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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor');
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->string('isbn')->nullable()->unique();
            $table->unsignedSmallInteger('ano_publicacao')->nullable();
            $table->unsignedInteger('quantidade_total')->default(1);
            $table->unsignedInteger('quantidade_disponivel')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
