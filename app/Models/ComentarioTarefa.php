<?php

namespace App\Models;

use Database\Factories\ComentarioTarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $tarefa_id
 * @property int $user_id
 * @property string $comentario
 */
class ComentarioTarefa extends Model
{
    /** @use HasFactory<ComentarioTarefaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'comentarios_tarefas';

    protected $fillable = ['comentario', 'editado_em'];

    protected function casts(): array
    {
        return ['editado_em' => 'datetime'];
    }

    /** @return BelongsTo<Tarefa, $this> */
    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    /** @return BelongsTo<User, $this> */
    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
