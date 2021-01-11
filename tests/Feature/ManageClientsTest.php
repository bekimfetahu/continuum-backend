<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Facades\Tests\Setup\UserFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageClientsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_only_authenticated_user_can_create_client()
    {

        $user = UserFactory::create();

        $attributes = [
            'first_name' => 'Jon',
            'last_name' => 'Do',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];
        $api = $this->baseUrl() . '/clients';

        $this->postJson($api, $attributes)->assertStatus(401); // check for non logged in user

        $this->actingAs($user, 'api'); // check for logged in user

        $this->postJson($api, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Client created successfully",
            ]);
    }

    private function createFile($width = 100, $height = 100, $size = 400)
    {
        Storage::fake('/app/public/avatars');

        return UploadedFile::fake()->image('avatar.jpg', $width, $height)->size($size);
    }
}
