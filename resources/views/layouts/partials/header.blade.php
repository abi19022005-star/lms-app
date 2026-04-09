<!-- Header / Navbar Modern -->
<header class="fixed top-0 left-0 right-0 z-30 bg-white border-b border-gray-200 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section: Logo & Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button
                    @click="toggleSidebar()"
                    class="p-2 text-gray-500 rounded-lg md:hidden hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 group">
                    <div class="flex items-center justify-center w-9 h-9 bg-gradient-to-br from-blue-600 to-blue-500 rounded-xl shadow-md transition-all duration-300 group-hover:shadow-lg group-hover:scale-105">
                        {{-- <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg> --}}
                        {{-- <span class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            {{ setting('app_logo', config('app.logo', 'LMS')) }}
                        </span> --}}
                        @if(setting('app_logo'))
                            <img
                                src="{{ asset('storage/' . setting('app_logo')) }}"
                                alt="Logo"
                                class="h-10 w-auto object-contain rounded-xl shadow-md border border-gray-200 p-1 bg-white hover:scale-105 transition duration-300 ease-in-out"
                            >
                        @else
                            <span class="text-xl font-bold">LMS</span>
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            {{ setting('app_name', config('app.name', 'LMS')) }}
                        </span>
                        <span class="ml-1 text-xs font-medium text-blue-600">v2.0</span>
                    </div>
                </a>
            </div>

            <!-- Center Section: Search Bar (Optional) -->
            <div class="flex-1 max-w-md px-4 mx-auto hidden md:block"
                 x-data="{
                     open: false,
                     results: { courses: [], lessons: [] },
                     loading: false,
                     async handleSearch(query) {
                         if (query.trim().length < 2) {
                             this.results = { courses: [], lessons: [] };
                             return;
                         }
                         this.loading = true;
                         try {
                             const response = await fetch(`{{ route('search') }}?q=${encodeURIComponent(query)}`);
                             const data = await response.json();
                             this.results = data;
                             this.open = data.courses.length > 0 || data.lessons.length > 0;
                         } catch (error) {
                             console.error('Search error:', error);
                             this.results = { courses: [], lessons: [] };
                         } finally {
                             this.loading = false;
                         }
                     }
                 }"
                 @keydown.debounce-300="handleSearch($el.querySelector('#searchInput').value)">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Cari kursus, materi, atau tugas..."
                        @input="handleSearch($el.value)"
                        @focus="open = true"
                        @keydown.escape="open = false"
                        class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >

                    <!-- Loading spinner -->
                    <div x-show="loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Search Results Dropdown -->
                    <div x-show="open && results"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                         style="display: none;">

                        <!-- Courses Section -->
                        <div x-show="results.courses && results.courses.length > 0" class="border-b border-gray-100">
                            <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Kursus
                            </div>
                            <div class="max-h-48 overflow-y-auto">
                                <template x-for="course in results.courses" :key="course.id">
                                    <a :href="course.url"
                                       class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <div x-show="course.thumbnail" class="mr-3 flex-shrink-0">
                                            <img :src="course.thumbnail" :alt="course.title"
                                                 class="w-10 h-10 rounded object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="course.title"></p>
                                            <p class="text-xs text-gray-500" x-text="course.teacher"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Lessons Section -->
                        <div x-show="results.lessons && results.lessons.length > 0">
                            <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Materi
                            </div>
                            <div class="max-h-48 overflow-y-auto">
                                <template x-for="lesson in results.lessons" :key="lesson.id">
                                    <a :href="lesson.url"
                                       class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="lesson.title"></p>
                                            <p class="text-xs text-gray-500" x-text="lesson.course"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- No Results -->
                        <div x-show="results && results.courses.length === 0 && results.lessons.length === 0"
                             class="px-4 py-8 text-center">
                            <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Tidak ada hasil dicari</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section: User Menu -->
            <div class="flex items-center gap-3">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                        >
                            @if(auth()->user()->foto)
                                <img src="{{ Storage::url(auth()->user()->foto) }}"
                                     class="object-cover w-9 h-9 rounded-full ring-2 ring-white shadow-md"
                                     alt="{{ auth()->user()->name }}">
                            @else
                                <div class="flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full shadow-md">
                                    <span class="text-sm font-medium text-white">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="hidden text-left sm:block">
                                <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">
                                    @if(auth()->user()->isAdmin())
                                        Administrator
                                    @elseif(auth()->user()->isGuru())
                                        Guru
                                    @else
                                        Siswa
                                    @endif
                                </p>
                            </div>
                            <svg class="hidden w-4 h-4 text-gray-400 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute right-0 z-50 w-64 mt-2 origin-top-right bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <div class="py-1">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('certificates.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Sertifikat
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-100">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white transition-all bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 hover:shadow-md">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<!-- Add Alpine.js for dropdown functionality -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
