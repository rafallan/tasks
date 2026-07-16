<?php

namespace Database\Factories;

use App\Models\ComentarioTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComentarioTarefa>
 */
class ComentarioTarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tarefa_id' => Tarefa::factory(),
            'user_id' => User::factory(),
            'comentario' => fake()->paragraph(),
        ];
    }
}
