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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id', 40)->unique();
            $table->string('job_title');
            $table->string('ort');
            $table->text('slug')->nullable();
            $table->integer("workload_from")->default(0);
            $table->integer("workload_to")->default(0);
            $table->integer('position')->default(0);
            $table->integer('work_experience')->default(0);
            $table->integer('work_time',0)->default(0);
            $table->boolean("is_active")->default(false);
            $table->boolean("leadership")->default(false);
            $table->boolean("is_published")->default(false);
            $table->boolean("on_top")->default(false);
            $table->boolean("is_promoted")->default(false);
            $table->timestamps();
            //foreign keys
            $table->unsignedBigInteger('contract_type_id');
            $table->unsignedBigInteger('employer_id');
            $table->unsignedBigInteger('employer_category_id');
            $table->unsignedBigInteger('job_category_id');
            $table->unsignedBigInteger('job_subcategory_id');
            $table->unsignedBigInteger('plz_id');
            $table->unsignedBigInteger('kantone_id');

            $table->foreign('contract_type_id')
                ->references('id')
                ->on('contract_types')
                ->onDelete('cascade');
            $table->foreign('employer_id')
                ->references('id')
                ->on('employers')
                ->onDelete('cascade');
            $table->foreign('employer_category_id')
                ->references('id')
                ->on('employer_categories')
                ->onDelete('cascade');
            $table->foreign('job_category_id')
                ->references('id')
                ->on('job_categories')
                ->onDelete('cascade');
            $table->foreign('job_subcategory_id')
                ->references('id')
                ->on('job_sub_categories')
                ->onDelete('cascade');
            $table->foreign('plz_id')
                ->references('id')
                ->on('plzs')
                ->onDelete('cascade');
            $table->foreign('kantone_id')
                ->references('id')
                ->on('kantones')
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
        Schema::dropIfExists('jobs');
    }
};
