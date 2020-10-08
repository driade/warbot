<?php declare (strict_types = 1);

namespace WarBot\Repositories;

use WarBot\Models\Method;

class MethodRepository extends Repository
{
    /** @var string */
    private $path = __DIR__ . '/../../db/methods.txt';

    public function getOne():  ? Method
    {
        /** @var array<string> */
        $methods = \file($this->path);
        \array_shift($methods);

        $methods = \array_map(function (string $method) : Method {
            return new Method(\trim($method));
        }, $methods);

        shuffle($methods);

        return \array_pop($methods);
    }

    public function reset(): void
    {

    }
}
