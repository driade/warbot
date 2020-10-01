<?php declare (strict_types = 1);

namespace WarBot\Repositories;

use Dotenv\Dotenv;

class ConfigRepository extends Repository
{
    public function __construct()
    {
        $dotenv = Dotenv::create(__DIR__ . '/../../');
        $dotenv->load();
    }

    public function get(string $key)
    {
        return getenv($key);
    }
}
