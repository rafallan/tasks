<?php

namespace App\Models;

use Database\Factories\HistoricoTarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tarefa_id
 * @property int|null $user_id
 * @property string $evento
 */
class HistoricoTarefa extends Model
{
    /** @use HasFactory<HistoricoTarefaFactory> */
    use HasFactory;

    protected $table = 'historicos_tarefas';

    public const UPDATED_AT = null;

    protected $fillable = ['evento', 'campo', 'valor_anterior', 'valor_novo', 'metadados'];

    protected function casts(): array
    {
        return ['valor_anterior' => 'array', 'valor_novo' => 'array', 'metadados' => 'array'];
    }

    /** @return BelongsTo<Tarefa, $this> */
    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    /** @return BelongsTo<User, $this> */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
