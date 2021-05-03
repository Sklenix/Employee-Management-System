<?php

return [
    'driver'     => env('MAIL_DRIVER', 'smtp'),
    'host'       => env('MAIL_HOST', 'smtp.gmail.com'),
    'port'       => env('MAIL_PORT', 587),
    'from'       => ['address' =>'tozondoservice@gmail.com', 'name' => 'tozondo.com '],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME','tozondoservice@gmail.com'),
    'password'   => env('MAIL_PASSWORD','Bilbo369#')
];
