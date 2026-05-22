<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    attendances: Object,
    schedules: Array,
    filters: Object,
});

const page = usePage();

const filter = reactive({
    schedule_id: props.filters.schedule_id ?? '',
    date: props.filters.date ?? '',
});

const applyFilter = () => {
    router.get(route('attendances.index'), filter, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Attendance" />

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
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Kehadiran
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Kehadiran
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola riwayat attendance siswa berdasarkan jadwal dan tanggal latihan
                    </p>
                </div>

                <Link
                    :href="route('attendances.scan')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75A1.125 1.125 0 013.75 8.625v-3.75zM14.25 4.875c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125v-3.75zM3.75 15.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125v-3.75z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.25 14.25h2.25m-2.25 3h3.75m-3.75 3h6m0-6h.008v.008H20.25v-.008z"
                        />
                    </svg>

                    Scan QR
                </Link>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Filter Kehadiran
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Pilih jadwal dan tanggal untuk melihat data attendance tertentu
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jadwal
                            </label>

                            <select
                                v-model="filter.schedule_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua jadwal</option>

                                <option
                                    v-for="schedule in schedules"
                                    :key="schedule.id"
                                    :value="schedule.id"
                                >
                                    {{ schedule.room?.name }}
                                    -
                                    {{ schedule.room?.dojang?.name }}
                                    -
                                    {{ schedule.day }}
                                    {{ schedule.start_time }} - {{ schedule.end_time }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Tanggal
                            </label>

                            <input
                                v-model="filter.date"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="flex items-end">
                            <button
                                type="button"
                                @click="applyFilter"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-95 md:w-auto"
                            >
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Riwayat Kehadiran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan data attendance yang tercatat di sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ attendances.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
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
                                    Room
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Jadwal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Catatan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="attendance in attendances.data"
                                :key="attendance.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full border border-gray-100 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ attendance.date }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ attendance.student?.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ attendance.student?.name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                Siswa
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-medium text-gray-700">
                                        {{ attendance.schedule?.room?.name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="inline-flex items-center gap-2 rounded-full border border-gray-100 bg-gray-50 px-3 py-1.5">
                                        <span class="text-xs font-semibold text-gray-700">
                                            {{ attendance.schedule?.day }}
                                            {{ attendance.schedule?.start_time }} - {{ attendance.schedule?.end_time }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-green-100 bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 capitalize">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        {{ attendance.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-700">
                                    {{ attendance.note ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <Link
                                        :href="route('attendances.show', [attendance.schedule_id, attendance.date])"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                    >
                                        Detail
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="attendances.data.length === 0">
                                <td colspan="7" class="py-16 text-center">
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
                                            Belum ada data kehadiran
                                        </p>

                                        <Link
                                            :href="route('attendances.scan')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Mulai scan QR attendance
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="attendances.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ attendances.from }}–{{ attendances.to }} dari {{ attendances.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in attendances.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 text-xs rounded-lg border transition-colors',
                                    link.active
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                                ]"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 text-xs rounded-lg border border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>