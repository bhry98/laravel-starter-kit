<?php
return [
    "types" => [
        [
            'column_name' => 'type_id',
            'relation' => \Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel::class,
            'api_access' => false,
            'can_delete' => false,
            'parent_id' => null,
            'locales' => [
                'ar' => "مستخدم مسؤول",
                "en" => "Administrator",
            ]
        ],
        [
            'column_name' => 'type_id',
            'relation' => \Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel::class,
            'api_access' => true,
            'can_delete' => true,
            'parent_id' => null,
            'locales' => [
                'ar' => "مستخحدم عادي",
                "en" => "Normal User",
            ]
        ],
    ],
    "genders" => [
        [
            'column_name' => 'gender_id',
            'relation' => \Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel::class,
            'api_access' => true,
            'can_delete' => false,
            'parent_id' => null,
            'locales' => [
                'ar' => "ذكر",
                "en" => "Male",
            ]
        ],
        [
            'column_name' => 'gender_id',
            'relation' => \Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel::class,
            'api_access' => true,
            'can_delete' => false,
            'parent_id' => null,
            'locales' => [
                'ar' => "أنثى",
                "en" => "Female",
            ]
        ],
    ],
];