<?php

namespace Kolette\Reporting\Seeds;

use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ReportCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ReportCategories::truncate();
        Schema::enableForeignKeyConstraints();

        $report_categories = ['Harassment', 'Inappropriate Behavior', 'Spamming', 'Other'];

        foreach ($report_categories as $category) {
            ReportCategories::firstOrCreate(['label' => $category]);
        }

        $profileReports = collect([
            'Pretending to Be Someone',
            'Fake Account',
            'Fake NAme',
            'Posting Inappropriate Things',
            'Harassment or Bullying',
        ]);

        $profileReports->each(function ($category) {
            ReportCategories::firstOrCreate([
                'label' => $category,
                'type' => 'users',
            ]);
        });

        $productReports = collect([
            'Inaccurate Description',
            'Promoting a business',
            'Animal Sales',
            'No Intent to Sell',
            'Weapon or Drug Sales',
            'Sexualized Content or Adult Products',
            'Descriminatory Listing',
            'Abusive or Harmful Content',
            'Scam',
            'Appears to be Counterfeit',
            'Child Abuse',
        ]);

        $productReports->each(function ($category) {
            ReportCategories::firstOrCreate([
                'label' => $category,
                'type' => 'products',
            ]);
        });

        $postReports = collect([
            'False News',
            'Spam',
            'Harassment',
            'Hate Speech',
            'Nudity or Sexual Activity',
            'Violence',
        ]);

        $postReports->each(function ($category) {
            ReportCategories::firstOrCreate([
                'label' => $category,
                'type' => 'posts',
            ]);
        });

        $refundReasons = collect([
            'Received the wrong item or size',
            'Missing/Incorrect/Food preparation issues',
            'Payment and Point Issue',
            'Food quality/health issues',
            'I didn\'t get my receipt',
            'Order not ready upon pickup',
            'Order cancellation'
        ]);

        $refundReasons->each(function ($category) {
            ReportCategories::firstOrCreate([
                'label' => $category,
                'type' => 'refund',
            ]);
        });
    }
}
