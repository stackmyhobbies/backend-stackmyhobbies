<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Shonen',
            'Seinen',
            'Shojo',
            'Josei',
            'Acción',
            'Aventura',
            'Terror',
            'Horror',
            'Comedia',
            'Romance',
            'Fantasia',
            'Ciencia ficción',
            'Drama',
            'Sobrenatural',
            'Misterio',
            'Psicológico',
            'Suspenso'
        ];

        foreach ($tags as $name) {
            Tag::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}
