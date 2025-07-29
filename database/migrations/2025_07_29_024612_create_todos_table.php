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
		Schema::create('todos', function (Blueprint $table) {
			$table->id();
			$table->string('nama'); // nama Todo
			$table->text('deskripsi')->nullable(); // deskripsi Todo, opsional
			$table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke user
			$table->date('due_date')->nullable(); // tanggal tenggat penyelesaian
			$table->timestamp('completed_at')->nullable(); // waktu selesai todo
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('todos');
	}
};
