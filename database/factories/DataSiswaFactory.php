<?php

namespace Database\Factories;

use App\Models\DataSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataSiswa>
 */
class DataSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DataSiswa::class;
    public function definition(): array
{
    $prefix = '131232750027'; // 12 digit
    $suffix = str_pad($this->faker->unique()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);


    return [
        'nis' => $prefix . $suffix,
        'kelas' => $this->faker->randomElement(['X', 'XI', 'XII']),
        'nama' => $this->faker->name,
    ];
}

}
