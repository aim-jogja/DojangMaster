<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';

const props = defineProps({
    schedule: Object,
    date: String,
    students: Array,
    statuses: Object,
});

const page = usePage();

const rows = reactive({});

const syncRows = () => {
    props.students.forEach((student) => {
        rows[student.id] = {
            status: student.status ?? 'absent',
            note: student.note ?? '',
            processing: false,
        };
    });
};

syncRows();

watch(
    () => props.students,
    () => syncRows(),
    { deep: true }
);

const saveManualAttendance = (student) => {
    rows[student.id].processing = true;

    router.post(
        route('attendances.manual.store'),
        {
            schedule_id: props.schedule.id,
            user_id: student.id,
            date: props.date,
            status: rows[student.id].status,
            note: rows[student.id].note,
        },
        {
            preserveScroll: true,
            onFinish: () => {
                rows[student.id].processing = false;
            },
        }
    );
};

const badgeClass = (status) => {
    return {
        present: 'bg-green-50 text-green-700 border-green-100',
        permission: 'bg-blue-50 text-blue-700 border-blue-100',
        sick: 'bg-amber-50 text-amber-700 border-amber-100',
        absent: 'bg-red-50 text-red-700 border-red-100',
    }[status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
};

const badgeDotClass = (status) => {
    return {
        present: 'bg-green-500',
        permission: 'bg-blue-500',
        sick: 'bg-amber-500',
        absent: 'bg-red-500',
    }[status] ?? 'bg-gray-400';
};
</script>

<template>
    <Head title="Detail Attendance" />

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
                    Detail Kehadiran
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="page.props.flash?.error"
                class="bg-red-50 border border-red-100 text-red-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.error }}
            </div>

            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ schedule.room?.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ schedule.room?.dojang?.name }}
                        -
                        {{ schedule.day }}
                        {{ schedule.start_time }} - {{ schedule.end_time }}
                    </p>
                </div>

                <Link
                    :href="route('attendances.scan')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all shrink-0"
                >
                    Scan QR
                </Link>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Jadwal
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail jadwal dan tanggal attendance
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border bg-indigo-50 text-indigo-700 border-indigo-100">
                        {{ date }}
                    </span>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Room
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ schedule.room?.name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ schedule.room?.dojang?.name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Jadwal
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ schedule.day }} {{ schedule.start_time }} - {{ schedule.end_time }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Total Siswa
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ students.length }} siswa
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Kehadiran Siswa
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Ubah status attendance manual jika diperlukan
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ students.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Siswa
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Email
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status Saat Ini
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Ubah Status
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
                                v-for="student in students"
                                :key="student.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ student.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ student.name }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Siswa
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-700">
                                    {{ student.email ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium capitalize"
                                        :class="badgeClass(student.status)"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="badgeDotClass(student.status)"
                                        ></span>

                                        {{ statuses[student.status] ?? student.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <select
                                        v-model="rows[student.id].status"
                                        class="w-full min-w-[140px] rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="present">Hadir</option>
                                        <option value="permission">Izin</option>
                                        <option value="sick">Sakit</option>
                                        <option value="absent">Tidak Hadir</option>
                                    </select>
                                </td>

                                <td class="px-5 py-4">
                                    <input
                                        v-model="rows[student.id].note"
                                        type="text"
                                        class="w-full min-w-[180px] rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Catatan opsional"
                                    />
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <button
                                        type="button"
                                        @click="saveManualAttendance(student)"
                                        :disabled="rows[student.id].processing"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 border border-gray-900 rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ rows[student.id].processing ? 'Menyimpan...' : 'Simpan' }}
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="students.length === 0">
                                <td colspan="6" class="py-16 text-center">
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
                                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada siswa aktif pada room ini
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-5 py-4 flex items-center justify-end gap-2 bg-gray-50/50">
                    <Link
                        :href="route('attendances.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Kembali
                    </Link>

                    <Link
                        :href="route('attendances.scan')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                    >
                        Scan QR
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>