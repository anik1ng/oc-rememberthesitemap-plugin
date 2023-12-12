<?php namespace ANKIN\RememberTheSitemap\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;

return new class extends \October\Rain\Database\Updates\Migration
{
    const TABLE = 'anikin_rememberthesitemap_definitions';

    public function up(): void
    {
        Schema::create(self::TABLE, function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('theme')->nullable()->index();
            $table->mediumtext('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
