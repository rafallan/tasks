<?php

namespace Database\Factories;

use App\Models\ItemChecklistTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemChecklistTarefa>
 */
class ItemChecklistTarefaFactory extends Factory
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
            'titulo' => fake()->sentence(3),
            'concluido' => false,
            'ordem' => fake()->numberBetween(0, 100),
            'criado_por' => User::factory(),
        ];
    }
}
