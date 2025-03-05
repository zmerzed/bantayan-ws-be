<?php

namespace Kolette\Reporting\Tests\Feature\Controllers\V1\Admin\UserController;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        /** @var User */
        $user = Sanctum::actingAs(
            User::factory()->create(['email_verified_at' => null]),
            ['*']
        );
        $token = $user->createToken(config('app.name'))->plainTextToken;

        return [$user, $token];
    }

    /** 
     * @test 
     * @group ReportingModule
     */
    public function authenticatedUserCanViewReportList()
    {
        Report::factory()
            ->count(2)
            ->create();

        list(, $token) = $this->authenticate();

        $response = $this->json('GET', '/api/v1/admin/report/users', [], ['Authorization' => "Bearer $token"]);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'description',
                    'report_type',
                    'reported_at',
                    'reported_by',
                    'reason_id',
                ],
            ],
        ]);

        $result = $response->decodeResponseJson();
        $this->assertEquals(data_get($result, 'meta.total', 0), 2); // check if count is same.
    }

    /** 
     * @test 
     * @group ReportingModule
     */
    public function unauthenticatedUserCannotViewReportList()
    {
        User::factory()->create();

        $this->json('GET', '/api/v1/admin/report/users')
            ->assertStatus(401);
    }
}
