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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock')->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->string('imagen', 255)->nullable();
            $table->foreignId('id_marca')->constrained('marcas')->onDelete('restrict');
            $table->foreignId('id_categoria')->constrained('categorias')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
