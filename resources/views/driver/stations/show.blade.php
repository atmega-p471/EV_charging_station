<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $station->title }}
            </h2>
            <a href="{{ route('driver.stations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Вернуться в каталог
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $station->title }}</h3>
                <p class="text-gray-600 text-sm mb-3">{{ $station->address }}</p>
                <div class="text-xs text-gray-400">
                    Геолокация: {{ $station->latitude }}, {{ $station->longitude }}
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 text-base">Выберите зарядное место для бронирования:</h4>

                <div class="space-y-6">
                    @foreach($station->chargers as $charger)
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="font-bold text-indigo-800 mb-3 text-sm uppercase tracking-wide">
                                🔌 {{ $charger->name }}
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($charger->connectors as $connector)
                                    <div class="p-4 bg-white rounded border border-gray-200 shadow-sm flex flex-col justify-between">
                                        <div class="mb-3">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-bold text-gray-900">{{ $connector->type }}</span>
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded font-semibold">Свободен</span>
                                            </div>
                                            <div class="text-sm text-gray-600">Мощность: <strong>{{ $connector->power_kw }} кВт</strong></div>
                                            <div class="text-sm text-gray-600">Тариф: <strong>{{ $connector->price_per_kwh }} ₽ / кВт·ч</strong></div>
                                        </div>

                                        <button type="button" style="background-color: #16a34a; color: white; padding: 8px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; border: none; cursor: pointer; width: 100%;">
                                            Забронировать время
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>