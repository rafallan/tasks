<?php

namespace Database\Factories;

use App\Models\HistoricoTarefa;
use App\Models\Tarefa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HistoricoTarefa>
 */
class HistoricoTarefaFactory extends Factory
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
            'evento' => 'tarefa_criada',
        ];
    }
}
