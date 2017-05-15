<?php namespace Bombozama\LinkCheck\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBrokenLinksTable extends Migration
{

    public function up()
    {
        Schema::table('bombozama_linkcheck_broken_links', function($table)
        {
            $table->text('context')->nullable();
            $table->text('url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('bombozama_linkcheck_broken_links', function($table)
        {
            $table->dropColumn('context');
            $table->string('url', 255)->nullable()->change();
        });
    }

}