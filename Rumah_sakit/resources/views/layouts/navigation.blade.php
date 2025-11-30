{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="bg-primary rounded p-2 me-2" style="background: var(--gradient-primary);">
                            <i class="fas fa-hospital text-white fs-4"></i>
                        </div>
                        <span class="fw-bold fs-4" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            InD
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex align-items-center">
                    @if(!Auth::check() || Auth::user()->role !== 'admin')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif

                    <!-- Role-based Navigation -->
                    @auth
                        @if(Auth::user()->role === 'pasien')
                            <x-nav-link :href="route('pasien.information')" :active="request()->routeIs('pasien.information')" class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Layanan
                            </x-nav-link>
                            <x-nav-link :href="route('pasien.schedules.index')" :active="request()->routeIs('pasien.schedules.index')" class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Jadwal Dokter
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->role === 'dokter')
                            <x-nav-link :href="route('dokter.appointments.index')" :active="request()->routeIs('dokter.appointments.index')" class="d-flex align-items-center">
                                <i class="fas fa-calendar-check me-2"></i>
                                Janji Temu
                            </x-nav-link>
                            <x-nav-link :href="route('dokter.schedules.index')" :active="request()->routeIs('dokter.schedules.index')" class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Jadwal Saya
                            </x-nav-link>
                            <x-nav-link :href="route('dokter.medical-records.index')" :active="request()->routeIs('dokter.medical-records.index')" class="d-flex align-items-center">
                                <i class="fas fa-file-medical me-2"></i>
                                Rekam Medis
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <!-- Dashboard Admin -->
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="d-flex align-items-center">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </x-nav-link>

                            <!-- Manajemen Janji Temu -->
                            <x-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.index')" class="d-flex align-items-center">
                                <i class="fas fa-calendar-check me-2"></i>
                                Manajemen Janji
                            </x-nav-link>
                            
                            <!-- Data Pasien -->
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="d-flex align-items-center">
                                <i class="fas fa-users me-2"></i>
                                Data User
                            </x-nav-link>

                            <!-- Data Poli -->
                            <x-nav-link :href="route('admin.polis.index')" :active="request()->routeIs('admin.polis.index')" class="d-flex align-items-center">
                                <i class="fas fa-clinic-medical me-2"></i>
                                Data Poli
                            </x-nav-link>

                            <!-- Data Obat (jika diperlukan) -->
                            <x-nav-link :href="route('admin.medicines.index')" :active="request()->routeIs('admin.medicines.index')" class="d-flex align-items-center">
                                <i class="fas fa-pills me-2"></i>
                                Data Obat
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle me-2" style="width: 32px; height: 32px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <span class="fw-medium">{{ Auth::user()->name }}</span>
                                <small class="text-muted ms-2 text-uppercase">({{ Auth::user()->role }})</small>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="d-flex align-items-center">
                            <i class="fas fa-user me-2"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="d-flex align-items-center text-danger"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(!Auth::check() || Auth::user()->role !== 'admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="d-flex align-items-center">
                <i class="fas fa-tachometer-alt me-2"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endif

            <!-- Role-based Mobile Navigation -->
            @auth
                @if(Auth::user()->role === 'pasien')
                    <x-responsive-nav-link :href="route('pasien.information')" :active="request()->routeIs('pasien.information')" class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Layanan
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('pasien.schedules.index')" :active="request()->routeIs('pasien.schedules.index')" class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Jadwal Dokter
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->role === 'dokter')
                    <x-responsive-nav-link :href="route('dokter.appointments.index')" :active="request()->routeIs('dokter.appointments.index')" class="d-flex align-items-center">
                        <i class="fas fa-calendar-check me-2"></i>
                        Janji Temu
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dokter.schedules.index')" :active="request()->routeIs('dokter.schedules.index')" class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Jadwal Saya
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dokter.medical-records.index')" :active="request()->routeIs('dokter.medical-records.index')" class="d-flex align-items-center">
                        <i class="fas fa-file-medical me-2"></i>
                        Rekam Medis
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->role === 'admin')
                    <!-- Dashboard Admin -->
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </x-responsive-nav-link>

                    <!-- Manajemen Janji Temu -->
                    <x-responsive-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.index')" class="d-flex align-items-center">
                        <i class="fas fa-calendar-check me-2"></i>
                        Manajemen Janji
                    </x-responsive-nav-link>
                    
                    <!-- Data Pasien -->
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="d-flex align-items-center">
                        <i class="fas fa-users me-2"></i>
                        Data User
                    </x-responsive-nav-link>

                    <!-- Data Poli -->
                    <x-responsive-nav-link :href="route('admin.polis.index')" :active="request()->routeIs('admin.polis.index')" class="d-flex align-items-center">
                        <i class="fas fa-clinic-medical me-2"></i>
                        Data Poli
                    </x-responsive-nav-link>

                    <!-- Data Obat (jika diperlukan) -->
                    <x-responsive-nav-link :href="route('admin.medicines.index')" :active="request()->routeIs('admin.medicines.index')" class="d-flex align-items-center">
                        <i class="fas fa-pills me-2"></i>
                        Data Obat
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="font-medium text-xs text-primary text-uppercase mt-1">{{ Auth::user()->role }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="d-flex align-items-center text-danger"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>