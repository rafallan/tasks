<?php

namespace Database\Factories;

use App\Models\Tarefa;
use App\Models\ListaTarefa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tarefa>
 */
class TarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lista_tarefa_id' => ListaTarefa::factory(),
            'titulo' => fake()->sentence(4),
            'descricao' => fake()->paragraph(),
            'criada_por' => User::factory(),
            'status' => 'pendente',
            'prioridade' => 'media',
            'ordem' => fake()->numberBetween(0, 100),
        ];
    }
}
