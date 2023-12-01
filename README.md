# elasticsearch

[![Latest Stable Version](https://poser.pugx.org/guandeng/elasticsearch/version.png)](https://packagist.org/packages/guandeng/elasticsearch)
[![Total Downloads](https://poser.pugx.org/guandeng/elasticsearch/d/total.png)](https://packagist.org/packages/guandeng/elasticsearch)
[![GitHub license](https://img.shields.io/github/license/guandeng/elasticsearch)](https://github.com/guandeng/elasticsearch)

A component for elasticsearch

## Installation

```bash
composer require guandeng/elasticsearch
```

## Publish configure

```bash
php bin/hyperf.php vendor:publish guandeng/elasticsearch
```

## Usage

### Index

- Create

```php
namespace App\Indices;

use Guandeng\Elasticsearch\Index\AbstractIndex;

class Test extends AbstractIndex
{
    protected $index = 'test';
}
```

- Create by command

```bash
php bin/hyperf.php gen:index test
```

- Query

```php
use App\Indices\Test;

Test::query()->where(...)->search();
```

- UpdateByQuery

```php
use App\Indices\Test;

Test::query()->where(...)->script(['source' => 'ctx.source.xxx = value'])->updateByQuery();
```

- Count

```php
use App\Indices\Test;

Test::query()->where(...)->count();
```

## Migrate

- Index

```php
namespace App\Indices;

use Guandeng\Elasticsearch\Index\AbstractIndex;

class Test extends AbstractIndex
{
    protected $index = 'test';
    protected $type = '_doc';
    protected $settings = [
        // your settings
    ];
    protected $properties = [
        // your properties
    ];

    public function getMigration(): Closure
    {
        return function ($index) {
            // migrate data
        };
    }
}
```

- Run migrate

```bash
php bin/hyperf.php elasticsearch:migrate "App\\Indices\\Test" [--migrate] [--update] [--recreate]
```

### ClientProxy

```php
namespace App\Proxy;

use Guandeng\Elasticsearch\ClientProxy;

class FooClient extends ClientProxy
{
    protected $poolName = 'foo';
}
```
