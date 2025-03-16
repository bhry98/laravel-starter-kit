<?php

if (!function_exists(function: 'bhry98_date_formatted')) {
    function bhry98_date_formatted($date = ''): array
    {
        return [
            "iso" => $date,
            'format' => $date?->format(config(key: "bhry98-users-core.date.format")) ?? null,
            'format_time' => $date?->format(config(key: "bhry98-users-core.date.format_time")) ?? null,
            'format_notification' => $date?->format(config(key: "bhry98-users-core.date.format_notification")) ?? null,
            'format_without_time' => $date?->format(config(key: "bhry98-users-core.date.format_without_time")) ?? null,
        ];
    }
}

if (!function_exists(function: 'bhry98_app_settings')) {
    function bhry98_app_settings($key)
    {
        //        // check if key not exists in config key return null
        //        if (!in_array($key, array_keys(array: config(key: "bhry98-starter.app_settings.auto_login_after_registration") ?? []))) return null;
        // check if setting value exists in setting table return value
        // else return default value from config file
        return config(key: "bhry98-starter.app_settings.{$key}");
    }
}
