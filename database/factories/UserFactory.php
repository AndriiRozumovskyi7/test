<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $url = "https://ui-avatars.com/api/?size=70&name=" . $name;
        $contents = Http::get($url)->body();
        $fileName = Str::random(10) . '.png';
        Storage::disk('public')->put($fileName, $contents);

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+380'.fake()->randomNumber(9, true),
            'position_id' => Position::all()->random()->id,
            'photo' => $fileName
        ];
    }
}
