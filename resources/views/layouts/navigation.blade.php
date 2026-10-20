<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Логотип -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('driver.stations.index') }}" class="font-bold text-lg text-indigo-600">
                        EV Charging
                    </a>
                </div>

                <!-- Навигационные ссылки (Десктоп) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('driver.stations.index')" :active="request()->routeIs('driver.stations.*')">
                        Каталог ЭЗС
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Личный кабинет
                        </x-nav-link>

                        @if(Auth::user()->role === 'owner')
                            <x-nav-link :href="route('owner.stations.index')" :active="request()->routeIs('owner.stations.index') || request()->routeIs('owner.stations.edit')">
                                Мои станции
                            </x-nav-link>
                            <x-nav-link :href="route('owner.stations.create')" :active="request()->routeIs('owner.stations.create')">
                                Добавить станцию
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.moderation.index')" :active="request()->routeIs('admin.moderation.*')">
                                Модерация станций
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Правая часть: Информация о пользователе и кнопка Выхода -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full font-semibold
                                {{ Auth::user()->role === 'admin' ? 'bg-red-100 text-red-800' : (Auth::user()->role === 'owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800') }}">
                                {{ Auth::user()->role === 'admin' ? 'Админ' : (Auth::user()->role === 'owner' ? 'Владелец' : 'Водитель') }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 rounded-md text-xs font-semibold transition border border-gray-200">
                                Выйти
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600">Войти</a>
                        <a href="{{ route('register') }}" style="background-color: #4f46e5; color: white; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600;">Регистрация</a>
                    </div>
                @endauth
            </div>
            <!-- Кнопка мобильного меню -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('driver.stations.index')" :active="request()->routeIs('driver.stations.*')">
                Каталог ЭЗС
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Личный кабинет
                </x-responsive-nav-link>

                @if(Auth::user()->role === 'owner')
                    <x-responsive-nav-link :href="route('owner.stations.index')" :active="request()->routeIs('owner.stations.*')">
                        Мои станции
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.moderation.index')" :active="request()->routeIs('admin.moderation.*')">
                        Модерация станций
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            Выйти
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>