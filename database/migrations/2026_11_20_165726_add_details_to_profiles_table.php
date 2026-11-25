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
        Schema::table('profiles', function (Blueprint $table): void {
            $table->string('tagline')->nullable()->after('headline');
            $table->text('working_style')->nullable()->after('bio');
            $table->text('education')->nullable()->after('working_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn(['tagline', 'working_style', 'education']);
        });
    }
};
