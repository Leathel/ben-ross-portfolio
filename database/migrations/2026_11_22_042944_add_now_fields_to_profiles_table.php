<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->text('now_building')->nullable()->after('hero_intro');
            $table->text('now_learning')->nullable()->after('now_building');
            $table->text('now_cooking')->nullable()->after('now_learning');
        });

        DB::table('profiles')
            ->whereNull('now_building')
            ->update(['now_building' => 'XRecipes, with the goal of getting real creators posting']);

        DB::table('profiles')
            ->whereNull('now_learning')
            ->update(['now_learning' => 'automated testing in PHP with Pest']);

        DB::table('profiles')
            ->whereNull('now_cooking')
            ->update(['now_cooking' => '[TODO: Add your current recipe, e.g. the perfect slow-cooker chili]']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn(['now_building', 'now_learning', 'now_cooking']);
        });
    }
};
