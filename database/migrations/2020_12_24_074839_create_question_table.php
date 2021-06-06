<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('question');
            $table->string('true_answer');
            $table->string('false_answer1');
            $table->string('false_answer2');
            $table->string('false_answer3');
            $table->string('points');
            $table->enum('level', ['easy','medium', 'hard'])->default('easy');
            $table->enum('status', ['active','passive'])->default('passive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question');
    }
}
