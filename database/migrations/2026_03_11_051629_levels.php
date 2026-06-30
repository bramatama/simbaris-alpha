<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('levels',function(Blueprint $table){
            $table->id('level_id');
            $table->string('level_name');
            $table->timestamps();
        });

        Schema::table('official_teams',function(Blueprint $table){
            $table->dropColumn('level');
            $table->foreignId('level_id')->index()->constrained('levels','level_id');        
        });

        Schema::create('event_levels',function(Blueprint $table){
            $table->id('event_level_id');
            $table->foreignId('event_id')->index()->constrained('events','event_id')->onDelete('cascade');
            $table->foreignId('level_id')->index()->constrained('levels','level_id');
            $table->integer('registration_fees')->default('0');
            $table->integer('quota')->nullable('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_levels');
        Schema::table('official_teams',function(Blueprint $table){
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
            $table->string('level')->nullable();
        });
        Schema::dropIfExists('levels');
    }
};
