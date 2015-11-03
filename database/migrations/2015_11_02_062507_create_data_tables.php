<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 数据表
 * @author LaijimLi
 *
 */
class CreateDataTables extends Migration
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
        	$table->string('website');
        	$table->string('title');
        	$table->string('desc');
        	$table->string('remark1');//留待差异化扩展
        	$table->string('remark2');
        	$table->string('remark3');
        	$table->string('remark4');
        	$table->text('url');
        	$table->mediumText('content');
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
        Schema::drop('datas');
    }
}
