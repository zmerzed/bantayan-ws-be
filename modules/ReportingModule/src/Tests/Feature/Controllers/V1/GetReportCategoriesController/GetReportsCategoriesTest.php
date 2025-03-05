<?php

namespace Kolette\Reporting\Tests\Feature\Controllers\V1\GetReportCategoriesController;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetReportsCategoriesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** 
     * @test 
     * @group ReportingModule
     * @group reportUser
     */
    public function authenticatedUserShouldBeAbleToGetCategoryList()
    {
        ReportCategories::factory()
            ->count(2)
            ->create();

        $user = Sanctum::actingAs(
            User::factory()->create(['email_verified_at' => null]),
            ['*']
        );
        $token = $user->createToken(config('app.name'))->plainTextToken;

        $this->json('GET', '/api/v1/report/categories', [], ['Authorization' => "Bearer $token"])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'label']],
            ]);
    }

    /** 
     * @test 
     * @group ReportingModule
     * @group reportUser
     */
    public function unauthenticatedUser()
    {
        $this->json('GET', '/api/v1/report/categories')->assertStatus(401);
    }
}
