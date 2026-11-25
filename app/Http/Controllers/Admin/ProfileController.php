<?php

namespace App\Http\Controllers\Admin;

use App\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(private readonly HtmlSanitizer $sanitizer) {}

    /**
     * Show the portfolio profile editor.
     */
    public function edit(): Response
    {
        return Inertia::render('Admin/Profile/Edit', [
            'profile' => Profile::current(),
        ]);
    }

    /**
     * Update the portfolio profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:180'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_intro' => ['nullable', 'string', 'max:500'],
            'now_building' => ['nullable', 'string', 'max:500'],
            'now_learning' => ['nullable', 'string', 'max:500'],
            'now_cooking' => ['nullable', 'string', 'max:500'],
            'bio' => ['required', 'string'],
            'working_style' => ['nullable', 'string'],
            'education' => ['nullable', 'string'],
            'outside_the_code_html' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'resume_url' => ['nullable', 'url', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'headshot_url' => ['nullable', 'string', 'max:255'],
            'about_image_url' => ['nullable', 'string', 'max:255'],
            'favicon_url' => ['nullable', 'string', 'max:255'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:80'],
        ]);

        $validated['outside_the_code_html'] = $this->sanitizer->clean($validated['outside_the_code_html'] ?? null);

        Profile::current()->fill($validated)->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Profile updated.']);

        return to_route('admin.profile.edit');
    }
}
