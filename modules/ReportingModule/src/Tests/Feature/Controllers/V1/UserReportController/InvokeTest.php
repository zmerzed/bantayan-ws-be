<?php

namespace Kolette\Reporting\Tests\Feature\Controllers\V1\UserReportController;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InvokeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** 
     * @test 
     * @group ReportingModule
     */
    public function userCanReportAnotherUser()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['email_verified_at' => null]),
            ['*']
        );

        $reportedUser = User::factory()->create();

        $payload = [
            'report_type' => 'users',
            'report_id' => $reportedUser->id,
            'reason_id' => ReportCategories::factory()->create()->id,
            'description' => $this->faker->sentence,
            'attachments' => [
                UploadedFile::fake()->image('imagetest.png'),
                UploadedFile::fake()->image('anotherimage.png'),
            ],
        ];

        $response = $this->actingAs($user)
            ->post('/api/v1/report', $payload);

        $response->assertOk();
        $this->assertDatabaseHas(
            'reports',
            array_merge(
                Arr::except($payload, ['attachments', 'report_type', 'report_id']),
                [
                    'reportable_type' => User::class,
                    'reportable_id' => $reportedUser->id,
                ]
            )
        );
    }
}
