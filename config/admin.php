<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin path
    |--------------------------------------------------------------------------
    |
    | The URL segment the admin area lives under, e.g. clintonagburum.com/{path}.
    | Set ADMIN_PATH in .env to something private and hard to guess — never
    | commit the real value. Route names (admin.login, admin.overview, ...)
    | are unaffected; only the visible URL changes.
    |
    */

    'path' => env('ADMIN_PATH', 'admin'),

];
