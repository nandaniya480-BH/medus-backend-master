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
        Schema::create('job_languages', function (Blueprint $table) {
            $table->id();
            $table->enum('level', [1, 2, 3, 4, 5, 6, null]);
            $table->timestamps();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('language_id');
            $table->unique(array('job_id', 'language_id'));
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_languages');
    }
};
