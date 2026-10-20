<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Поиск и бронирование зарядных станций (ЭЗС)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('driver.stations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Тип разъема</label>
                        <select name="type" class="w-full border-gray-300 rounded-md text-sm">
                            <option value="">Все типы разъемов</option>
                            <option value="CCS Combo 2" {{ request('type') == 'CCS Combo 2' ? 'selected' : '' }}>CCS Combo 2 (DC)</option>
                            <option value="Type 2" {{ request('type') == 'Type 2' ? 'selected' : '' }}>Type 2 (AC)</option>
                            <option value="CHAdeMO" {{ request('type') == 'CHAdeMO' ? 'selected' : '' }}>CHAdeMO (DC)</option>
                            <option value="GB/T" {{ request('type') == 'GB/T' ? 'selected' : '' }}>GB/T</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Мин. мощность (кВт)</label>
                        <input type="number" name="min_power" value="{{ request('min_power') }}" placeholder="Например: 50" class="w-full border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <button type="submit" style="background-color: #2563eb; color: #fff; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; width: 100%;">
                            Применить фильтр
                        </button>
                    </div>

                    <div>
                        <a href="{{ route('driver.stations.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 py-2">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>

            @if($stations->isEmpty())
                <div class="bg-white p-8 rounded-lg text-center text-gray-500">
                    По вашему запросу не найдено подходящих активных станций.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stations as $station)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                            <div class="p-5 space-y-3">
                                <div>
                                    <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded mb-1">Онлайн</span>
                                    <h3 class="font-bold text-lg text-gray-900 leading-snug">{{ $station->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $station->address }}</p>
                                </div>

                                <div class="border-t pt-3">
                                    <div class="text-xs font-bold uppercase text-gray-400 mb-2">Доступные посты и кабели:</div>
                                    <div class="space-y-2">
                                        @foreach($station->chargers as $charger)
                                            <div class="p-2 bg-gray-50 rounded border text-xs">
                                                <div class="font-bold text-indigo-700">{{ $charger->name }}</div>
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    @foreach($charger->connectors as $connector)
                                                        <span class="px-1.5 py-0.5 bg-white border rounded text-[11px] text-gray-700">
                                                            {{ $connector->type }} • <strong>{{ $connector->power_kw }} кВт</strong> ({{ $connector->price_per_kwh }} ₽)
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 border-t">
                                <a href="{{ route('driver.stations.show', $station) }}" style="display: block; text-align: center; background-color: #4f46e5; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">
                                    Выбрать разъем и забронировать
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>