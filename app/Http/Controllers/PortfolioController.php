<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    /**
     * Show the portfolio landing page.
     */
    public function home(): Response
    {
        return Inertia::render('Portfolio/Home', [
            'profile' => Profile::current(),
            'featuredProject' => $this->publishedCaseStudy(),
            'skills' => Skill::query()->ordered()->limit(8)->get(),
            'experience' => Experience::query()->ordered()->limit(3)->get()->map(
                fn (Experience $experience): array => $experience->only([
                    'id',
                    'company',
                    'role',
                    'location',
                    'start_date',
                    'end_date',
                    'is_current',
                    'sort_order',
                ]),
            ),
        ]);
    }

    /**
     * Show the about page.
     */
    public function about(): Response
    {
        return Inertia::render('Portfolio/About', [
            'profile' => Profile::current(),
            'skills' => Skill::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the XRecipes case study.
     */
    public function project(): Response
    {
        $project = $this->publishedCaseStudy();

        if ($project === null) {
            abort(404);
        }

        return Inertia::render('Portfolio/Projects/Show', [
            'profile' => Profile::current(),
            'project' => $project,
        ]);
    }

    /**
     * Show the full work history.
     */
    public function experience(): Response
    {
        return Inertia::render('Portfolio/Experience/Index', [
            'profile' => Profile::current(),
            'experience' => Experience::query()->ordered()->get(),
        ]);
    }

    /**
     * Show the contact page.
     */
    public function contact(Request $request): Response
    {
        return Inertia::render('Portfolio/Contact', [
            'profile' => Profile::current(),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Store a contact message.
     */
    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Thanks for getting in touch. I will be in touch soon.']);

        return to_route('contact')->with('status', 'Your message has been sent.');
    }

    /**
     * Get the published case study, independently of its editable slug.
     */
    private function publishedCaseStudy(): ?Project
    {
        return Project::query()
            ->published()
            ->featured()
            ->ordered()
            ->first()
            ?? Project::query()
                ->published()
                ->ordered()
                ->first();
    }
}
