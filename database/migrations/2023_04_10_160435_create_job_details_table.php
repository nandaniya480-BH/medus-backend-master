<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_details', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->boolean('by_arrangement')->default(false);
            $table->text("employer_description")->nullable();
            $table->text("job_content")->nullable();
            $table->text("job_description")->nullable();
            $table->string('c_person_name')->nullable();
            $table->string('c_person_last_name')->nullable();
            $table->string('c_person_email')->nullable();
            $table->string('c_person_phone')->nullable();
            $table->string('c_person_fax')->nullable();
            $table->enum('c_person_gender', ['male', 'female', 'transgender', 'bigender', 'another', null]);
            $table->string('job_file_url')->nullable();
            $table->string('job_url')->nullable();
            $table->string('apply_form_url')->nullable();

            $table->timestamps();
            // foreign key
            $table->unsignedBigInteger('job_id');
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
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
        Schema::dropIfExists('job_details');
    }
};