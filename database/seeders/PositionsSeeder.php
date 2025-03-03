<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            "Lawyer", "Content manager", "Security", "Designer"
        ];

        foreach ($positions as $position) {
            Position::create(['name' => $position]);
        }
    }
}
