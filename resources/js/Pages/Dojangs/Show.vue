<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    dojang: Object,
});
</script>

<template>
    <Head title="Detail Dojang" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2 min-w-0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-indigo-500 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-3M9 9h1m-1 4h1m-1 4h1m4-4h1m-1 4h1"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Detail Dojang
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <!-- Page heading -->
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ dojang.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Detail informasi dojang, owner, dan room/kelas yang tersedia
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="dojang.is_active
                        ? 'bg-green-50 text-green-700 border-green-100'
                        : 'bg-red-50 text-red-700 border-red-100'"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="dojang.is_active ? 'bg-green-500' : 'bg-red-500'"
                    ></span>

                    {{ dojang.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <!-- Main info card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Dojang
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data utama dojang dan pemilik
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ dojang.name?.charAt(0) ?? 'D' }}
                    </div>
                </div>

                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Dojang
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ dojang.name ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Domain
                            </dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center gap-1.5 font-mono text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-3 h-3"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 21a9 9 0 100-18 9 9 0 000 18z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18"
                                        />
                                    </svg>

                                    {{ dojang.domain ?? '-' }}
                                </span>
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Owner
                            </dt>
                            <dd class="mt-2 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                    {{ dojang.owner?.name?.charAt(0) ?? '-' }}
                                </div>

                                <span class="text-sm font-semibold text-gray-900">
                                    {{ dojang.owner?.name ?? '-' }}
                                </span>
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Email Owner
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ dojang.owner?.email ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Telepon
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ dojang.phone ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Alamat
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ dojang.address ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Room table -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Room / Kelas
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Daftar room atau kelas yang terhubung dengan dojang ini
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ dojang.rooms?.length ?? 0 }} item
                    </span>
                </div>

                <div v-if="dojang.rooms?.length" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nama
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Pendaftaran
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Bulanan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="room in dojang.rooms"
                                :key="room.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[180px]">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase shrink-0">
                                            {{ room.name?.charAt(0) ?? 'R' }}
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ room.name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm font-medium text-gray-700">
                                    Rp {{ Number(room.registration_fee).toLocaleString('id-ID') }}
                                </td>

                                <td class="px-5 py-4 text-sm font-medium text-gray-700">
                                    Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border"
                                        :class="room.is_active
                                            ? 'bg-green-50 text-green-700 border-green-100'
                                            : 'bg-red-50 text-red-700 border-red-100'"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="room.is_active ? 'bg-green-500' : 'bg-red-500'"
                                        ></span>

                                        {{ room.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-14 text-center">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-10 h-10 text-gray-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
                            />
                        </svg>

                        <p class="text-sm font-medium">
                            Belum ada room/kelas
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('dojangs.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('dojangs.edit', dojang.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>