<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    // public function test_database_works()
    // {
    //     User::factory(20)->create();

    //     $this->assertEquals(20, User::all()->count());
    // }

    public function test_the_users_api_returns_a_successful_response()
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    public function test_the_users_api_returns_paginated_cached_data()
    {
        $response1 = $this->get('/users?page=1');
        $response2 = $this->get('/users?page=1');
        $response3 = $this->get('/users?page=2');

        $response1->assertStatus(200);
        $response2->assertStatus(200);
        $response3->assertStatus(200);

        $this->assertTrue(Cache::has('users.page.1'));
        $this->assertTrue(Cache::has('users.page.2'));

        $this->assertEquals(
            $response1->getContent(),
            $response2->getContent(),
            'The second request for page 1 did not return cached data'
        );
    }
}
