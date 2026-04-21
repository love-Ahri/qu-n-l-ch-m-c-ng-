<?php
/**
 * Application Configuration
 */
return [
    'name'         => 'Quản lý Dự Án & Chấm Công',
    'base_url'     => '/ddonf/public',
    'timezone'     => 'Asia/Ho_Chi_Minh',
    'upload_path'  => __DIR__ . '/../uploads',
    'excel_path'   => __DIR__ . '/../uploads/excel',
    'session'      => [
        'name'     => 'DDONF_SESSION',
        'lifetime' => 3600,
    ],
    'pagination'   => [
        'per_page' => 15,
    ],
    'working'      => [
        'hours_per_day'  => 8,
        'hours_per_week' => 40,
        'start_time'     => '08:00',
        'end_time'       => '17:00',
        'ot_multiplier'  => 1.5,
    ],
];
