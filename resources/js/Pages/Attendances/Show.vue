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
        present: 'bg-green-100 text-green-700',
        permission: 'bg-blue-100 text-blue-700',
        sick: 'bg-yellow-100 text-yellow-700',
        absent: 'bg-red-100 text-red-700',
    }[status] ?? 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Detail Attendance" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detail Attendance
                </h2>

                <Link
                    :href="route('attendances.scan')"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Scan QR
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="rounded bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="rounded bg-red-100 px-4 py-3 text-red-700"
                >
                    {{ page.props.flash.error }}
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ schedule.room?.name }}
                            </h1>

                            <p class="text-sm text-gray-500">
                                {{ schedule.room?.dojang?.name }}
                                -
                                {{ schedule.day }}
                                {{ schedule.start_time }} - {{ schedule.end_time }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Tanggal: {{ date }}
                            </p>
                        </div>

                        <div class="rounded bg-gray-50 px-4 py-3 text-sm text-gray-700">
                            Total siswa:
                            <span class="font-semibold">{{ students.length }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                                        Siswa
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                                        Email
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                                        Status Saat Ini
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                                        Ubah Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">
                                        Catatan
                                    </th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="student in students" :key="student.id">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ student.name }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ student.email ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="badgeClass(student.status)"
                                        >
                                            {{ statuses[student.status] ?? student.status }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <select
                                            v-model="rows[student.id].status"
                                            class="w-full rounded border-gray-300 text-sm"
                                        >
                                            <option value="present">Hadir</option>
                                            <option value="permission">Izin</option>
                                            <option value="sick">Sakit</option>
                                            <option value="absent">Tidak Hadir</option>
                                        </select>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <input
                                            v-model="rows[student.id].note"
                                            type="text"
                                            class="w-full rounded border-gray-300 text-sm"
                                            placeholder="Catatan opsional"
                                        />
                                    </td>

                                    <td class="px-4 py-3 text-right text-sm">
                                        <button
                                            type="button"
                                            @click="saveManualAttendance(student)"
                                            :disabled="rows[student.id].processing"
                                            class="rounded bg-gray-800 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-900 disabled:opacity-50"
                                        >
                                            {{ rows[student.id].processing ? 'Menyimpan...' : 'Simpan' }}
                                        </button>
                                    </td>
                                </tr>

                                <tr v-if="students.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Belum ada siswa aktif pada room ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('attendances.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('attendances.scan')"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Scan QR
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>