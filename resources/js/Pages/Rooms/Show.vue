<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    room: Object,
});
</script>

<template>
    <Head title="Detail Room" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Room / Kelas
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="rounded bg-white p-6 shadow">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ room.name }}
                            </h1>

                            <p class="text-sm text-gray-500">
                                {{ room.dojang?.name ?? '-' }}
                            </p>
                        </div>

                        <span
                            class="rounded-full px-3 py-1 text-sm"
                            :class="room.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                        >
                            {{ room.is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Biaya Pendaftaran
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                Rp {{ Number(room.registration_fee).toLocaleString('id-ID') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Biaya Bulanan
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                            </dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Deskripsi
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ room.description ?? '-' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">
                            Jadwal
                        </h3>

                        <div v-if="room.schedules?.length" class="overflow-hidden rounded border">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Hari
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Mulai
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Selesai
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="schedule in room.schedules" :key="schedule.id">
                                        <td class="px-4 py-2 text-sm">
                                            {{ schedule.day }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ schedule.start_time }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ schedule.end_time }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p v-else class="text-sm text-gray-500">
                            Belum ada jadwal.
                        </p>
                    </div>

                    <div class="mt-8">
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">
                            Siswa
                        </h3>

                        <div v-if="room.students?.length" class="overflow-hidden rounded border">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Nama
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Email
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Status
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="student in room.students" :key="student.id">
                                        <td class="px-4 py-2 text-sm">
                                            {{ student.name }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ student.email }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ student.pivot?.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p v-else class="text-sm text-gray-500">
                            Belum ada siswa di room ini.
                        </p>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('rooms.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('rooms.edit', room.id)"
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