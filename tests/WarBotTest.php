<?php declare (strict_types = 1);

namespace WarBot\Tests;

use WarBot\Actions\CreateGraphAction;
use WarBot\Actions\SendMessageAction;
use WarBot\Models\Method;
use WarBot\Models\Subject;
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
    public function itCanOnlyBeOne(): void
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

    public function testNoWar(): void
    {
        // Setup a subject repository mock with only one subject to fetch.
        $subject_repository = $this->createMock(SubjectRepository::class);
        $subject_repository->expects($this->once())->method('getAlive')->willReturn([new Subject(1, 'name1')]);

        // Setup some shell mocks strictly to instantiate WarBot.
        $method_repository   = $this->createMock(MethodRepository::class);
        $create_graph_action = $this->createMock(CreateGraphAction::class);
        $send_message_action = $this->createMock(SendMessageAction::class);

        $warbot = new WarBot($subject_repository, $send_message_action, $create_graph_action, $method_repository);

        // The response should be null.
        self::assertNull($warbot->handle());
    }

    /** @test */
    public function testWarWithTwoUsers(): void
    {
        // Setup a subject repository mock with two subjects to fetch.
        // asserting that we do kill off a subject.
        $subject_repository = $this->createMock(SubjectRepository::class);
        $subject_repository->expects($this->once())->method('getAlive')->willReturn([new Subject(1, 'name1'), new Subject(1, 'name2')]);
        $subject_repository->expects($this->once())->method('kill')->with(self::isInstanceOf(Subject::class));

        // Setup a method repository to provide a kill method.
        $method_repository = $this->createMock(MethodRepository::class);
        $method_repository->method('getOne')->willReturn(new Method('with a knife'));

        // Setup a graph action to return a graph image path.
        $create_graph_action = $this->createMock(CreateGraphAction::class);
        $create_graph_action->expects(self::once())->method('handle')->willReturn('images/sweetgraph.jpg');

        // Setup a send message action to assert the message after the round states we have one participant left.
        // and also assert that our provided image path was also provided.
        $send_message_action = $this->createMock(SendMessageAction::class);
        $send_message_action->expects($this->once())->method('handle')->with(
            self::stringContains("and there's 1 participant left"),
            self::stringContains("images/sweetgraph.jpg")
        );

        // Instantiate WarBot with mocks to exercise 3 assertions.
        $warbot = new WarBot($subject_repository, $send_message_action, $create_graph_action, $method_repository);
        $warbot->handle();
    }
}
