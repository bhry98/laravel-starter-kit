<?php

namespace Bhry98\LaravelStarterKit\Http\Resources\core\locations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GovernorateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "name" => $this->name,
            "local_name" => $this->local_name,
            "total_cities" => $this->when(!is_null($this->cities_count),$this->cities_count),
            "users_count" => $this->when(!is_null($this->users_count),$this->users_count),
            "country" => CountryResource::make($this->whenLoaded(relationship: 'country')),
        ];
    }
}
