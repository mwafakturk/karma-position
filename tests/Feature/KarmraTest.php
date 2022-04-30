<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KarmaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRandomUser()
    {
        $user = User::all()->random();
        $response = $this->get('/api/v1/user/' . $user->id . '/karma-position');
        $response->assertStatus(200);
    }

    public function testWithNumberOfUsers()
    {
        $randomNumber = random_int(1, 1000);
        $user = User::all()->random();
        $response = $this->get('/api/v1/user/' . $user->id . "/karma-position/" . $randomNumber);
        $response->assertStatus(200);
    }
}
