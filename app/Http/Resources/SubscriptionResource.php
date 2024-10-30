<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => optional($this->pivot->start_date ? Carbon::parse($this->pivot->start_date) : null)->format('Y-m-d'),
            'end_date' => optional($this->pivot->end_date ? Carbon::parse($this->pivot->end_date) : null)->format('Y-m-d'),
            'price' => $this->price,
            'status' => [
                'name' => $this->status_name,
                'code' => $this->status_code
            ],
            'products' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
