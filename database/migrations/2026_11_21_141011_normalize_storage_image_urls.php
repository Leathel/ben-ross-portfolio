<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Make previously uploaded image URLs relative to the current domain.
     */
    public function up(): void
    {
        $normalize = static function (?string $url): ?string {
            if ($url === null) {
                return null;
            }

            return preg_replace('#^https?://[^/]+/storage/#', '/storage/', $url) ?? $url;
        };

        foreach (DB::table('profiles')->get(['id', 'logo_url', 'headshot_url', 'about_image_url']) as $profile) {
            $updates = [];

            foreach (['logo_url', 'headshot_url', 'about_image_url'] as $column) {
                $normalized = $normalize($profile->{$column});

                if ($normalized !== $profile->{$column}) {
                    $updates[$column] = $normalized;
                }
            }

            if ($updates !== []) {
                DB::table('profiles')->where('id', $profile->id)->update($updates);
            }
        }

        foreach (DB::table('projects')->get(['id', 'image_url']) as $project) {
            $normalized = $normalize($project->image_url);

            if ($normalized !== $project->image_url) {
                DB::table('projects')->where('id', $project->id)->update(['image_url' => $normalized]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
