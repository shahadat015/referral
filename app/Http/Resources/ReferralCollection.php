<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReferralCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($referral) {
                return [
                    'id' => $referral->id,
                    'referrer_name' => $referral->referrer->name,
                    'email' => $referral->email,
                    'status' => $referral->is_registered,
                    'created_at' => $referral->created_at->format('d M Y h:mA'),
                ];
            }),
        ];
    }
}
