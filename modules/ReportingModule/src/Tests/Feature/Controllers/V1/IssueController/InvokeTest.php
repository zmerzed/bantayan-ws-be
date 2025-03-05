<?php

namespace Kolette\Reporting\Tests\Feature\Controllers\V1\IssueController;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\Issue;
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
    public function userCanReportIssue()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['email_verified_at' => null]),
            ['*']
        );

        $payload = Issue::factory()
            ->make(['reported_by' => $user->id])
            ->toArray();

        $payload['attachments'] = [
            UploadedFile::fake()->image('imagetest.png'),
            UploadedFile::fake()->image('anotherimage.png'),
        ];

        $this->actingAs($user)
            ->post('/api/v1/issues', $payload)
            ->assertSuccessful();

        $this->assertDatabaseHas('issues', Arr::except($payload, ['attachments']));
    }

    /** 
     * @test 
     * @group ReportingModule
     */
    public function userCanReportIssueWithoutAttachment()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['email_verified_at' => null]),
            ['*']
        );

        $payload = Issue::factory()
            ->make(['reported_by' => $user->id])
            ->toArray();

        $this->actingAs($user)
            ->post('/api/v1/issues', $payload)
            ->assertSuccessful();

        $this->assertDatabaseHas('issues', $payload);
    }
}
