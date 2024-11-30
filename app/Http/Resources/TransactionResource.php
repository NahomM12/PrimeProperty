<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'seller' => [
                'id' => $this->owner->id,
                'name' => $this->owner->user->name,
                'phone' => $this->owner->phone,
            ],
            'buyer' => $this->when($this->customer, [
                'id' => $this->customer->id,
                'name' => $this->customer->user->name,
                'phone' => $this->customer->phone,
            ]),
            'property' => new PropertyResource($this->whenLoaded('property')),
            'transaction_type' => $this->transaction_type,
            'transaction_date' => $this->transaction_date,
            'price' => $this->price,
            'commission' => $this->commission,
            'rent_start_date' => $this->when($this->transaction_type === 'rent', $this->rent_start_date),
            'rent_end_date' => $this->when($this->transaction_type === 'rent', $this->rent_end_date),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}