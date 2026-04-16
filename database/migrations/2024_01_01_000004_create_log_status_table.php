<?php
// database/migrations/2024_01_01_000004_create_log_status_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_registro')->constrained('registros')->onDelete('cascade');
            $table->enum('status_anterior', ['PENDENTE', 'EM_ANDAMENTO', 'RESOLVIDO']);
            $table->enum('status_novo', ['PENDENTE', 'EM_ANDAMENTO', 'RESOLVIDO']);
            $table->foreignId('id_admin')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status');
    }
};
