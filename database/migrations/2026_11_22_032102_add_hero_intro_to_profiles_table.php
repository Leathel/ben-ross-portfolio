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
            $table->text('hero_intro')->nullable()->after('tagline');
        });

        DB::table('profiles')
            ->whereNull('hero_intro')
            ->update([
                'hero_intro' => 'Senior Full Stack Developer. I take complex problems from discovery through to implementation, with a focus on backend systems, integrations and practical business impact.',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn('hero_intro');
        });
    }
};
