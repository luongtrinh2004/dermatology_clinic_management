<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->bigIncrements('id'); // BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, tự động tạo PRIMARY KEY
            $table->string('name', 255)->collation('utf8mb4_unicode_ci')->nullable(false); // VARCHAR(255) NOT NULL
            $table->string('cccd', 255)->collation('utf8mb4_unicode_ci')->nullable(false); // VARCHAR(255) NOT NULL
            $table->unique('cccd'); // UNIQUE INDEX `cccd` (`cccd`) USING BTREE
        });

        // Đặt thuộc tính bảng
        DB::statement('ALTER TABLE medical_records COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE medical_records ENGINE = InnoDB');
        DB::statement('ALTER TABLE medical_records AUTO_INCREMENT = 21');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};