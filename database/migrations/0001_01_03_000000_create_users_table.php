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
        Schema::create('users', function (Blueprint $table) {
            // Clave primaria y campos básicos
            $table->id();  
            $table->string('dni')->unique();  
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('tipo_usuario');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->json('clases')->nullable();
            
            // Relación con tarifas
            $table->foreignId('tarifa_id')
                  ->nullable()
                  ->constrained('tarifas')
                  ->onDelete('set null');
            
            // Fechas de tarifa
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_expiracion')->nullable();

            // Método de pago
            $table->string('metodo_pago')->nullable(); // "tarjeta" o "cuenta_bancaria"
            $table->string('cuenta_bancaria')->nullable(); // IBAN
            $table->string('numero_tarjeta')->nullable(); // Número de tarjeta
            $table->string('cvv')->nullable(); // CVV
            $table->date('fecha_caducidad')->nullable(); // Fecha de caducidad

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
