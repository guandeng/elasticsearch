<?php

declare(strict_types=1);
/**
 * This file is part of elasticsearch.
 *
 * @link     https://github.com/guandeng/elasticsearch
 * @document https://github.com/guandeng/elasticsearch/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Guandeng\Elasticsearch\Index;

use Guandeng\Elasticsearch\ClientFactory;
use Guandeng\Elasticsearch\ClientProxy;
use Guandeng\Elasticsearch\Index\Traits\Migrateable;
use Guandeng\Elasticsearch\Query\Builder;
use Psr\Container\ContainerInterface;
use Hyperf\Context\ApplicationContext;

/**
 * @mixin \Elasticsearch\Client
 * @mixin \Guandeng\Elasticsearch\Query\Builder
 */
abstract class AbstractIndex
{
    use Migrateable;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $type = '_doc';

    /**
     * @var string
     */
    protected $pool = 'default';

    /**
     * @var ClientProxy
     */
    protected $client;

    /**
     * @var Builder
     */
    protected $query;

    public function __call(string $name, array $arguments)
    {
        if (is_callable([$this->query, $name])) {
            $result = $this->query->{$name}(...$arguments);

            if (! ($result instanceof Builder)) {
                return $result;
            }

            return $this;
        }

        if (is_callable([$this->client, $name])) {
            if (isset($arguments[0]) && is_array($arguments[0])) {
                $params = $arguments[0];
            } else {
                $params = $this->query->compileSearch();
            }

            if ($name == 'count') {
                unset($params['_source']);
            }

            return $this->client->{$name}($params);
        }
    }

    public static function query(): self
    {
        return (new static())->newQuery();
    }

    public function newQuery(): self
    {
        /** @var ContainerInterface $container */
        $container = ApplicationContext::getContainer();
        /** @var ClientFactory $clientFactory */
        $clientFactory = $container->get(ClientFactory::class);
        $this->client = $clientFactory->get($this->pool);
        $this->query = new Builder($this->index);

        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
