<?php

use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;

return [
    "apis" => [
        /**
         * For global api configurations
         */
        "prefix" => "api",
        "namespace" => "bhry98",
        "middleware" => [
            'api',
            \Bhry98\LaravelStarterKit\Http\Middlewares\GlobalResponseLocale::class
        ],
        /**
         * For helpers apis configurations
         * locations, enums ,..etc
         */
        // "helpers" => [
        // "prefix" => "helpers",
        // "namespace" => "helpers",
        // "middleware" => [],
        // ],
    ],
    "date" => [
        "now" => now(tz: 'Africa/Cairo'),
    ],
    /**
     * overwrite the app config file
     */
    "config" => [
        // overwrite the config/auth.php file
        "auth" => [
            "user_model" => UsersCoreUsersModel::class,
        ],
    ],
    "validations" => [
        "users" => [
            "national_id" => [
                "required" => false,
            ],
            "phone_number" => [
                "required" => true,
            ],
        ]
    ],
    "app_settings" => [
        "auto_login_after_registration" => true,
        "default_user_type_in_registration" => null, // add default type UUID code from your database if null require add type UUID code in registration
        "default_user_login_way" => null, // [ email, phone_number, username, national_id ] // if null by default login via username
    ]
];