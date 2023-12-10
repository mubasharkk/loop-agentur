<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'job_title'        => $this->job_title,
            'email_address'    => $this->email_address,
            'name'             => implode(' ', [$this->first_name, $this->last_name]),
            'registered_since' => $this->registered_since,
            'phone'            => $this->phone,
        ];
    }
}
