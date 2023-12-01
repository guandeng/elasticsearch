<?php

declare(strict_types=1);
/**
 * This file is part of elasticsearch.
 *
 * @link     https://github.com/guandeng/elasticsearch
 * @document https://github.com/guandeng/elasticsearch/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Guandeng\Elasticsearch;

use Hyperf\Contract\ConfigInterface;

class ClientProxy extends Client
{
    public function __construct(ConfigInterface $config, protected string $poolName='default')
    {
        parent::__construct($config);
    }
}
