<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Разъемы станции: {{ $station->title }}
            </h2>
            <a href="{{ route('owner.stations.index') }}" class="text-sm text-indigo-600 hover:underline">
                &larr; Назад к списку станций
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Форма добавления нового коннектора -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">+ Добавить еще один разъем к этой станции</h3>
                
                <form action="{{ route('owner.stations.connectors.store', $station) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип разъема</label>
                        <select name="type" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Type 2">Type 2 (Медленная, AC)</option>
                            <option value="CCS Combo 2">CCS Combo 2 (Быстрая, DC)</option>
                            <option value="CHAdeMO">CHAdeMO (Быстрая, DC)</option>
                            <option value="GB/T">GB/T (Китайский стандарт)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Мощность (кВт)</label>
                        <input type="number" name="power_kw" placeholder="например, 50" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тариф (₽/кВт·ч)</label>
                        <input type="number" step="0.01" name="price_per_kwh" placeholder="например, 18.00" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                            Добавить разъем
                        </button>
                    </div>
                </form>
            </div>

            <!-- Список существующих разъемов -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Установленные зарядные разъемы ({{ $connectors->count() }})</h3>

                @if($connectors->isEmpty())
                    <p class="text-gray-500">На этой станции пока нет ни одного разъема.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"># ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Тип кабеля / стандарта</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Мощность</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Цена за кВт·ч</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Действие</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($connectors as $connector)
                                <tr>
                                    <td class="px-4 py-2 font-mono text-sm">#{{ $connector->id }}</td>
                                    <td class="px-4 py-2 font-medium">{{ $connector->type }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $connector->power_kw }} кВт</td>
                                    <td class="px-4 py-2 text-sm font-bold text-gray-700">{{ $connector->price_per_kwh }} ₽</td>
                                    <td class="px-4 py-2">
                                        <form action="{{ route('owner.stations.connectors.destroy', [$station, $connector]) }}" method="POST" onsubmit="return confirm('Удалить этот разъем?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">
                                                Удалить
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>