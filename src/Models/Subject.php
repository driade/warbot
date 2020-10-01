<?php declare (strict_types = 1);

namespace WarBot\Models;

class Subject
{
    /** @var int */
    public $alive;
    /** @var string */
    public $name;

    public function __construct(int $alive, string $name)
    {
        $this->alive = $alive;
        $this->name  = $name;
    }
}
