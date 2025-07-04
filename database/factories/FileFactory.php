<?php

namespace Database\Factories;

use App\Enums;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' =>  $this->faker->randomElement(array_column(Enums\FileType::cases(), 'value')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
