<?php

declare(strict_types=1);

use function Hyperf\Collection\value;
use function Hyperf\Support\env;

return [
    'default' => [
        'hosts' => value(function () {
            return explode(',', env('ELASTICSEARCH_HOSTS', ''));
        }),
        'pool' => [
            'max_connections' => 64,
        ],
    ],
];
