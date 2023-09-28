<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'birthday';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name, 
            'title' => $this->title, 
            'phone_number' => $this->phone_number, 
            'body' => $this->body, 
            'birthday_date' => $this->birthday_date, 
            'user_id' => $this->user_id,
            'group_id' => $this->whenNotNull($this->group_id),
        ];
    }
}
