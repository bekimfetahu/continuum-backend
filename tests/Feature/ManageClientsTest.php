<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Facades\Tests\Setup\UserFactory;
use Facades\Tests\Setup\TransactionFactory;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Model\Client;
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
        $api = $this->baseUrl() . '/clients'; //client resource route

        $this->postJson($api, $attributes)
            ->assertStatus(401); // check for non logged in user response status

        $this->actingAs($user, 'api'); // check for logged in user

        $this->postJson($api, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Client created successfully",
            ]);
        $this->assertDatabaseHas('clients',['email'=>$attributes['email']]);
    }
    public function test_authenticated_user_can_update_client()
    {
        $attributes = [
            'first_name' => 'Jon',
            'last_name' => 'Do',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];

        $this->postData($attributes);  // Create a Client

        $client = Client::all()->first();  // Fetch Client

        $attributes['first_name'] = 'John';  // Change first name
        $attributes['_method'] = 'PATCH';    // Setup PATCH method for update

        $api = $this->baseUrl() . '/clients';   // Update route URL
        $this->postJson($api.'/'.$client->id, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Client updated successfully",
            ]);

        // Check if recorded in DB
        $this->assertDatabaseHas('clients',['email'=>$attributes['email'],'first_name'=>'John']);
    }
    public function test_authenticated_user_can_delete_client_with_no_transaction()
    {
        $attributes = [
            'first_name' => 'Jon',
            'last_name' => 'Do',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];

        $this->postData($attributes);  // Create a Client

        $client = Client::all()->first();  // Fetch Client

        $api = $this->baseUrl() . '/clients';   // Clients resource route URL

        $attributes = [
            '_method' => 'DELETE',  // DELETE method
        ];

        $this->postJson($api.'/'.$client->id, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Client deleted successfully",
            ]);

        // Check if recorded in DB
        $this->assertDatabaseMissing('clients',['id'=>$client->id]);
    }
    public function test_authenticated_user_cannot_delete_client_with_transactions()
    {
        $attributes = [
            'first_name' => 'Jon',
            'last_name' => 'Do',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];

        $this->postData($attributes);  // Create a Client

        $client = Client::all()->first();  // Fetch Client

        TransactionFactory::withClient($client)->create(); // Create a client transaction

        $api = $this->baseUrl() . '/clients';   // Clients resource route URL

        $attributes = [
            '_method' => 'DELETE',  // DELETE method
        ];

        $this->postJson($api.'/'.$client->id, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'error' => "Policy violation on delete restriction",
            ]);

        // Check if recorded in DB is still there (not able to delete because of constraint)
        $this->assertDatabaseHas('clients',['id'=>$client->id]);
    }

/************ Form field validation ******************/

    public function test_validate_first_name_required()
    {
        $attributes = [
            'last_name' => 'Do',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['first_name' => ["The first name field is required."]],
            ]);

    }

    public function test_validate_last_name_required()
    {
        $attributes = [
            'first_name' => 'John',
            'email' => 'jon.do@gmail.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['last_name' => ["The last name field is required."]],
            ]);

    }

    public function test_email_is_required()
    {
        $attributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'avatar' => $this->createFile(200, 200, 400)
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['email' => ["The email field is required."]],
            ]);
    }

    public function test_email_incorrect_formed()
    {
        $attributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'incorrect.com',
            'avatar' => $this->createFile(200, 200, 400)
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['email' => ["The email must be a valid email address."]],
            ]);
    }

    public function test_avatar_is_required()
    {
        $attributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'incorrect.com',
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['avatar' => ["The avatar field is required."]],
            ]);
    }

    public function test_avatar_is_valid_file()
    {
        $attributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'incorrect.com',
            'avatar' => UploadedFile::fake()->create('document.pdf')
        ];
        $this->postData($attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['avatar' =>
                    ['The avatar must be a file of type: jpeg, jpg, png, gif|max:10000.']
                ],
            ]);
    }

    public function test_avatar_with_wrong_dimensions()
    {
        $attributes = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jon.doe@example.com',
            'avatar' => $this->createFile(400, 200, 400), // Has to be min 100 x 100 or greater and equal sides
        ];
        $this->postData($attributes)
            ->assertStatus(200)
            ->assertJson([
                'error' => 'Avatar should have equal width and height with a minim of 100 x 100 pixels'
            ]);
    }

    /**
     * Prepare POST data
     * @param $attributes
     * @return \Illuminate\Testing\TestResponse
     */
    private function postData($attributes)
    {
        $user = UserFactory::create();

        $this->actingAs($user, 'api'); // check for logged in user
        $api = $this->baseUrl() . '/clients';

        return $this->postJson($api, $attributes);

    }

    private function createFile($width = 100, $height = 100, $size = 400)
    {
        Storage::fake('/app/public/avatars');

        return UploadedFile::fake()->image('avatar.jpg', $width, $height)->size($size);
    }

}

