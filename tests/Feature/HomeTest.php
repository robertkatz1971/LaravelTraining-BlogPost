<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageIsWorking()
    {
        $response = $this->get(route('home.index'));
        $response->assertSeeText('Hello World!');
        $response->assertStatus(200);
    }

    public function testContactPageIsWorking()
    {
        $response = $this->get(route('home.contact'));
        $response->assertSeeText('Contact Page!');
        $response->assertStatus(200);
    }
}
