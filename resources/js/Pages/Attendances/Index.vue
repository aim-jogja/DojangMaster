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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Attendance
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

                <div class="rounded bg-white p-6 shadow">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Schedule
                            </label>

                            <select v-model="filter.schedule_id" class="w-full rounded border-gray-300">
                                <option value="">Semua schedule</option>

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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Tanggal
                            </label>

                            <input
                                v-model="filter.date"
                                type="date"
                                class="w-full rounded border-gray-300"
                            />
                        </div>

                        <div class="flex items-end">
                            <button
                                type="button"
                                @click="applyFilter"
                                class="rounded bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900"
                            >
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Room
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Schedule
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Catatan
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="attendance in attendances.data" :key="attendance.id">
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ attendance.date }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ attendance.student?.name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ attendance.schedule?.room?.name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ attendance.schedule?.day }}
                                    {{ attendance.schedule?.start_time }} - {{ attendance.schedule?.end_time }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">
                                        {{ attendance.status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ attendance.note ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm">
                                    <Link
                                        :href="route('attendances.show', [attendance.schedule_id, attendance.date])"
                                        class="font-semibold text-indigo-600 hover:text-indigo-900"
                                    >
                                        Detail
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="attendances.data.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data attendance.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="attendances.links?.length > 3" class="mt-4 flex flex-wrap gap-2">
                    <Link
                        v-for="link in attendances.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="rounded px-3 py-1 text-sm"
                        :class="[
                            link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700',
                            !link.url ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'
                        ]"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>