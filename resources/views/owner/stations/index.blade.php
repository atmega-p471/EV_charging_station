<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Мои зарядные станции (CRUD)
            </h2>
            <a href="{{ route('owner.stations.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700 transition">
                + Добавить станцию
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($stations->isEmpty())
                    <p class="text-gray-500">У вас пока нет добавленных станций.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Название</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Адрес</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Посты и кабели</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Координаты</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($stations as $station)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $station->title }}</td>
                                    <td class="px-4 py-4 text-gray-600 text-sm">{{ $station->address }}</td>
                                    <td class="px-4 py-4">
                                        @if($station->is_approved)
                                            <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">Одобрена</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold">На проверке</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <div class="space-y-2">
                                            @forelse($station->chargers as $charger)
                                                <div class="p-2.5 bg-gray-50 rounded-md border border-gray-200">
                                                    <div class="font-bold text-xs text-indigo-700 uppercase tracking-wide">
                                                        ⚡ {{ $charger->name }}
                                                        <span class="text-gray-400 font-normal lowercase">({{ $charger->connectors->count() }} разъем.)</span>
                                                    </div>
                                                    <div class="mt-1 flex flex-wrap gap-1.5">
                                                        @foreach($charger->connectors as $connector)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-white text-gray-700 border border-gray-200">
                                                                <strong>{{ $connector->type }}</strong>: {{ $connector->power_kw }} кВт ({{ $connector->price_per_kwh }} ₽)
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @empty
                                                <span class="text-gray-400 text-xs">Нет постов</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-500">{{ $station->latitude }}, {{ $station->longitude }}</td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <!-- Количество постов со значком -->
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold text-xs border border-indigo-200">
                                                Посты: {{ $station->chargers->count() }}
                                            </span>

                                            <a href="{{ route('owner.stations.edit', $station) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                                Редактировать
                                            </a>

                                            <form action="{{ route('owner.stations.destroy', $station) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту станцию со всеми постами?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
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