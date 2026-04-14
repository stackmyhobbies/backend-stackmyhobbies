<?php

namespace Database\Seeders;

use App\Models\ProgressStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'En emisión',
            'Finalizado',
            'Viendo',
            'Por ver',
            'Por estrenar',
            'Pausado',
            'Abandonado'
        ];

        foreach ($statuses as $name) {
            ProgressStatus::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}
