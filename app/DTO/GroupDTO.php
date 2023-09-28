<?php

namespace App\DTO;

use App\Http\Requests\GroupStoreRequest;

class GroupDTO
{
    public string $name;
    public ?string $description;
    public string $user_id;

    public function __construct(
        string $name, 
        ?string $description,
        string $user_id,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->user_id = $user_id;
    }

    public static function fromRequest(GroupStoreRequest $request): GroupDTO {
        return new self(
            $request->input('name'),
            $request->input('description'),
            auth()->user()->id
        );
    }
}