<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Requests;

class RequestsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_requests()
    {
        $this->withoutExceptionHandling();

        $name = uniqid();

        $data = [
            'name' => $name,
            'grade' => 11,
            'status' => 'new'
        ];

        $response = $this->json('POST', apiUrl('requests'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('requests', $data);
    }
}
