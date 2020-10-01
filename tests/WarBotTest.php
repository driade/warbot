<?php declare (strict_types = 1);

namespace WarBot\Tests;

use WarBot\Actions\SendMessageAction;
use WarBot\Repositories\MethodRepository;
use WarBot\Repositories\SubjectRepository;
use WarBot\WarBot;

class WarBotTest extends WarBotTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        (new MethodRepository)->reset();
        (new SubjectRepository)->reset();
    }

    /** @test */
    public function itCanOnlyBeOne()
    {
        $this->mock(SendMessageAction::class, function ($m) {
            return $m->shouldReceive('handle')->with(\Mockery::type('string'), \Mockery::type('string'));
        });

        echo "\n\n";

        do {
            $message = $this->container->get(WarBot::class)->handle();
            echo $message . "\n\n";
        } while ($message !== null);
    }
}
