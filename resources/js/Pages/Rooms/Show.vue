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
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Detail Room / Kelas
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ room.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ room.dojang?.name ?? '-' }}
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
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
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Room
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail biaya dan deskripsi room / kelas
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ room.name?.charAt(0) ?? 'R' }}
                    </div>
                </div>

                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Room
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ room.name ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dojang
                            </dt>
                            <dd class="mt-2 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                    {{ room.dojang?.name?.charAt(0) ?? '-' }}
                                </div>

                                <span class="text-sm font-semibold text-gray-900">
                                    {{ room.dojang?.name ?? '-' }}
                                </span>
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Biaya Pendaftaran
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                Rp {{ Number(room.registration_fee).toLocaleString('id-ID') }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Biaya Bulanan
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                            </dd>
                        </div>

                        <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Deskripsi
                            </dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 leading-6">
                                {{ room.description ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Jadwal
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Jadwal latihan pada room ini
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ room.schedules?.length ?? 0 }} item
                    </span>
                </div>

                <div v-if="room.schedules?.length" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Hari
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Mulai
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Selesai
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="schedule in room.schedules"
                                :key="schedule.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4 font-medium text-gray-900">
                                    {{ schedule.day }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ schedule.start_time }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ schedule.end_time }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-12 text-center">
                    <p class="text-sm text-gray-400">
                        Belum ada jadwal.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Siswa
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Daftar siswa yang tergabung dalam room ini
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ room.students?.length ?? 0 }} item
                    </span>
                </div>

                <div v-if="room.students?.length" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nama
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Email
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="student in room.students"
                                :key="student.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-xs uppercase shrink-0">
                                            {{ student.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ student.name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    {{ student.email }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border"
                                        :class="student.pivot?.is_active
                                            ? 'bg-green-50 text-green-700 border-green-100'
                                            : 'bg-red-50 text-red-700 border-red-100'"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="student.pivot?.is_active ? 'bg-green-500' : 'bg-red-500'"
                                        ></span>

                                        {{ student.pivot?.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-12 text-center">
                    <p class="text-sm text-gray-400">
                        Belum ada siswa di room ini.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('rooms.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('rooms.edit', room.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>