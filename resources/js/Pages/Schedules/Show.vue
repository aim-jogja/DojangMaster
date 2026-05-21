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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Schedule / Jadwal
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="rounded bg-white p-6 shadow">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ schedule.room?.name ?? '-' }}
                        </h1>

                        <p class="text-sm text-gray-500">
                            {{ schedule.room?.dojang?.name ?? '-' }}
                        </p>
                    </div>

                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Hari
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ schedule.day }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Jam Mulai
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ schedule.start_time }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Jam Selesai
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ schedule.end_time }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">
                            Riwayat Absensi
                        </h3>

                        <div v-if="schedule.attendances?.length" class="overflow-hidden rounded border">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Tanggal
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Siswa
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Status
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Catatan
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="attendance in schedule.attendances" :key="attendance.id">
                                        <td class="px-4 py-2 text-sm">
                                            {{ attendance.date }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ attendance.student?.name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ attendance.status }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ attendance.note ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p v-else class="text-sm text-gray-500">
                            Belum ada data absensi untuk jadwal ini.
                        </p>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('schedules.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('schedules.edit', schedule.id)"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>