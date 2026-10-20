<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        $pendingStations = Station::with(['user', 'chargers.connectors'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedStations = Station::with(['user', 'chargers.connectors'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedStations = Station::with(['user', 'chargers.connectors'])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.moderation.index', compact('pendingStations', 'approvedStations', 'rejectedStations'));
    }

    public function approve(Station $station): RedirectResponse
    {
        $station->update([
            'status' => 'approved',
            'is_approved' => true,
        ]);

        return redirect()->route('admin.moderation.index')
            ->with('success', "Станция «{$station->title}» успешно одобрена и опубликована!");
    }

    public function reject(Station $station): RedirectResponse
    {
        $station->update([
            'status' => 'rejected',
            'is_approved' => false,
        ]);

        return redirect()->route('admin.moderation.index')
            ->with('success', "Заявка по станции «{$station->title}» отклонена.");
    }
}