<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the portfolio management overview.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'profile' => Profile::current(),
            'stats' => [
                'projects' => Project::query()->count(),
                'skills' => Skill::query()->count(),
                'experience' => Experience::query()->count(),
                'unreadMessages' => ContactMessage::query()->unread()->count(),
            ],
            'recentMessages' => ContactMessage::query()->latest()->limit(5)->get(),
        ]);
    }
}
