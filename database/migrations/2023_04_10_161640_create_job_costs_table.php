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
        Schema::create('job_costs', function (Blueprint $table) {
            $table->id();
            $table->boolean("is_paid")->default(false);
            $table->string("employer_email");
            $table->timestamps();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('price_id');
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
            $table->foreign('price_id')
                ->references('id')
                ->on('prices')
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
        Schema::dropIfExists('job_costs');
    }
};
