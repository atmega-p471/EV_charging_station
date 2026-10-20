<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Панель модерации станций ЭЗС
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-md font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 1. Ожидают проверки -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    Ожидают модерации ({{ $pendingStations->count() }})
                </h3>

                @if($pendingStations->isEmpty())
                    <p class="text-gray-500 text-sm">Новых заявок на проверку нет.</p>
                @else
                    <div class="space-y-4">
                        @foreach($pendingStations as $station)
                            <div class="border rounded-lg p-5 bg-yellow-50/50 border-yellow-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-gray-900 text-base">{{ $station->title }}</h4>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-semibold">На проверке</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $station->address }}</p>
                                    <p class="text-xs text-gray-500">Владелец: {{ $station->user->name ?? '—' }} ({{ $station->user->email ?? '' }})</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.moderation.approve', $station) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 700; border: none; cursor: pointer;">
                                            Одобрить
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.moderation.reject', $station) }}" method="POST" onsubmit="return confirm('Отклонить заявку?');">
                                        @csrf
                                        <button type="submit" style="background-color: #dc2626; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 700; border: none; cursor: pointer;">
                                            Отклонить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 2. Одобренные станции -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    Активные и одобренные станции ({{ $approvedStations->count() }})
                </h3>

                @if($approvedStations->isEmpty())
                    <p class="text-gray-500 text-sm">Пока нет одобренных станций.</p>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($approvedStations as $station)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $station->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $station->address }} • Владелец: {{ $station->user->name ?? '—' }}</div>
                                </div>
                                <form action="{{ route('admin.moderation.reject', $station) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">
                                        Отозвать публикацию
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. Отклоненные станции -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    Отклоненные заявки ({{ $rejectedStations->count() }})
                </h3>

                @if($rejectedStations->isEmpty())
                    <p class="text-gray-500 text-sm">Нет отклоненных заявок.</p>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($rejectedStations as $station)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $station->title }}</div>
                                    <div class="text-xs text-red-500">{{ $station->address }} • Отклонено администратором</div>
                                </div>
                                <form action="{{ route('admin.moderation.approve', $station) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">
                                        Вернуть и одобрить
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>