<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontTest extends TestCase
{

    public function test_homepage(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


}
