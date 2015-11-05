<?php namespace Bombozama\LinkCheck\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateBrokenLinksTable extends Migration
{

    public function up()
    {
        Schema::create('bombozama_linkcheck_broken_links', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('model');
            $table->integer('model_id')->nullable();
            $table->string('field')->nullable();
            $table->integer('status');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bombozama_linkcheck_broken_links');
    }

}
