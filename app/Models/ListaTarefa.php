<?php

namespace App\Models;

use Database\Factories\ListaTarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $equipe_id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $cor
 * @property int $ordem
 * @property int $criada_por
 * @property \Carbon\CarbonImmutable|null $arquivada_em
 */
class ListaTarefa extends Model
{
    /** @use HasFactory<ListaTarefaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'listas_tarefas';

    protected $fillable = ['nome', 'descricao', 'cor', 'ordem', 'arquivada_em'];

    protected function casts(): array
    {
        return ['arquivada_em' => 'datetime'];
    }

    /** @return BelongsTo<Equipe, $this> */
    public function equipe(): BelongsTo
    {
        return $this->belongsTo(Equipe::class);
    }

    /** @return BelongsTo<User, $this> */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criada_por');
    }

    /** @return HasMany<Tarefa, $this> */
    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }
}
