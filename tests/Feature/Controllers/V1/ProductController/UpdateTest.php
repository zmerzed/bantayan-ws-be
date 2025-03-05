<?php

namespace Tests\Feature\Controllers\V1\ProductController;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Marketplace\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticatedMerchantCanUpdateProduct()
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

        $product = $userFactory->products()->first();

        $this->json('PUT', '/api/v1/marketplace/products/' . $product->id, [
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
            'product_selections' => [
                [
                    'id' => $product->productSelections()->first()->id,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ],
                [
                    'id' => null,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ]
            ]
        ], ['Authorization' => "Bearer $token"])
            ->assertOk()
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
            ])
            ->assertJsonCount(2, 'data.selections')
            ->assertJsonCount(2, 'data.selections.0.options')
            ->assertJsonCount(1, 'data.selections.1.options');
    }

    /** @test */
    public function authenticatedCustomerCantUpdateProduct()
    {
        Storage::fake();

        $merchant = Sanctum::actingAs(
            $merchantFactory = User::factory()->create(),
            ['*']
        );

        $merchantFactory->syncRoles(Role::MERCHANT);
        $merchantFactory->payouts_enabled = true;
        $merchantFactory->save();

        $tokenMerchant = $merchant->createToken(config('app.name'))->plainTextToken;

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
        ], ['Authorization' => "Bearer $tokenMerchant"])
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


        $user = Sanctum::actingAs(
            $userFactory = User::factory()->create(),
            ['*']
        );

        $userFactory->syncRoles(Role::USER);
        $userFactory->payouts_enabled = false;
        $userFactory->save();

        $tokenCustomer = $user->createToken(config('app.name'))->plainTextToken;

        $product = $merchantFactory->products()->first();

        $this->json('PUT', '/api/v1/marketplace/products/' . $product->id, [
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
            'product_selections' => [
                [
                    'id' => $product->productSelections()->first()->id,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ],
                [
                    'id' => null,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ]
            ]
        ], ['Authorization' => "Bearer $tokenCustomer"])
            ->assertForbidden();
    }


    /** @test */
    public function authenticatedNotOwnerMerchantCantUpdateProduct()
    {
        Storage::fake();

        $merchant = Sanctum::actingAs(
            $merchantFactory = User::factory()->create(),
            ['*']
        );

        $merchantFactory->syncRoles(Role::MERCHANT);
        $merchantFactory->payouts_enabled = true;
        $merchantFactory->save();

        $tokenMerchant = $merchant->createToken(config('app.name'))->plainTextToken;

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
        ], ['Authorization' => "Bearer $tokenMerchant"])
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


        $user = Sanctum::actingAs(
            $merchantFactory2 = User::factory()->create(),
            ['*']
        );

        $merchantFactory2->syncRoles(Role::MERCHANT);
        $merchantFactory2->payouts_enabled = true;
        $merchantFactory2->save();

        $tokenCustomer = $user->createToken(config('app.name'))->plainTextToken;

        $product = $merchantFactory->products()->first();

        $this->json('PUT', '/api/v1/marketplace/products/' . $product->id, [
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
            'product_selections' => [
                [
                    'id' => $product->productSelections()->first()->id,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ],
                [
                    'id' => null,
                    'name' => 'Select 1',
                    'is_multiple' => false,
                    'options' => [
                        [
                            'id' => null,
                            'name' => 'Option 1',
                            'is_available' => true,
                            'price' => 2
                        ]
                    ]
                ]
            ]
        ], ['Authorization' => "Bearer $tokenCustomer"])
            ->assertForbidden();
    }
}
