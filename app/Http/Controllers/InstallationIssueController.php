<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\InstallationIssue;
use Illuminate\Http\Request;

class InstallationIssueController extends Controller
{
    public function store(Request $request, Installation $installation)
    {
        $this->authorize('update', $installation);

        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
        ]);

        $issue = $installation->issues()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Log automático
        $installation->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'Nueva incidencia',
            'description' => $issue->title,
        ]);

        return redirect()->back()->with('success_issue', true);
    }

    public function close(InstallationIssue $issue)
    {
        $this->authorize('update', $issue->installation);

        $issue->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $issue->installation->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'Incidencia cerrada',
            'description' => $issue->title,
        ]);

        return redirect()->back();
    }
}
