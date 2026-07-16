<?php

namespace Database\Factories;

use App\Models\ListaTarefa;
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListaTarefa>
 */
class ListaTarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'equipe_id' => Equipe::factory(),
            'nome' => fake()->words(2, true),
            'descricao' => fake()->sentence(),
            'cor' => '#'.fake()->hexColor(),
            'ordem' => fake()->numberBetween(0, 100),
            'criada_por' => User::factory(),
        ];
    }
}
