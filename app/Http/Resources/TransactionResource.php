<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        // Get the seller and buyer user objects
        $seller = User::find($this->owner);
        $buyer = User::find($this->customer);

        return [
            'id' => $this->id,
            'seller' => $seller ? [
                'id' => $seller->id,
                'name' => $seller->name,
                 'phone' => $seller->phone,
            ] : null,
            'buyer' => $buyer ? [
                'id' => $buyer->id,
                'name' => $buyer->name,
                 'phone' => $buyer->phone,
            ] : null,
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
