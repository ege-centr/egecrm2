<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Utils\Sms;

class SmsTest extends TestCase
{
    /** @test **/
    public function it_gets_messages()
    {
        $result = Sms::get('79252727210');
        file_put_contents('test.txt', $result);


        // $result = file_get_contents('test.txt');
        // // dump($result);

        $result = json_decode($result);
        dump($result);
    }
}
