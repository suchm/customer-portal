<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Get address autocomplete suggestions.
     *
     * @param  Request  $request
     * @param  AddressService  $addressService
     * @return JsonResponse
     */
    public function getAddressAutocomplete(Request $request, AddressService $addressService): JsonResponse
    {
        $validated = $request->validate([
            'term' => 'required|string|min:1|max:255',
        ]);

        return $this->handleApiCall(function () use ($addressService, $validated) {
            return $addressService->getAddressAutocomplete($validated['term']);
        }, 'An error occurred while fetching address autocomplete details.');
    }

    /**
     * Handle API exceptions and return appropriate JSON responses.
     *
     * @param  callable  $callback
     * @param  string|null  $errorMessage
     * @return JsonResponse
     */
    private function handleApiCall(callable $callback, ?string $errorMessage = 'An error occurred'): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json($data);
        } catch (Exception $e) {
            if ($e->getMessage() === 'Rate limit exceeded') {
                return response()->json([
                    'error' => 'Rate limit error',
                    'message' => $e->getMessage(),
                ], 429); // HTTP 429 Too Many Requests
            }

            return response()->json([
                'error' => $errorMessage,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get address details by ID.
     *
     * @param  AddressService  $addressService
     * @param  string  $id
     * @return JsonResponse
     */
    public function getAddressDetails(AddressService $addressService, string $id): JsonResponse
    {
        return $this->handleApiCall(function () use ($addressService, $id) {
            return $addressService->getAddressDetails($id);
        }, 'An error occurred while fetching address details.');
    }
}
