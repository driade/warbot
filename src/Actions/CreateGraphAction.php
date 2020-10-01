<?php declare (strict_types = 1);

namespace WarBot\Actions;

use Intervention\Image\ImageManagerStatic as Image;
use WarBot\Repositories\SubjectRepository;

class CreateGraphAction
{
    /** @var SubjectRepository */
    private $subject_repository;

    public function __construct(SubjectRepository $subject_repository)
    {
        $this->subject_repository = $subject_repository;
    }

    public function handle(): string
    {
        $subjects = $this->subject_repository->getAllByName();

        Image::configure(['driver' => 'gd']);
        $width  = 800;
        $height = 800;
        $image  = Image::canvas($width, $height, '#fff');

        $columns = 5;

        $name = microtime() . ".jpg";

        foreach ($subjects as $index => $subject) {

            $x = $index - (floor($index / $columns) * $columns);
            $y = floor($index / $columns);

            $x = 10 + $x * round($width / $columns);
            $y = $y * 30 + 30;

            $box = null;
            // echo "\n\n";
            // echo $subject->name . ": $x - y: $y\n";
            $image->text($subject->name, (int) $x, (int) $y, function ($font) use (&$box) {
                $font->file('fonts/arial.ttf');
                $font->size(12);
                $font->color('#000');

                $box = $font->getBoxSize();
            });

            if ( ! $subject->alive) {
                $image->rectangle((int) $x, (int) $y - 10, (int) $x + $box['width'], (int) $y - 10 + $box['height'], function ($draw) {
                    $draw->background('rgba(255,0,0,0.5)');
                });
            }

        }

        $image->save('images/' . $name, 80, 'jpg');

        return "images/" . $name;
    }
}
