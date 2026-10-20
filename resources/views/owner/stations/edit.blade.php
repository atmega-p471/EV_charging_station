<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Редактирование станции #{{ $station->id }}: {{$station->title }}
            </h2>
            <a href="{{ route('owner.stations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12 pb-32">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('owner.stations.update', $station) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">1. Основные параметры станции</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Название станции</label>
                        <input type="text" name="title" value="{{ old('title', $station->title) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Адрес</label>
                        <input type="text" name="address" value="{{ old('address', $station->address) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Широта (Latitude)</label>
                            <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $station->latitude) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Долгота (Longitude)</label>
                            <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $station->longitude) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800">2. Зарядные устройства (посты) и типы разъемов</h3>
                        <button type="button" id="add-charger-btn" style="background-color: #2563eb; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                            + Добавить еще пост
                        </button>
                    </div>

                    <!-- Контейнер для постов -->
                    <div id="chargers-container" class="space-y-6 mb-8">
                        @foreach($station->chargers as $cIdx =>$charger)
                            <div class="charger-card border-2 border-indigo-200 p-5 rounded-lg bg-gray-50" data-charger-idx="{{ $cIdx }}">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="w-1/2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Имя / Номер поста</label>
                                        <input type="text" name="chargers[{{ $cIdx }}][name]" value="{{ old("chargers.$cIdx.name", $charger->name) }}" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    </div>
                                    <button type="button" class="remove-charger-btn text-red-600 hover:text-red-900 text-sm font-semibold">
                                        Удалить этот пост
                                    </button>
                                </div>

                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold uppercase text-gray-500">Коннекторы этого поста:</span>
                                    <button type="button" class="add-connector-btn" style="background-color: #16a34a; color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; border: none; cursor: pointer;">
                                        + Добавить кабель
                                    </button>
                                </div>

                                <div class="connectors-list space-y-3">
                                    @foreach($charger->connectors as $kIdx =>$connector)
                                        <div class="connector-row border border-gray-200 p-3 rounded-md bg-white grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Тип разъема</label>
                                                <select name="chargers[{{ $cIdx }}][connectors][{{$kIdx }}][type]" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                    @foreach(['CCS Combo 2', 'Type 2', 'CHAdeMO', 'GB/T'] as $type)
                                                        <option value="{{ $type }}" {{ (old("chargers.$cIdx.connectors.$kIdx.type", $connector->type) ==$type) ? 'selected' : '' }}>
                                                            {{ $type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Мощность (кВт)</label>
                                                <input type="number" name="chargers[{{ $cIdx }}][connectors][{{ $kIdx }}][power_kw]" value="{{ old("chargers.$cIdx.connectors.$kIdx.power_kw", $connector->power_kw) }}" required min="1" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Тариф (₽/кВт·ч)</label>
                                                <input type="number" step="0.01" name="chargers[{{ $cIdx }}][connectors][{{ $kIdx }}][price_per_kwh]" value="{{ old("chargers.$cIdx.connectors.$kIdx.price_per_kwh", $connector->price_per_kwh) }}" required min="0" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <button type="button" class="remove-connector-btn w-full py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-red-600 hover:text-white transition text-xs">
                                                    Удалить кабель
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                        <button type="submit" style="background-color: #4338ca; color: #ffffff; width: 100%; padding: 14px 20px; border-radius: 8px; font-weight: 700; font-size: 16px; border: none; cursor: pointer; display: block;">
                            Сохранить все изменения (станция, посты и кабели)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let chargerCount = {{ $station->chargers->count() }};
            const chargersContainer = document.getElementById('chargers-container');
            const addChargerBtn = document.getElementById('add-charger-btn');

            if (addChargerBtn) {
                addChargerBtn.addEventListener('click', function () {
                    const cIdx = chargerCount;
                    const newCharger = document.createElement('div');
                    newCharger.className = 'charger-card border-2 border-indigo-200 p-5 rounded-lg bg-gray-50';
                    newCharger.dataset.chargerIdx = cIdx;
                    newCharger.innerHTML = `
                        <div class="flex justify-between items-center mb-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Имя / Номер поста</label>
                                <input type="text" name="chargers[${cIdx}][name]" value="Пост ${cIdx + 1}" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <button type="button" class="remove-charger-btn text-red-600 hover:text-red-900 text-sm font-semibold">
                                Удалить этот пост
                            </button>
                        </div>

                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold uppercase text-gray-500">Коннекторы этого поста:</span>
                            <button type="button" class="add-connector-btn" style="background-color: #16a34a; color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; border: none; cursor: pointer;">
                                + Добавить кабель
                            </button>
                        </div>

                        <div class="connectors-list space-y-3">
                            <div class="connector-row border border-gray-200 p-3 rounded-md bg-white grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Тип разъема</label>
                                    <select name="chargers[${cIdx}][connectors][0][type]" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="Type 2">Type 2</option>
                                        <option value="CCS Combo 2">CCS Combo 2</option>
                                        <option value="CHAdeMO">CHAdeMO</option>
                                        <option value="GB/T">GB/T</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Мощность (кВт)</label>
                                    <input type="number" name="chargers[${cIdx}][connectors][0][power_kw]" value="22" required min="1" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Тариф (₽/кВт·ч)</label>
                                    <input type="number" step="0.01" name="chargers[${cIdx}][connectors][0][price_per_kwh]" value="15.00" required min="0" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                </div>
                                <div>
                                    <button type="button" class="remove-connector-btn w-full py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-red-600 hover:text-white transition text-xs">
                                        Удалить кабель
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    chargersContainer.appendChild(newCharger);
                    chargerCount++;
                });
            }

            chargersContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-connector-btn')) {
                    const chargerCard = e.target.closest('.charger-card');
                    const cIdx = chargerCard.dataset.chargerIdx;
                    const list = chargerCard.querySelector('.connectors-list');
                    const connIdx = list.querySelectorAll('.connector-row').length;

                    const newConn = document.createElement('div');
                    newConn.className = 'connector-row border border-gray-200 p-3 rounded-md bg-white grid grid-cols-1 md:grid-cols-4 gap-3 items-end';
                    newConn.innerHTML = `
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Тип разъема</label>
                            <select name="chargers[${cIdx}][connectors][${connIdx}][type]" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="CCS Combo 2">CCS Combo 2</option>
                                <option value="Type 2">Type 2</option>
                                <option value="CHAdeMO">CHAdeMO</option>
                                <option value="GB/T">GB/T</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Мощность (кВт)</label>
                            <input type="number" name="chargers[${cIdx}][connectors][${connIdx}][power_kw]" value="50" required min="1" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Тариф (₽/кВт·ч)</label>
                            <input type="number" step="0.01" name="chargers[${cIdx}][connectors][${connIdx}][price_per_kwh]" value="18.00" required min="0" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <div>
                            <button type="button" class="remove-connector-btn w-full py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-red-600 hover:text-white transition text-xs">
                                Удалить кабель
                            </button>
                        </div>
                    `;
                    list.appendChild(newConn);
                }

                if (e.target.classList.contains('remove-connector-btn')) {
                    const list = e.target.closest('.connectors-list');
                    if (list.querySelectorAll('.connector-row').length > 1) {
                        e.target.closest('.connector-row').remove();
                    } else {
                        alert('У поста должен оставаться хотя бы один разъем!');
                    }
                }

                if (e.target.classList.contains('remove-charger-btn')) {
                    if (chargersContainer.querySelectorAll('.charger-card').length > 1) {
                        e.target.closest('.charger-card').remove();
                    } else {
                        alert('У станции должен быть хотя бы один пост!');
                    }
                }
            });
        });
    </script>
</x-app-layout>