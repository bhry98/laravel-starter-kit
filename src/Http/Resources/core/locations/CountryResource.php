<?php

namespace Bhry98\LaravelStarterKit\Http\Resources\core\locations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "country_code" => $this->country_code,
            "name" => $this->name,
            "local_name" => $this->local_name,
            "flag" => $this->flag,
            "lang_key" => $this->lang_key,
            "system_lang" => $this->system_lang,
            "total_users" => $this->when(!is_null($this->users_count),$this->users_count),
            "governorates_count" => $this->when(!is_null($this->governorates_count),$this->governorates_count),
            "cities_count" => $this->when(!is_null($this->cities_count),$this->cities_count),
        ];
    }
}
