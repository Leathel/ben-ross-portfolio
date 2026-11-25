<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{
    /**
     * Show the contact message inbox.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Messages/Index', [
            'messages' => ContactMessage::query()->latest()->get(),
        ]);
    }

    /**
     * Show a contact message and mark it as read.
     */
    public function show(ContactMessage $message): Response
    {
        if ($message->read_at === null) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return Inertia::render('Admin/Messages/Show', [
            'message' => $message,
        ]);
    }

    /**
     * Toggle the read state of a contact message.
     */
    public function update(Request $request, ContactMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'read' => ['required', 'boolean'],
        ]);

        $message->update(['read_at' => $validated['read'] ? now() : null]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Message updated.']);

        return to_route('admin.messages.index');
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Message deleted.']);

        return to_route('admin.messages.index');
    }
}
