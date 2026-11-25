<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SkillController extends Controller
{
    /**
     * Show the skills management list.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Skills/Index', [
            'skills' => Skill::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the create skill form.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Skills/Form', [
            'skill' => null,
        ]);
    }

    /**
     * Store a new skill.
     */
    public function store(Request $request): RedirectResponse
    {
        Skill::query()->create($this->validatedData($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Skill created.']);

        return to_route('admin.skills.index');
    }

    /**
     * Show the edit skill form.
     */
    public function edit(Skill $skill): Response
    {
        return Inertia::render('Admin/Skills/Form', [
            'skill' => $skill,
        ]);
    }

    /**
     * Update the given skill.
     */
    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $skill->update($this->validatedData($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Skill updated.']);

        return to_route('admin.skills.index');
    }

    /**
     * Delete the given skill.
     */
    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Skill deleted.']);

        return to_route('admin.skills.index');
    }

    /**
     * Validate the submitted skill data.
     *
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:60'],
            'proficiency' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
