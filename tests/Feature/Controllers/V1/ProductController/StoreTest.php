<?php

namespace Tests\Feature\Controllers\V1\ProductController;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticatedMerchantCanStoreProduct()
    {
        Storage::fake();

        $user = Sanctum::actingAs(
            $userFactory = User::factory()->create(),
            ['*']
        );

        $userFactory->syncRoles(Role::MERCHANT);
        $userFactory->payouts_enabled = true;
        $userFactory->save();

        $token = $user->createToken(config('app.name'))->plainTextToken;

        $this->json('POST', '/api/v1/marketplace/products', [
            'title' => 'product 1',
            'description' => 'The quick brown fox',
            'is_special' => false,
            'is_counted' => true,
            'points' => 1,
            'price' => 100,
            'places_id' => 'ChIJfzKeUZ9t-TIRc8V5n1gkOrU',
            'currency' => 'usd',
            'places_address' => 'Address from places',
            'thumbnail' => [
                UploadedFile::fake()->image('test.jpeg')
            ],
            'product_selections' => [
                [
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ]
            ]
        ], ['Authorization' => "Bearer $token"])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'price_in_cents',
                    'formatted_price',
                    'places_address',
                    'places_id',
                    'seller',
                    'selections' => [
                        [
                            'id',
                            'is_multiple',
                            'name',
                            'options' => [[
                                'id',
                                'name',
                                'is_available',
                                'price',
                                'price_in_cents',
                                'formatted_price'
                            ]]
                        ]
                    ]
                ]
            ]);

        $this->json('POST', '/api/v1/marketplace/products', [
            'title' => 'product 1',
            'description' => 'The quick brown fox',
            'is_special' => false,
            'is_counted' => true,
            'points' => 1,
            'price' => 100,
            'currency' => 'usd',
            'places_id' => 'ChIJfzKeUZ9t-TIRc8V5n1gkOrU',
            'places_address' => 'Address from places',
            'thumbnail' => [
                UploadedFile::fake()->image('test.jpeg')
            ],
            'product_selections' => []
        ], ['Authorization' => "Bearer $token"])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'price_in_cents',
                    'formatted_price',
                    'places_address',
                    'places_id',
                    'seller',
                    'selections' => []
                ]
            ]);
    }
}
