<?php

namespace App\Http\Controllers\Admin;

use App\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExperienceController extends Controller
{
    public function __construct(private readonly HtmlSanitizer $sanitizer) {}

    /**
     * Show the work experience management list.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Experience/Index', [
            'experiences' => Experience::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the create experience form.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Experience/Form', [
            'experience' => null,
        ]);
    }

    /**
     * Store a new experience entry.
     */
    public function store(Request $request): RedirectResponse
    {
        Experience::query()->create($this->validatedData($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Experience added.']);

        return to_route('admin.experience.index');
    }

    /**
     * Show the edit experience form.
     */
    public function edit(Experience $experience): Response
    {
        return Inertia::render('Admin/Experience/Form', [
            'experience' => [
                ...$experience->toArray(),
                'start_date' => $experience->start_date?->format('Y-m-d'),
                'end_date' => $experience->end_date?->format('Y-m-d'),
                'skill_tags' => implode(', ', $experience->skill_tags ?? []),
            ],
        ]);
    }

    /**
     * Update the given experience entry.
     */
    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->validatedData($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Experience updated.']);

        return to_route('admin.experience.index');
    }

    /**
     * Delete the given experience entry.
     */
    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Experience deleted.']);

        return to_route('admin.experience.index');
    }

    /**
     * Validate and normalise the submitted experience data.
     *
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $rawTags = $request->input('skill_tags', []);
        $tags = is_array($rawTags) ? $rawTags : explode(',', (string) $rawTags);

        $request->merge([
            'skill_tags' => collect($tags)
                ->map(fn (mixed $tag): string => trim(strip_tags((string) $tag)))
                ->filter()
                ->unique(fn (string $tag): string => strtolower($tag))
                ->values()
                ->all(),
        ]);

        $validated = $request->validate([
            'company' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:60'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'is_current' => ['boolean'],
            'description' => ['required', 'string'],
            'skill_tags' => ['nullable', 'array', 'max:12'],
            'skill_tags.*' => ['string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['description'] = $this->sanitizer->clean($validated['description']) ?? '';
        $validated['skill_tags'] = $validated['skill_tags'] ?? [];
        $validated['is_current'] = $request->boolean('is_current');
        $validated['end_date'] = $validated['is_current'] ? null : ($validated['end_date'] ?? null);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
