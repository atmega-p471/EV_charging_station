<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Connector;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConnectorController extends Controller
{
    /**
     * Показывает список разъемов конкретной станции и форму добавления
     */
    public function index(Station $station): View
    {
        // Проверка: станция должна принадлежать текущему владельцу
        if ($station->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для управления этой станцией.');
        }

        $connectors = $station->connectors;

        return view('owner.connectors.index', compact('station', 'connectors'));
    }

    /**
     * Добавление нового разъема к станции
     */
    public function store(Request $request, Station $station): RedirectResponse
    {
        if ($station->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'string'],
            'power_kw' => ['required', 'integer', 'min:1'],
            'price_per_kwh' => ['required', 'numeric', 'min:0'],
        ]);

        $station->connectors()->create($validated);

        return back()->with('success', 'Новый разъем успешно добавлен к станции!');
    }

    /**
     * Удаление разъема
     */
    public function destroy(Station $station, Connector $connector): RedirectResponse
    {
        if ($station->user_id !== auth()->id() || $connector->station_id !== $station->id) {
            abort(403);
        }

        $connector->delete();

        return back()->with('success', 'Разъем удален.');
    }
}