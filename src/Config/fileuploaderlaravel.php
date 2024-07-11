<?php

return [

    /*
    |--------------------------------------------------------------------------
    | File Uploader Requirements
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'ALLOWED_IMAGE_TYPE' => env('ALLOWED_IMAGE_TYPE') ? env('ALLOWED_IMAGE_TYPE') : [],
    'MAX_UPLOAD_IMAGE_SIZE' => env('MAX_UPLOAD_IMAGE_SIZE') ? env('MAX_UPLOAD_IMAGE_SIZE') : 2048, // in KB
    'DEFAULT_IMAGE_FORMAT' => env('DEFAULT_IMAGE_FORMAT') ? env('DEFAULT_IMAGE_FORMAT') : 'webp',
    'DEFAULT_IMAGE_QUALITY' => env('DEFAULT_IMAGE_QUALITY') ? env('DEFAULT_IMAGE_QUALITY') : 80,
];
