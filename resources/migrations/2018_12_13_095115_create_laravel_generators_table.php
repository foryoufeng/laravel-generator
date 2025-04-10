<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelGeneratorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('laravel_generator_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('模板组');
            $table->timestamps();
        });

        Schema::create('laravel_generators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('path')->comment('保存路径');
            $table->string('file_name')->comment('文件名');
            $table->char('is_checked', 1)->comment('是否选中 0 不选中 1 选中');
            $table->text('template')->coment('模板');
            $table->unsignedInteger('template_id');
            $table->foreign('template_id')->references('id')->on('laravel_generator_types');

            $table->timestamps();
        });

        Schema::create('laravel_generator_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_name')->comment('model name');
            $table->string('display_name')->comment('display name');
            $table->string('creator')->comment('creator');
            $table->json('configs')->comment('configs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('laravel_generators');
        Schema::dropIfExists('laravel_generator_types');
        Schema::dropIfExists('laravel_generator_types');
    }
}
