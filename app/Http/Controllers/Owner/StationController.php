<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
{
    /**
     * READ: Список станций владельца со всеми постами и кабелями
     */
    public function index(): View
    {
        $stations = auth()->user()
            ->stations()
            ->with(['chargers.connectors'])
            ->latest()
            ->get();

        return view('owner.stations.index', compact('stations'));
    }

    /**
     * CREATE: Форма добавления станции
     */
    public function create(): View
    {
        return view('owner.stations.create');
    }

    /**
     * CREATE: Сохранение станции, постов и разъемов в БД
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'chargers' => ['required', 'array', 'min:1'],
            'chargers.*.name' => ['required', 'string', 'max:100'],
            'chargers.*.connectors' => ['required', 'array', 'min:1'],
            'chargers.*.connectors.*.type' => ['required', 'string'],
            'chargers.*.connectors.*.power_kw' => ['required', 'integer', 'min:1'],
            'chargers.*.connectors.*.price_per_kwh' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Создаем локацию/станцию
            $station = auth()->user()->stations()->create([
                'title' => $validated['title'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'is_approved' => false,
            ]);

            // 2. Создаем зарядные посты и их кабели
            foreach ($validated['chargers'] as $chargerData) {
                $charger = $station->chargers()->create([
                    'name' => $chargerData['name'],
                ]);

                foreach ($chargerData['connectors'] as $connectorData) {
                    $charger->connectors()->create($connectorData);
                }
            }
        });

        return redirect()->route('owner.stations.index')->with('success', 'Станция со всеми постами и разъемами успешно создана!');
    }

    /**
     * UPDATE: Форма редактирования станции
     */
    public function edit(Station $station): View
    {
        if ($station->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этой станции.');
        }

        // Загружаем посты и кабели для вывода в форму
        $station->load('chargers.connectors');

        return view('owner.stations.edit', compact('station'));
    }

    /**
     * UPDATE: Сохранение обновлений (станция, посты, коннекторы)
     */
    public function update(Request $request, Station $station): RedirectResponse
    {
        if ($station->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'chargers' => ['required', 'array', 'min:1'],
            'chargers.*.name' => ['required', 'string', 'max:100'],
            'chargers.*.connectors' => ['required', 'array', 'min:1'],
            'chargers.*.connectors.*.type' => ['required', 'string'],
            'chargers.*.connectors.*.power_kw' => ['required', 'integer', 'min:1'],
            'chargers.*.connectors.*.price_per_kwh' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($station, $validated) {
            // 1. Обновляем основные атрибуты станции
            $station->update([
                'title' => $validated['title'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'is_approved' => false, // После изменений отправляем на повторную модерацию
            ]);

            // 2. Синхронизируем посты и кабели (удаляем старые и записываем актуальный набор)
            $station->chargers()->delete();

            foreach ($validated['chargers'] as $chargerData) {
                $charger = $station->chargers()->create([
                    'name' => $chargerData['name'],
                ]);

                foreach ($chargerData['connectors'] as $connectorData) {
                    $charger->connectors()->create($connectorData);
                }
            }
        });

        return redirect()->route('owner.stations.index')->with('success', 'Станция, зарядные устройства и кабели успешно обновлены!');
    }

    /**
     * DELETE: Удаление станции
     */
    public function destroy(Station $station): RedirectResponse
    {
        if ($station->user_id !== auth()->id()) {
            abort(403);
        }

        // Каскадное удаление (onDelete cascade в миграциях) очистит посты и разъемы
        $station->delete();

        return redirect()->route('owner.stations.index')->with('success', 'Станция удалена!');
    }
}