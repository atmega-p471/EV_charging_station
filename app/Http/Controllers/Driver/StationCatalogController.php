<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StationCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Station::with(['chargers.connectors'])
            ->where('status', 'approved');

        if ($request->filled('type')) {
            $query->whereHas('chargers.connectors', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('min_power')) {
            $query->whereHas('chargers.connectors', function ($q) use ($request) {
                $q->where('power_kw', '>=', (int)$request->min_power);
            });
        }

        $stations = $query->latest()->get();

        return view('driver.stations.index', compact('stations'));
    }

    public function show(Station $station): View
    {
        if ($station->status !== 'approved') {
            abort(404, 'Станция не найдена или еще не прошла модерацию.');
        }

        $station->load(['chargers.connectors']);

        return view('driver.stations.show', compact('station'));
    }
}