<?php declare (strict_types = 1);

namespace WarBot\Repositories;

use WarBot\Models\Subject;

class SubjectRepository extends Repository
{
    private $path = __DIR__ . '/../../db/subjects.txt';

    public function getAllByName(): array
    {
        $subjects = $this->open();

        usort($subjects, function (Subject $subject) {
            return $subject->name;
        });

        return $subjects;
    }

    public function getAlive(): array
    {
        $subjects = $this->open();

        $subjects = \array_filter($subjects, function (Subject $subject) {
            return $subject->alive === 1;
        });

        return $subjects;
    }

    public function kill(Subject $subject): void
    {
        $subjects = $this->open();

        foreach ($subjects as &$mysubject) {
            if ($mysubject->name === $subject->name) {
                $mysubject->alive = 0;
                $this->write($subjects);
                return;
            }
        }
    }

    /** @return array<Subject> */
    private function open(): array
    {
        /** @var array */
        $subjects = \file($this->path);
        \array_shift($subjects);

        $subjects = \array_map(function (string $subject) {
            $subject = \explode(",", trim($subject));
            return new Subject((int) $subject[0], $subject[1]);
        }, $subjects);

        return $subjects;
    }

    private function write(array $subjects): void
    {
        $subjects = \array_map(function (Subject $subject) {
            return ((string) $subject->alive) . ',' . $subject->name;
        }, $subjects);

        $subjects = \array_merge(['alive,name'], $subjects);
        $subjects = \implode("\n", $subjects);

        \file_put_contents($this->path, $subjects);
    }

    public function reset()
    {
        $subjects = $this->open();

        $subjects = \array_map(function (Subject $subject) {
            $subject->alive = 1;
            return $subject;
        }, $subjects);

        $this->write($subjects);
    }
}
