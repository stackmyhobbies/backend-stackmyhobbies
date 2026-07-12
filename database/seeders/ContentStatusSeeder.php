<?php

namespace Database\Seeders;

use App\Models\ProgressStatus;
use Illuminate\Database\Seeder;

class ContentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            // Media status
            'Airing',
            'Finished',
            'Upcoming',

            // User progress status
            'Watching',
            'Plan to Watch',
            'On Hold',
            'Dropped',
        ];

        foreach ($statuses as $name) {
            ProgressStatus::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}
