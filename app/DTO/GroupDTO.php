<?php

namespace App\DTO;

use App\Http\Requests\{GroupStoreRequest, GroupUpdateRequest};
use Illuminate\Http\Request;

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

    public static function fromRequest(Request $request, string $userId): GroupDTO {
        return new self(
            $request->input('name'),
            $request->input('description'),
            $userId
        );
    }

    public static function fromStoreRequest(GroupStoreRequest $request, string $userId): GroupDTO {
        return self::fromRequest($request, $userId);
    }

    public static function fromUpdateRequest(GroupUpdateRequest $request, string $userId): GroupDTO {
        return self::fromRequest($request, $userId);
    }
}