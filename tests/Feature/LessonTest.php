<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Lesson\Lesson};

class LessonTest extends TestCase
{
   /** @test */
    public function it_gets_lesson_clients()
    {
        $lesson = Lesson::find(3826);
        dump($lesson->clients);
        $this->assertTrue(count($lesson->clients) > 0);
    }
}
