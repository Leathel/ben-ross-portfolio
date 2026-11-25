<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'home'])->name('home');

Route::get('about', [PortfolioController::class, 'about'])->name('about');
Route::get('experience', [PortfolioController::class, 'experience'])->name('experience');
Route::get('projects', [PortfolioController::class, 'project'])->name('projects.show');
Route::get('contact', [PortfolioController::class, 'contact'])->name('contact');
Route::post('contact', [PortfolioController::class, 'storeContact'])->name('contact.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('admin/projects', AdminProjectController::class)->names('admin.projects');
    Route::resource('admin/skills', AdminSkillController::class)->names('admin.skills');
    Route::resource('admin/experience', ExperienceController::class)->names('admin.experience');

    Route::post('admin/images', [ImageController::class, 'store'])->name('admin.images.store');

    Route::get('admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('admin/messages', [ContactMessageController::class, 'index'])->name('admin.messages.index');
    Route::get('admin/messages/{message}', [ContactMessageController::class, 'show'])->name('admin.messages.show');
    Route::patch('admin/messages/{message}', [ContactMessageController::class, 'update'])->name('admin.messages.update');
    Route::delete('admin/messages/{message}', [ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');
});

require __DIR__.'/settings.php';
