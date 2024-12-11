<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class AddressService
{
    private string $apiEndpoint;
    private string $apiKey;
    private string $cacheKey = "address_api_usage_count";
    private int $rateLimit = 20;

    public function __construct(protected Client $client)
    {
        $this->apiEndpoint = config('services.get_address.api_endpoint');
        $this->apiKey = config('services.get_address.api_key');
    }

    /**
     * Get address autocomplete suggestions.
     *
     * @param  string  $term
     * @return array|null
     * @throws Exception
     */
    public function getAddressAutocomplete(string $term): ?array
    {
        return Cache::remember("address_autocomplete_{$term}", 600, function () use ($term) {

            $this->checkRateLimit();

            $response = $this->client->get("{$this->apiEndpoint}/autocomplete/{$term}?api-key={$this->apiKey}&all=true");

            $this->incrementRateLimit();

            return json_decode($response->getBody()->getContents(), true);
        });
    }

    /**
     * Enforce the API rate limit.
     *
     * @throws Exception
     */
    private function checkRateLimit(): void
    {
        // Get the current usage or initialize it
        $usage = Cache::get($this->cacheKey, 0);

        // Check if the rate limit is exceeded
        if ($usage >= $this->rateLimit) {
            throw new Exception('Rate limit exceeded');
        }
    }

    /**
     * Increment the rate limit count.
     */
    private function incrementRateLimit(): void
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::add($this->cacheKey, 1, now()->endOfDay()); // Initialize the cache with expiration
        } else {
            Cache::increment($this->cacheKey); // Increment without resetting expiration
        }
    }

    /**
     * Get address details by ID.
     *
     * @param  string  $id
     * @return array|null
     * @throws Exception
     */
    public function getAddressDetails(string $id): ?array
    {
        return Cache::remember("address_details_{$id}", 600, function () use ($id) {

            $this->checkRateLimit();

            $response = $this->client->get("{$this->apiEndpoint}/get/{$id}?api-key={$this->apiKey}");

            $this->incrementRateLimit();

            return json_decode($response->getBody()->getContents(), true);
        });
    }
}
