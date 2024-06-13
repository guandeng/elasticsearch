<?php

declare(strict_types=1);
/**
 * This file is part of elasticsearch.
 *
 * @link     https://github.com/guandeng/elasticsearch
 * @document https://github.com/guandeng/elasticsearch/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
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
        'retries' => 3,
    ],
];
