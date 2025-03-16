<?php

namespace Bhry98\LaravelStarterKit\Http\Resources\core\locations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "name" => $this->name,
            "local_name" => $this->local_name,
            "total_users" => $this->when(!is_null($this->users_count), $this->users_count),
            "country" => CountryResource::make($this->whenLoaded("country")),
            "governorate" => GovernorateResource::make($this->whenLoaded("governorate")),
        ];
    }
}
