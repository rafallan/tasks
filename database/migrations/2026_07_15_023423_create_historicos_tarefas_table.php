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
        Schema::create('historicos_tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('evento', 100);
            $table->string('campo', 100)->nullable();
            $table->json('valor_anterior')->nullable();
            $table->json('valor_novo')->nullable();
            $table->json('metadados')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tarefa_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos_tarefas');
    }
};
