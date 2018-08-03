<?php

return [



    'models'    => 'App/Kernel/Models',

    /*
   |--------------------------------------------------------------------------
   | Veritabanı Bağlantıları
   |--------------------------------------------------------------------------
   |
   | Boilerplate esnek veritabanı bağlantısı sunmaktadır, istediğiniz
   | veritabanı sürücüsü ile bağlantı sağlayabilirsiniz.
   |
   */

    'driver'    => 'mysql',
    'host'      => 'localhost',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'schema'    => 'uyeler',


    /*
   |--------------------------------------------------------------------------
   | Redis Veritabanı
   |--------------------------------------------------------------------------
   |
   | Redis sunucunuz var ise aşağıdaki bilgileri doldurabilirsiniz.
   |
   */

    'redis' => [
    'cluster' => false,
    'default' => [
        'host' => 'localhost',
        'password' => 'REDISPASSWORD',
        'port' => 'REDISPORT',
        'database' => 0,
        ],
    ],

];
