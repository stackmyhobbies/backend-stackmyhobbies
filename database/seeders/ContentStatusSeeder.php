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
            'en emisión',
            'finalizado',
            'viendo',
            'por ver',
            'por estrenar',
            'pausado',
            'abandonado',
        ];

        foreach ($statuses as $name) {
            ProgressStatus::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}
