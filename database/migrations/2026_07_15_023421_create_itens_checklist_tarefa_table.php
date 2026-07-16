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
        Schema::create('itens_checklist_tarefa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->cascadeOnDelete();
            $table->string('titulo');
            $table->boolean('concluido')->default(false);
            $table->unsignedInteger('ordem')->default(0);
            $table->foreignId('criado_por')->constrained('users')->restrictOnDelete();
            $table->foreignId('concluido_por')->nullable()->constrained('users')->restrictOnDelete();
            $table->dateTime('concluido_em')->nullable();
            $table->timestamps();

            $table->index(['tarefa_id', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_checklist_tarefa');
    }
};
