<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listas_tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipe_id')->constrained('equipes')->restrictOnDelete();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->string('cor', 20)->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->foreignId('criada_por')->constrained('users')->restrictOnDelete();
            $table->timestamp('arquivada_em')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['equipe_id', 'arquivada_em']);
            $table->index(['equipe_id', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_tarefas');
    }
};
