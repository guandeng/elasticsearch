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

use Guandeng\Elasticsearch\Exception\InvalidClientProxyException;
use Hyperf\Contract\ConfigInterface;

use function Hyperf\Support\make;
use function Hyperf\Tappable\tap;

class ClientFactory
{
    /**
     * @var ClientProxy[]
     */
    protected $proxies;

    public function __construct(ConfigInterface $config)
    {
        foreach ($config->get('elasticsearch') as $poolName => $configure) {
            $this->proxies[$poolName] = make(ClientProxy::class, ['poolName' => $poolName]);
        }
    }

    /**
     * @return ClientProxy
     */
    public function get(string $poolName)
    {
        return tap($this->proxies[$poolName] ?? null, function ($proxy) {
            if (! $proxy instanceof ClientProxy) {
                throw new InvalidClientProxyException('Invalid Client proxy.');
            }
        });
    }
}
