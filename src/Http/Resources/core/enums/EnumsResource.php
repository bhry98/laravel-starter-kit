<?php

namespace Bhry98\LaravelStarterKit\Http\Resources\core\enums;

use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CountryResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\GovernorateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnumsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "name" => $this->name,
        ];
    }
}
