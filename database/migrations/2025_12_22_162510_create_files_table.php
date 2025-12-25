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
        Schema::create('files', function (Blueprint $table) {
            $table->id('file_id');

            $table->string('operation_name', 50)
                ->comment('Table name of the related model');

            $table->unsignedBigInteger('table_id')
                ->comment('Primary key of the related table');

            $table->string('path', 150)
                ->comment('Storage path of the file');

            $table->string('name', 100)
                ->comment('Original file name');

            $table->unsignedBigInteger('size')
                ->comment('File size in bytes');

            $table->string('info', 255)
                ->nullable()
                ->comment('Optional file metadata / description');

            $table->unsignedBigInteger('created_by')
                ->comment('User who uploaded the file');

            $table->unsignedBigInteger('deleted_by')
                ->nullable()
                ->comment('User who deleted the file');

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['operation_name', 'table_id']);

            // Foreign keys
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
