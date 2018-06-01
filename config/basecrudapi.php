<?php

return [
    'package' => 'BaseCRUDApi',

    'model' => base_path() . '/p4scu41/basecrudapi/src/Models',

    'controller' => base_path() . '/p4scu41/basecrudapi/src/Http/Controllers',

    'migration' => base_path() . '/p4scu41/basecrudapi/database/migrations',

    'database' => '/p4scu41/basecrudapi/database/migrations',

    'routes' => base_path() . '/p4scu41/basecrudapi/src/routes/api.php',

    'controllerNameSpace' => 'p4scu41\BaseCRUDApi\\Http\\Controllers',

    'modelNameSpace' => 'p4scu41\BaseCRUDApi\Models',
];
