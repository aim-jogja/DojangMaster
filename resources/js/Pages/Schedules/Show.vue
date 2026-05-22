<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    schedule: Object,
});
</script>

<template>
    <Head title="Detail Schedule" />

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
                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Detail Jadwal
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ schedule.room?.name ?? '-' }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ schedule.room?.dojang?.name ?? '-' }}
                    </p>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border bg-indigo-50 text-indigo-700 border-indigo-100 shrink-0">
                    {{ schedule.day }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Jadwal
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail room, hari, dan jam latihan
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                            />
                        </svg>
                    </div>
                </div>

                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Hari
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ schedule.day }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jam Mulai
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ schedule.start_time }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jam Selesai
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ schedule.end_time }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Riwayat Absensi
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data absensi yang terhubung dengan jadwal ini
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ schedule.attendances?.length ?? 0 }} item
                    </span>
                </div>

                <div v-if="schedule.attendances?.length" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Tanggal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Siswa
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Catatan
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="attendance in schedule.attendances"
                                :key="attendance.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                    {{ attendance.date }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-xs uppercase shrink-0">
                                            {{ attendance.student?.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ attendance.student?.name ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full border bg-gray-50 text-gray-700 border-gray-100 capitalize">
                                        {{ attendance.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-700">
                                    {{ attendance.note ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-12 text-center">
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>

                        <p class="text-sm font-medium">
                            Belum ada data absensi untuk jadwal ini
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('schedules.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('schedules.edit', schedule.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>