<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_key', 255)->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('file_name', 255);
            $table->longText('description', 255);
            $table->integer('downloads')->default(0);
            $table->integer('max_downloads')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->boolean('expired')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
