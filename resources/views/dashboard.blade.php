<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Личный кабинет — {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">
                    Система управления ЭЗС
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Текущая роль:
                    <strong class="uppercase text-indigo-600">{{ Auth::user()->role }}</strong>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-5 bg-gray-50 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-gray-900 text-base mb-1">Каталог зарядных станций</h4>
                            <p class="text-xs text-gray-500 mb-4">Поиск свободных коннекторов по типу разъема и мощности.</p>
                        </div>
                        <a href="{{ route('driver.stations.index') }}" style="display: block; text-align: center; background-color: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">
                            Перейти в каталог
                        </a>
                    </div>

                    @if(Auth::user()->role === 'owner')
                        <div class="border border-indigo-200 rounded-lg p-5 bg-indigo-50/40 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">Мои станции (CRUD)</h4>
                                <p class="text-xs text-gray-500 mb-4">Просмотр статуса модерации, редактирование постов и разъемов.</p>
                            </div>
                            <a href="{{ route('owner.stations.index') }}" style="display: block; text-align: center; background-color: #4f46e5; color: white; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">
                                Управление станциями
                            </a>
                        </div>

                        <div class="border border-green-200 rounded-lg p-5 bg-green-50/40 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">Добавить новую ЭЗС</h4>
                                <p class="text-xs text-gray-500 mb-4">Регистрация новой локации, зарядных терминалов и тарифов.</p>
                            </div>
                            <a href="{{ route('owner.stations.create') }}" style="display: block; text-align: center; background-color: #16a34a; color: white; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">
                                Создать станцию
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <div class="border border-red-200 rounded-lg p-5 bg-red-50/40 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">Панель модерации</h4>
                                <p class="text-xs text-gray-500 mb-4">Проверка новых заявок от владельцев ЭЗС, одобрение и отклонение.</p>
                                </div>
                            <a href="{{ route('admin.moderation.index') }}" style="display: block; text-align: center; background-color: #dc2626; color: white; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; text-decoration: none;">
                                Открыть модерацию
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>