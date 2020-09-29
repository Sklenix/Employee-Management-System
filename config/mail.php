<?php

return [
    'driver'     => env('MAIL_DRIVER', 'smtp'),
    'host'       => env('MAIL_HOST', 'smtp.gmail.com'),
    'port'       => env('MAIL_PORT', 465),
    'from'       => ['address' =>'tozondoservice@gmail.com', 'name' => 'Resetování hesla'],
    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
    'username'   => env('MAIL_USERNAME','tozondoservice@gmail.com'),
    'password'   => env('MAIL_PASSWORD','Bilbo369#'),
    'sendmail'   => '/usr/sbin/sendmail -bs',
];
