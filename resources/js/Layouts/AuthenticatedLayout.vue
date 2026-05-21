<script setup>
import { ref, computed, nextTick, onMounted, onBeforeUnmount } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleCollapse = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const page = usePage();
const user = page.props.auth.user;
const roles = computed(() => user?.roles ?? []);

const canAccess = (itemRoles) => {
    if (!itemRoles || itemRoles.length === 0) return true;
    return itemRoles.some(role => roles.value.includes(role));
};

const filteredNavGroups = computed(() => {
    return navGroups
        .map(group => {
            const items = group.items.filter(item => canAccess(item.role));
            return { ...group, items };
        })
        .filter(group => group.items.length > 0);
});

// ── User dropdown (Teleport) ───────────────────────────────────
const userMenuOpen = ref(false);
const userMenuTrigger = ref(null);
const userMenuDropdown = ref(null);
const userMenuStyle = ref({});

const toggleUserMenu = async () => {
    userMenuOpen.value = !userMenuOpen.value;
    if (userMenuOpen.value) {
        await nextTick();
        const rect = userMenuTrigger.value?.getBoundingClientRect();
        if (!rect) return;
        userMenuStyle.value = {
            bottom: `${window.innerHeight - rect.top + 8}px`,
            left: `${rect.left}px`,
            width: `${Math.max(rect.width, 224)}px`,
        };
    }
};

const handleOutsideClick = (e) => {
    if (!userMenuOpen.value) return;
    if (userMenuTrigger.value?.contains(e.target) || userMenuDropdown.value?.contains(e.target)) return;
    userMenuOpen.value = false;
};

const handleKeydown = (e) => {
    if (e.key === 'Escape') userMenuOpen.value = false;
};

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
    document.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleOutsideClick);
    document.removeEventListener('keydown', handleKeydown);
});

const navGroups = [
    {
        label: null,
        items: [
            {
                name: 'Dashboard',
                route: 'dashboard',
                activePattern: 'dashboard',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>`,
                role: [],
            },
        ],
    },
    {
        label: 'Manajemen',
        items: [
            {
                name: 'Dojang',
                route: 'dojangs.index',
                activePattern: 'dojangs.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>`,
                role: ['super_admin', 'owner'],
            },
            {
                name: 'Kelas',
                route: 'rooms.index',
                activePattern: 'rooms.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>`,
                role: ['super_admin', 'owner'],
            },
            {
                name: 'Jadwal',
                route: 'schedules.index',
                activePattern: 'schedules.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>`,
                role: ['super_admin', 'owner'],
            },
            {
                name: 'Pelatih',
                route: 'trainers.index',
                activePattern: 'trainers.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`,
                role: ['super_admin', 'owner'],
            },
            {
                name: 'Siswa',
                route: 'students.index',
                activePattern: 'students.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>`,
                role: ['super_admin', 'owner'],
            },
        ],
    },
    {
        label: 'Kehadiran',
        items: [
            {
                name: 'Kehadiran',
                route: 'attendances.index',
                activePattern: 'attendances.index',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>`,
                role: ['super_admin', 'owner', 'trainer'],
            },
            {
                name: 'Scan Kehadiran',
                route: 'attendances.scan',
                activePattern: 'attendances.scan',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8H3a2 2 0 00-2 2v6a2 2 0 002 2h2m14-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2M5 8V6a2 2 0 012-2h10a2 2 0 012 2v2M5 8h14" />
                </svg>`,
                role: ['super_admin', 'owner', 'trainer'],
            },
        ],
    },
    {
        label: 'Pembayaran',
        items: [
            {
                name: 'Kategori Pembayaran',
                route: 'payment-categories.index',
                activePattern: 'payment-categories.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>`,
                role: ['super_admin', 'owner', 'trainer'],
            },
            {
                name: 'Tagihan',
                route: 'student-bills.index',
                activePattern: 'student-bills.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>`,
                role: ['super_admin', 'owner', 'trainer'],
            },
            {
                name: 'Pembayaran',
                route: 'student-payments.index',
                activePattern: 'student-payments.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>`,
                role: ['super_admin', 'owner', 'trainer', 'student'],
            },
        ],
    },
    {
        label: 'Subscription',
        items: [
            {
                name: 'Plans',
                route: 'subscription-plans.index',
                activePattern: 'subscription-plans.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>`,
                role: ['super_admin'],
            },
            {
                name: 'Features',
                route: 'features.index',
                activePattern: 'features.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l1.5 3 3.25.47-2.35 2.29.55 3.24-2.95-1.55-2.95 1.55.55-3.24L6.5 7.97l3.25-.47 1.5-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15" />
                </svg>`,
                role: ['super_admin'],
            },
        ],
    },
    {
        label: 'Billing',
        items: [
            {
                name: 'Dojang Payments',
                route: 'dojang-payments.index',
                activePattern: 'dojang-payments.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>`,
                role: ['super_admin'],
            },
        ],
    },
    {
        label: 'Billing',
        items: [
            {
                name: 'Billing',
                route: 'owner.billing.index',
                activePattern: 'owner.billing.*',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>`,
                role: ['owner'],
            },
        ],
    }
];
</script>

<template>
    <div class="flex min-h-screen bg-gray-50">

        <!-- Overlay mobile -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- ===== SIDEBAR ===== -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 flex flex-col bg-gray-900 transition-all duration-300 ease-in-out',
                sidebarCollapsed ? 'w-[70px]' : 'w-64',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
            ]"
        >
            <!-- Logo area -->
            <div class="flex items-center h-16 px-4 border-b border-gray-700/60 shrink-0">
                <Link :href="route('dashboard')" class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0 w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center shadow-md">
                        <ApplicationLogo class="h-5 w-5 fill-current text-white" />
                    </div>
                    <span
                        v-if="!sidebarCollapsed"
                        class="font-semibold text-white text-sm tracking-wide truncate transition-opacity duration-200"
                    >
                        Taekwondo
                    </span>
                </Link>

                <!-- Collapse toggle (desktop only) -->
                <button
                    @click="toggleCollapse"
                    class="hidden lg:flex ml-auto shrink-0 w-7 h-7 items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-700 transition-colors"
                    :title="sidebarCollapsed ? 'Expand' : 'Collapse'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation groups -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1 scrollbar-thin scrollbar-thumb-gray-700">
                <template v-for="group in filteredNavGroups" :key="group.label ?? 'main'">
                    <!-- Group label -->
                    <div
                        v-if="group.label && !sidebarCollapsed"
                        class="px-4 pt-4 pb-1"
                    >
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">
                            {{ group.label }}
                        </p>
                    </div>
                    <div v-else-if="group.label && sidebarCollapsed" class="pt-3 pb-1 flex justify-center">
                        <div class="w-6 border-t border-gray-700"></div>
                    </div>

                    <!-- Items -->
                    <Link
                        v-for="item in group.items"
                        :key="item.name"
                        :href="route(item.route)"
                        :class="[
                            'group flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150',
                            route().current(item.activePattern)
                                ? 'bg-indigo-600 text-white shadow-sm'
                                : 'text-gray-400 hover:bg-gray-800 hover:text-white',
                            sidebarCollapsed ? 'justify-center px-0' : '',
                        ]"
                        :title="sidebarCollapsed ? item.name : ''"
                    >
                        <span class="shrink-0" v-html="item.icon" />
                        <span v-if="!sidebarCollapsed" class="truncate">{{ item.name }}</span>
                    </Link>
                </template>
            </nav>

            <!-- User section at bottom -->
            <div class="border-t border-gray-700/60 p-3 shrink-0">
                <button
                    ref="userMenuTrigger"
                    @click="toggleUserMenu"
                    :class="[
                        'w-full flex items-center gap-3 rounded-lg p-2 text-left hover:bg-gray-800 transition-colors',
                        sidebarCollapsed ? 'justify-center' : '',
                    ]"
                >
                    <div class="shrink-0 w-8 h-8 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-semibold text-xs uppercase">
                        {{ $page.props.auth.user.name?.charAt(0) }}
                    </div>
                    <div v-if="!sidebarCollapsed" class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" :class="['w-4 h-4 text-gray-500 shrink-0 transition-transform duration-200', userMenuOpen ? 'rotate-180' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </button>

                <Teleport to="body">
                    <Transition
                        enter-active-class="transition duration-150 ease-out"
                        enter-from-class="opacity-0 translate-y-1"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition duration-100 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 translate-y-1"
                    >
                        <div
                            v-if="userMenuOpen"
                            ref="userMenuDropdown"
                            class="fixed z-[9999] bg-white rounded-xl shadow-xl ring-1 ring-black/5 overflow-hidden py-1"
                            :style="userMenuStyle"
                        >
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    @click="userMenuOpen = false"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Log Out
                                </Link>
                            </div>
                        </div>
                    </Transition>
                </Teleport>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <div
            :class="[
                'flex flex-col flex-1 min-w-0 transition-all duration-300 ease-in-out',
                sidebarCollapsed ? 'lg:ml-[70px]' : 'lg:ml-64',
            ]"
        >
            <!-- Top bar -->
            <header class="sticky top-0 z-10 h-16 bg-white border-b border-gray-200 flex items-center px-4 sm:px-6 gap-4 shrink-0 shadow-sm">
                <!-- Hamburger (mobile) -->
                <button
                    @click="toggleSidebar"
                    class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                    aria-label="Toggle sidebar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page title / breadcrumb slot -->
                <div class="flex-1 min-w-0">
                    <slot name="header" />
                </div>

                <!-- Right side: user info -->
                <div class="hidden lg:flex items-center gap-3 ml-auto">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-600 font-semibold text-xs uppercase">
                        {{ $page.props.auth.user.name?.charAt(0) }}
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>