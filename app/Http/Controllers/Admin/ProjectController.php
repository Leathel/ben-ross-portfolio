<?php

namespace App\Http\Controllers\Admin;

use App\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(private readonly HtmlSanitizer $sanitizer) {}

    /**
     * Show the projects management list.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Projects/Index', [
            'projects' => Project::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the create project form.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Projects/Form', [
            'project' => null,
        ]);
    }

    /**
     * Store a new project.
     */
    public function store(Request $request): RedirectResponse
    {
        Project::query()->create($this->validatedData($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Project created.']);

        return to_route('admin.projects.index');
    }

    /**
     * Show the edit project form.
     */
    public function edit(Project $project): Response
    {
        return Inertia::render('Admin/Projects/Form', [
            'project' => [
                ...$project->toArray(),
                'technologies' => implode(', ', $project->technologies ?? []),
            ],
        ]);
    }

    /**
     * Update the given project.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validatedData($request, $project));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Project updated.']);

        return to_route('admin.projects.index');
    }

    /**
     * Delete the given project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Project deleted.']);

        return to_route('admin.projects.index');
    }

    /**
     * Validate and normalise the submitted project data.
     *
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Project $project = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'case_study_html' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:60'],
            'organization' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string', 'max:500'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'card_image_url' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['case_study_html'] = $this->sanitizer->clean($validated['case_study_html'] ?? null);

        $technologies = collect(explode(',', (string) ($validated['technologies'] ?? '')))
            ->map(fn (string $technology) => trim($technology))
            ->filter()
            ->values()
            ->all();

        $validated['technologies'] = $technologies;
        $validated['slug'] = $this->uniqueSlug($validated['title'], $project);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }

    /**
     * Build a unique slug from the project title.
     */
    private function uniqueSlug(string $title, ?Project $project = null): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $suffix = 2;

        while (Project::query()
            ->where('slug', $slug)
            ->when($project, fn ($query) => $query->whereKeyNot($project->getKey()))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
