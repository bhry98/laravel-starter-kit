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
    "input_types" => [
        'button',
        'checkbox',
        'color',
        'date',
        'datetime-local',
        'email',
        'file',
        'hidden',
        'image',
        'month',
        'number',
        'password',
        'radio',
        'range',
        'reset',
        'search',
        'submit',
        'tel',
        'text',
        'time',
        'url',
        'week',
    ],
    /**
     * overwrite the app config files
     */
    "config" => [
        // overwrite the config/auth.php file
        "auth" => [
            "user_model" => UsersCoreUsersModel::class,
        ],
        // overwrite the config/mail.php file
        "mail" => [
            'smtp' => [
                'transport' => 'smtp',
                'scheme' => env(key: 'B_MAIL_SCHEME'),
                'url' => env(key: 'B_MAIL_URL'),
                'host' => env(key: 'B_MAIL_HOST', default: "mail0.serv00.com"),
                'port' => env(key: 'B_MAIL_PORT', default: 465),
                'username' => env(key: 'B_MAIL_USERNAME', default: "code.faster@bhry98.serv00.net"),
                'password' => env(key: 'B_MAIL_PASSWORD', default: "P@ssw0rd"),
                'timeout' => null,
                'local_domain' => env(key: 'MAIL_EHLO_DOMAIN', default: parse_url(env(key: 'APP_URL', default: 'http://localhost'), component: PHP_URL_HOST)),
            ],
            'from' => [
                'address' => env(key: 'B_MAIL_FROM_ADDRESS', default: 'code.faster@bhry98.serv00.net'),
                'name' => env(key: 'B_MAIL_FROM_NAME', default: 'Code Faster'),
            ],
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
        "default_user_forgot_password_way" => null, // [ email ] // if null by default forgot password via email
    ]
];