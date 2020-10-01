<?php declare (strict_types = 1);

namespace WarBot\Models;

class Method
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
