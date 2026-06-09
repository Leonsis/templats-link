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
        Schema::create('rotas_dinamicas', function (Blueprint $table) {
            $table->id();
            $table->string('tema', 100);
            $table->string('pagina', 100);
            $table->string('rota', 255);
            $table->string('nome_rota', 100);
            $table->string('controller', 100)->default('TemasController');
            $table->string('metodo', 100)->default('renderizarPaginaDinamica');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['tema', 'pagina']);
            $table->index(['tema', 'ativo']);
            $table->unique(['tema', 'pagina']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rotas_dinamicas');
    }
};
