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
            $table->string('logo_url')->nullable()->after('resume_url');
            $table->string('headshot_url')->nullable()->after('logo_url');
            $table->string('about_image_url')->nullable()->after('headshot_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn(['logo_url', 'headshot_url', 'about_image_url']);
        });
    }
};
