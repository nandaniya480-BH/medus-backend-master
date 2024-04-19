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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->default('');
            $table->string('last_name', 80)->default('');
            $table->string('image_url', 80)->default('');
            $table->enum('gender', ['male', 'female', 'transgender', 'bigender', 'another', null]);
            $table->date('age')->nullable();
            $table->string('phone', 60)->default('');
            $table->string('mobile', 60)->default('');
            $table->string('email', 80)->default('');
            $table->string('address', 200)->default('');
            // $table->json('workload')->nullable();
            $table->integer("workload_from")->default(0);
            $table->integer("workload_to")->default(0);
            $table->enum('position', [0,1,2,3])->default(0);
            $table->enum('work_time', [0,1,2,3])->default(0);
            $table->text('description')->nullable();
            $table->integer("prefered_distance")->default(0);
            $table->boolean("leadership")->default(false);
            $table->string('ort', 200)->nullable();
            $table->boolean("is_active")->default(false);

            $table->timestamps();
            //foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kantone_id')->nullable();
            $table->unsignedBigInteger('plz_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('kantone_id')
                ->references('id')
                ->on('kantones')
                ->onDelete('cascade');
            $table->foreign('plz_id')
                ->references('id')
                ->on('plzs')
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
        Schema::dropIfExists('employees');
    }
};
