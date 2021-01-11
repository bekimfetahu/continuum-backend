<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Facades\Tests\Setup\UserFactory;
use Facades\Tests\Setup\TransactionFactory;

use App\Model\Client;
use Illuminate\Http\Client\Factory;
use Tests\TestCase;

class ManageTransactionsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test Transactions
     *
     * @return void
     */

    public function test_only_authenticated_user_can_create_client_transaction()
    {

        $client = factory(Client::class)->create();

        $client->refresh();

        $attributes = [
            'client_id' => $client->id,
            'amount' => 20.50,

        ];

        $api = $this->baseUrl() . '/transactions'; //transactions resource route

        // Post with non logged user
        $this->postJson($api, $attributes)
            ->assertStatus(401); // check for non logged in user response status

        $this->assertDatabaseMissing('transactions', $attributes);

        // Test with logged user
        $user = UserFactory::create();
        $this->actingAs($user, 'api');

        $this->postJson($api, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Transaction created successfully",
            ]);
        $this->assertDatabaseHas('transactions', $attributes);
    }

    public function test_authenticated_user_delete_transaction()
    {
        $transaction = TransactionFactory::create();

        // Test with logged user
        $user = UserFactory::create();
        $this->actingAs($user, 'api');

        $attributes = [
            '_method' => 'DELETE',  // DELETE method
        ];

        $api = $this->baseUrl() . '/transactions';   // Transactions resource route URL

        $this->postJson($api.'/'.$transaction->id, $attributes)
            ->assertStatus(200)
            ->assertJson([
                'success' => "Transaction deleted successfully",
            ]);

        // Check if recorded in DB is removed
        $this->assertDatabaseMissing('transactions',['id'=>$transaction->id]);
    }

    /**
     * Test for form field validations
     */

    public function test_amount_is_required()
    {
        $client = factory(Client::class)->create();

        $client->refresh();

        $attributes = [
            'client_id' => $client->id,
        ];

        $api = $this->baseUrl() . '/transactions'; // transactions resource route

        $user = UserFactory::create();
        $this->actingAs($user, 'api');

        $this->postJson($api, $attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['amount' => ["The amount field is required."]],
            ]);
    }

    public function test_client_id_is_required()
    {
        $client = factory(Client::class)->create();

        $client->refresh();

        $attributes = [
            'amount' => 20.50,   // required amount
        ];

        $api = $this->baseUrl() . '/transactions'; // transactions resource route

        $user = UserFactory::create();
        $this->actingAs($user, 'api');

        $this->postJson($api, $attributes)
            ->assertStatus(422)
            ->assertJson([
                'errors' => ['client_id' => ["The client id field is required."]],
            ]);
    }

    public function test_valid_client_id_is_required()
    {
        $client = factory(Client::class)->create();

        $client->refresh();

        $attributes = [
            'amount' => 20.50,   // required amount
            'client_id'=> $client->id + 1 // non existing client
        ];

        $api = $this->baseUrl() . '/transactions'; // transactions resource route

        $user = UserFactory::create();
        $this->actingAs($user, 'api');

        // Client not found
        $this->postJson($api, $attributes)
            ->assertStatus(404);  // Model not found
    }
}

