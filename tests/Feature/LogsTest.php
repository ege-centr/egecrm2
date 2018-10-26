<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\{Task, User, Admin\Admin};

class LogsTest extends TestCase
{
    // use DatabaseTransactions;

    /** @test */
    public function it_creates_logs()
    {
        $_SESSION['user'] = Admin::find(69);
        Task::create([
            'text' => 'testy-test'
        ]);
    }
}
