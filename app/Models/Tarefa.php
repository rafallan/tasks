<?php

namespace App\Models;

use App\PrioridadeTarefa;
use App\StatusTarefa;
use Database\Factories\TarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $lista_tarefa_id
 * @property string $titulo
 * @property string|null $descricao
 * @property int $criada_por
 * @property int|null $responsavel_id
 * @property StatusTarefa $status
 * @property PrioridadeTarefa $prioridade
 * @property \Carbon\CarbonImmutable|null $data_inicio
 * @property \Carbon\CarbonImmutable|null $prazo
 * @property \Carbon\CarbonImmutable|null $concluida_em
 * @property int|null $estimativa_minutos
 * @property int $ordem
 */
class Tarefa extends Model
{
    /** @use HasFactory<TarefaFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lista_tarefa_id', 'titulo', 'descricao', 'responsavel_id', 'status', 'prioridade',
        'data_inicio', 'prazo', 'concluida_em', 'estimativa_minutos', 'ordem',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusTarefa::class,
            'prioridade' => PrioridadeTarefa::class,
            'data_inicio' => 'datetime',
            'prazo' => 'datetime',
            'concluida_em' => 'datetime',
        ];
    }

    /** @return BelongsTo<ListaTarefa, $this> */
    public function lista(): BelongsTo
    {
        return $this->belongsTo(ListaTarefa::class, 'lista_tarefa_id');
    }

    /** @return BelongsTo<User, $this> */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criada_por');
    }

    /** @return BelongsTo<User, $this> */
    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    /** @return HasMany<ItemChecklistTarefa, $this> */
    public function itensChecklist(): HasMany
    {
        return $this->hasMany(ItemChecklistTarefa::class)->orderBy('ordem');
    }

    /** @return HasMany<ComentarioTarefa, $this> */
    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioTarefa::class)->latest();
    }

    /** @return HasMany<HistoricoTarefa, $this> */
    public function historicos(): HasMany
    {
        return $this->hasMany(HistoricoTarefa::class)->latest();
    }
}
