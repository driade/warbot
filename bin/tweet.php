<?php declare (strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

(new DI\Container())->get(\WarBot\WarBot::class)->handle();
