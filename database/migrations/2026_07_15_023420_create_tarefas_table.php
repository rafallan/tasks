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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_tarefa_id')->constrained('listas_tarefas')->cascadeOnDelete();
            $table->string('titulo');
            $table->longText('descricao')->nullable();
            $table->foreignId('criada_por')->constrained('users')->restrictOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('status', 30)->default('pendente');
            $table->string('prioridade', 20)->default('media');
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('prazo')->nullable();
            $table->dateTime('concluida_em')->nullable();
            $table->unsignedInteger('estimativa_minutos')->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['responsavel_id', 'status']);
            $table->index(['lista_tarefa_id', 'ordem']);
            $table->index(['status', 'prazo']);
            $table->index('criada_por');
            $table->index('prioridade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
