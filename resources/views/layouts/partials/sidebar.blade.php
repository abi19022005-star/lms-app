<!-- Sidebar Modern -->
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-20 w-72 bg-white border-r border-gray-200 shadow-lg transition-all duration-300 ease-in-out md:relative md:z-10 md:shadow-sm"
    :style="{'transform': sidebarOpen ? 'translateX(0)' : 'translateX(-100%)', 'display': 'flex', 'flexDirection': 'column'}"
    @click.away=" sidebarOpen = false">
    <!-- Overlay untuk mobile -->
    <div
        id="sidebarOverlay"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-10 bg-black/50 md:hidden transition-opacity"
        :style="{'opacity': sidebarOpen ? '1' : '0', 'pointerEvents': sidebarOpen ? 'auto' : 'none'}"></div>

    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-100 md:hidden">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-500 rounded-lg"></div>
                <span class="text-lg font-semibold text-gray-800">Menu</span>
            </div>
            <button @click="toggleSidebar()" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            @auth
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Courses -->
                <a href="{{ route('courses.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('courses.index') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('courses.index') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Semua Kursus</span>
                </a>

                <!-- Certificates -->
                <a href="{{ route('certificates.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('certificates.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('certificates.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Sertifikat</span>
                </a>

                <div class="my-4 border-t border-gray-100"></div>

                <!-- SISWA -->
                @if(auth()->user()->isSiswa())
                <div class="px-3 py-1">
                    <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Menu Siswa</p>
                </div>

                <a href="{{ route('siswa.courses.my') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('siswa.courses.my') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('siswa.courses.my') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                    </svg>
                    <span>Kursus Saya</span>
                </a>

                <a href="{{ route('progress.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('progress.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('progress.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>

                    <span>Progress Belajar</span>
                </a>
                @endif

                <!-- GURU -->
                @if(auth()->user()->isGuru())
                <div class="px-3 py-1">
                    <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Menu Guru</p>
                </div>

                <a href="{{ route('guru.courses.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('guru.courses.index') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('guru.courses.index') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                    </svg>

                    <span>Kursus Saya</span>
                </a>
                <a href="{{ route('guru.students.index') }}"class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('guru.students.index') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('guru.students.index') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <span>Siswa Saya</span>
                </a>



                <a href="{{ route('grading.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('grading.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('grading.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <span>Penilaian</span>
                </a>
                <a href="{{ route('courses.create') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('courses.create') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('courses.create') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Kursus Baru</span>
                </a>

                @endif

                <!-- ADMIN -->
                @if(auth()->user()->isAdmin())
                <div class="px-3 py-1">
                    <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Administrasi</p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span>Kelola Pengguna</span>
                </a>

                <a  href="{{ route('admin.students.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-colors duration-200 {{ request()->routeIs('admin.students.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span> Semua Siswa</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>

                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span>Laporan</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-colors duration-200 {{ request()->routeIs('admin.settings.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                    </svg>
                    <span>Pengaturan</span>
                </a>

                <a href="{{ route('admin.activity-logs.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 pl-8 text-sm rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.activity-logs.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    <span>Log Aktivitas</span>
                </a>
                @endif

                <div class="my-4 border-t border-gray-100"></div>

                <!-- Profile & Logout -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil Saya</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center w-full gap-3 px-3 py-2.5 text-sm font-medium text-red-600 rounded-xl transition-all duration-200 hover:bg-red-50 group">
                        <svg class="w-5 h-5 text-red-400 transition-colors duration-200 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            @endauth
        </nav>

        <!-- Sidebar Footer (Version Info) -->
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center justify-between text-xs text-gray-400">
                <span>© 2026 {{ setting('app_name', config('app.name')) }}</span>
                <span>v2.0.0</span>
            </div>
        </div>
    </div>
</aside>

<script>
    // Fungsi toggle untuk mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('translate-x-0');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }

    // Tutup sidebar saat klik overlay
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
    });
</script>
