<?php

namespace Bhry98\LaravelStarterKit\Http\Resources\users;
use Bhry98\LaravelStarterKit\Http\Resources\core\enums\EnumsResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CityResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CountryResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\GovernorateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "type" => $this->type ? EnumsResource::make($this->Type) : null,
            "display_name" => $this->display_name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "username" => $this->username,
            "email" => $this->email,
            "national_id" => $this->national_id,
            "birthdate" => $this->birthdate ? bhry98_date_formatted($this->birthdate) : null,
            "phone_number" => $this->phone_number,
            "must_change_password" => $this->must_change_password,
            "country" =>CountryResource::make($this->whenLoaded(relationship: 'country')),
            "governorate" =>GovernorateResource::make($this->whenLoaded(relationship: 'governorate')),
            "city" =>CityResource::make($this->whenLoaded(relationship: 'city')),
        ];
    }
}
