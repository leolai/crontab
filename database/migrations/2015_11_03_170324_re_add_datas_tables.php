<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReAddDatasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas', function(Blueprint $table){
        	$table->increments('id');
        	$table->string('website')->nullable();
        	$table->string('title')->nullable();
        	$table->string('desc')->nullable();
        	$table->string('remark1')->nullable();//留待差异化扩展
        	$table->string('remark2')->nullable();
        	$table->string('remark3')->nullable();
        	$table->string('remark4')->nullable();
        	$table->text('url')->nullable();
        	$table->mediumText('content')->nullable();
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
        //
    }
}
