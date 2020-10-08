<?php declare (strict_types = 1);

namespace WarBot\Repositories;

use WarBot\Models\Subject;

class SubjectRepository extends Repository
{
    /** @var string */
    private $path = __DIR__ . '/../../db/subjects.txt';

    /** @return array<Subject> */
    public function getAllByName(): array
    {
        $subjects = $this->open();

        /** @phpstan-ignore-next-line */
        usort($subjects, function (Subject $subject): string {
            return $subject->name;
        });

        return $subjects;
    }

    /** @return array<Subject> */
    public function getAlive(): array
    {
        $subjects = $this->open();

        $subjects = \array_filter($subjects, function (Subject $subject): bool {
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
        /** @var array<string> */
        $subjects = \file($this->path);
        \array_shift($subjects);

        $subjects = \array_map(function (string $subject): Subject{
            $subject = \explode(",", trim($subject));
            return new Subject((int) $subject[0], $subject[1]);
        }, $subjects);

        return $subjects;
    }

    /** @param array<Subject> $subjects */
    private function write(array $subjects): void
    {
        $subjects = \array_map(function (Subject $subject): string {
            return ((string) $subject->alive) . ',' . $subject->name;
        }, $subjects);

        $subjects = \array_merge(['alive,name'], $subjects);
        $subjects = \implode("\n", $subjects);

        \file_put_contents($this->path, $subjects);
    }

    public function reset(): void
    {
        $subjects = $this->open();

        $subjects = \array_map(function (Subject $subject): Subject{
            $subject->alive = 1;
            return $subject;
        }, $subjects);

        $this->write($subjects);
    }
}
