<?php

namespace App\Models;

use Database\Factories\EquipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property int $criado_por
 * @property bool $ativo
 */
class Equipe extends Model
{
    /** @use HasFactory<EquipeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected function casts(): array
    {
        return ['ativo' => 'boolean'];
    }

    /** @return BelongsTo<User, $this> */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    /** @return BelongsToMany<User, $this> */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['tipo_participacao', 'ativo'])
            ->withTimestamps();
    }

    /** @return HasMany<ListaTarefa, $this> */
    public function listasTarefas(): HasMany
    {
        return $this->hasMany(ListaTarefa::class);
    }
}
