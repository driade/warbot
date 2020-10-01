<?php declare (strict_types = 1);

namespace WarBot\Repositories;

use WarBot\Models\Method;

class MethodRepository extends Repository
{
    private $path = __DIR__ . '/../../db/methods.txt';

    public function getOne(): Method
    {
        /** @var array */
        $methods = \file($this->path);
        \array_shift($methods);

        $methods = \array_map(function (string $method) {
            return new Method(\trim($method));
        }, $methods);

        shuffle($methods);

        return \array_pop($methods);
    }

    public function reset()
    {

    }
}
