<?php

namespace App\Models;

use App\TipoParticipacaoEquipe;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $ativo
 */
#[Fillable(['name', 'email', 'password', 'ativo'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, SoftDeletes, TwoFactorAuthenticatable;

    /** @return BelongsToMany<Equipe, $this> */
    public function equipes(): BelongsToMany
    {
        return $this->belongsToMany(Equipe::class)
            ->withPivot(['tipo_participacao', 'ativo'])
            ->withTimestamps();
    }

    /** @return HasMany<Equipe, $this> */
    public function equipesCriadas(): HasMany
    {
        return $this->hasMany(Equipe::class, 'criado_por');
    }

    /** @return HasMany<Tarefa, $this> */
    public function tarefasResponsavel(): HasMany
    {
        return $this->hasMany(Tarefa::class, 'responsavel_id');
    }

    public function gerenciaEquipe(Equipe $equipe): bool
    {
        return $this->equipes()
            ->whereKey($equipe)
            ->wherePivot('tipo_participacao', TipoParticipacaoEquipe::Gestor->value)
            ->wherePivot('ativo', true)
            ->exists();
    }

    public function participaDaEquipe(Equipe $equipe): bool
    {
        return $this->equipes()
            ->whereKey($equipe)
            ->wherePivot('ativo', true)
            ->exists();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'ativo' => 'boolean',
        ];
    }
}
