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

use Closure;
use Elasticsearch\ClientBuilder;
use Guandeng\Elasticsearch\Exception\MissingConfigException;
use Guandeng\Elasticsearch\Query\Builder;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Coroutine\Coroutine;
use Hyperf\Guzzle\RingPHP\CoroutineHandler;
use Hyperf\Guzzle\RingPHP\PoolHandler;

use function Hyperf\Support\make;
use function Hyperf\Collection\data_get;
use function Hyperf\Tappable\tap;

/**
 * @mixin \Elasticsearch\Client
 */
class Client
{
    protected string $poolName = 'default';

    protected ClientBuilder $clientBuilder;

    public function __construct(ConfigInterface $config)
    {
        if (! $config->has($configKey = 'elasticsearch.' . $this->poolName)) {
            throw new MissingConfigException('Config item ' . $configKey . ' is missing.');
        }

        $this->clientBuilder = tap(ClientBuilder::create(), function (ClientBuilder $builder) use ($config, $configKey) {
            $poolConfig = (array) $config->get($configKey, []);

            if (Coroutine::inCoroutine()) {
                $maxConnections = (int) data_get($poolConfig, 'pool.max_connections');

                if ($maxConnections > 0) {
                    $handler = make(PoolHandler::class, [
                        'option' => [
                            'max_connections' => (int) $maxConnections,
                        ],
                    ]);
                } else {
                    $handler = make(CoroutineHandler::class);
                }

                $builder->setHandler($handler);
            }

            $hosts = (array) data_get($poolConfig, 'hosts', []);
            $builder->setHosts($hosts);
        });
    }

    public function __call($name, $arguments)
    {
        if (isset($arguments[0])) {
            if ($arguments[0] instanceof Closure) {
                $arguments[0] = $arguments[0](new Builder());
            }

            if ($arguments[0] instanceof Builder) {
                $arguments[0] = $arguments[0]->compileSearch();
            }
        }

        return $this->clientBuilder->build()->{$name}(...$arguments);
    }
}
