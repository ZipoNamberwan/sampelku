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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('code')->nullable();
        });

        Schema::create('survey_user', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('survey_id')->constrained('surveys');
        });

        Schema::create('samples', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('subdistrict_code')->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->string('village_code')->nullable();
            $table->string('village_name')->nullable();
            $table->string('bs_code')->nullable();
            $table->string('sample_unique_code');
            $table->string('sample_name');
            $table->string('sample_address')->nullable();
            $table->string('job')->nullable();
            $table->string('category')->nullable();
            $table->string('kbli')->nullable();
            $table->string('strata')->nullable();
            $table->boolean('is_selected')->default(true);
            $table->enum('type', ['Utama', 'Cadangan'])->nullable();
            $table->enum('status_recommendation', ['Diajukan', 'Diterima', 'Ditolak'])->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->nullable();
            $table->foreignId('pcl_id')->nullable()->constrained('users');
            $table->foreignId('pml_id')->nullable()->constrained('users');
            $table->foreignId('survey_id')->nullable()->constrained('surveys');
            $table->foreignId('replacement_id')->nullable()->constrained('samples');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
