<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Data\{Grade, Subject};
use App\Models\Request;

class DataControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_static_data()
    {
        $response = $this->json('POST', apiUrl('data/static'), [
            'fields' => ['grade', 'subject']
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'grade' => Grade::all()->toArray(),
                'subject' => Subject::all()->toArray()
            ]);
    }

    /** @test */
    public function it_returns_enum_data()
    {
        $field = 'status';

        $response = $this->json('POST', apiUrl('data/enum'), [
            'class' => 'request',
            'field' => $field
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(Request::getEnumValues($field));
    }
}
