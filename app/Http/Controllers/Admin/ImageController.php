<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ImageController extends Controller
{
    /**
     * Upload an image for a portfolio field and return its public URL.
     */
    public function store(Request $request): RedirectResponse
    {
        $allowedFields = ['logo_url', 'headshot_url', 'about_image_url', 'favicon_url', 'image_url', 'card_image_url', 'case_study_image', 'description_image', 'outside_the_code_image'];

        $validated = $request->validate([
            'field' => ['required', 'string', Rule::in($allowedFields)],
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $path = str_replace('\\', '/', (string) $request->file('image')->store('images', 'public'));
        $url = '/storage/'.$path;

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Image uploaded.']);

        return back()->with([
            'uploaded_field' => $validated['field'],
            'uploaded_url' => $url,
        ]);
    }
}
