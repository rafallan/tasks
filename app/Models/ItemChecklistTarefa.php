<?php

namespace App\Models;

use Database\Factories\ItemChecklistTarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tarefa_id
 * @property string $titulo
 * @property bool $concluido
 * @property int $ordem
 * @property int $criado_por
 * @property int|null $concluido_por
 */
class ItemChecklistTarefa extends Model
{
    /** @use HasFactory<ItemChecklistTarefaFactory> */
    use HasFactory;

    protected $table = 'itens_checklist_tarefa';

    protected $fillable = ['titulo', 'concluido', 'ordem', 'concluido_por', 'concluido_em'];

    protected function casts(): array
    {
        return ['concluido' => 'boolean', 'concluido_em' => 'datetime'];
    }

    /** @return BelongsTo<Tarefa, $this> */
    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    /** @return BelongsTo<User, $this> */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
