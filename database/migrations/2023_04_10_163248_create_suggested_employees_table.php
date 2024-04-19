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
        Schema::create('suggested_employees', function (Blueprint $table) {
            $table->id();
            $table->enum('employee_response', ['pending', 'accepted', 'declined']);
            $table->enum('employer_response', ['pending', 'accepted', 'declined']);
            $table->integer('employee_match')->default(0);
            $table->integer('employer_match')->default(0);
            $table->boolean('re_suggest')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('employer_id');
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
            $table->foreign('employer_id')
                ->references('id')
                ->on('employers')
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
        Schema::dropIfExists('suggested_employees');
    }
};
