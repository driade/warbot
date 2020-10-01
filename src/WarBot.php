<?php declare (strict_types = 1);

namespace WarBot;

use WarBot\Actions\CreateGraphAction;
use WarBot\Actions\SendMessageAction;
use WarBot\Models\Method;
use WarBot\Models\Subject;
use WarBot\Repositories\MethodRepository;
use WarBot\Repositories\SubjectRepository;

class WarBot
{
    /** @var Subject|null */
    private $winner;
    /** @var Subject|null */
    private $loser;
    /** @var array */
    private $alive;
    /** @var string */
    private $message;
    /** @var string */
    private $graph;

    /** @var SubjectRepository */
    private $subject_repository;
    /** @var SendMessageAction */
    private $send_message_action;
    /** @var CreateGraphAction */
    private $create_graph_action;
    /** @var MethodRepository */
    private $method_repository;

    public function __construct(SubjectRepository $subject_repository, SendMessageAction $send_message_action, CreateGraphAction $create_graph_action, MethodRepository $method_repository)
    {
        $this->subject_repository  = $subject_repository;
        $this->send_message_action = $send_message_action;
        $this->create_graph_action = $create_graph_action;
        $this->method_repository   = $method_repository;
    }

    /** @return string|null */
    public function handle()
    {
        $this->winner = null;
        $this->loser  = null;

        if ($this->fight() === false) {
            return null;
        }

        $this->createMessage();
        $this->createGraph();
        $this->tweet();

        return $this->message;
    }

    private function fight(): bool
    {
        $this->alive = $this->subject_repository->getAlive();

        if (\count($this->alive) < 2) {
            return false;
        }

        shuffle($this->alive);
        $this->winner = array_pop($this->alive);

        shuffle($this->alive);
        $this->loser = $this->alive[0];

        $this->subject_repository->kill($this->loser);

        return true;
    }

    private function createMessage()
    {
        $method = $this->method_repository->getOne();

        if ($method !== null && $this->winner !== null && $this->loser !== null) {
            $verb = "and there're " . \count($this->alive) . " participants left";
            if (\count($this->alive) === 1) {
                $verb = "and there's 1 participant left";
            }
            $this->message = $this->winner->name . " has killed " . $this->loser->name . " " . $method->name . ", $verb in #PHPWarBots";
        }
    }

    private function tweet()
    {
        $this->send_message_action->handle($this->message, $this->graph);
    }

    private function createGraph()
    {
        $image       = $this->create_graph_action->handle();
        $this->graph = __DIR__ . '/../' . $image;
    }
}
