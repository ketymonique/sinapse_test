<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at?->toIso8601String(),
        ];

        if ($this->deleted_at !== null) {
            $data['deleted_at'] = $this->deleted_at->toIso8601String();
        }

        return $data;
    }
}
