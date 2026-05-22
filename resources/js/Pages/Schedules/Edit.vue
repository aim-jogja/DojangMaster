<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    schedule: Object,
    rooms: Array,
    days: Object,
});

const form = useForm({
    room_id: props.schedule.room_id,
    day: props.schedule.day,
    start_time: props.schedule.start_time,
    end_time: props.schedule.end_time,
});

const submit = () => {
    form.put(route('schedules.update', props.schedule.id));
};
</script>

<template>
    <Head title="Edit Schedule" />

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
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 7.125L16.875 4.5M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Edit Jadwal
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        Edit Jadwal {{ schedule.room?.name ?? '-' }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Perbarui room, hari, dan jam latihan pada jadwal ini
                    </p>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border bg-indigo-50 text-indigo-700 border-indigo-100 shrink-0">
                    {{ form.day || 'Jadwal' }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Jadwal
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data jadwal saat ini sebelum diperbarui
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

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-3">
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
                            Jam
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ schedule.start_time }} - {{ schedule.end_time }}
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Edit Jadwal
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sesuaikan data jadwal latihan yang perlu diperbarui
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Room / Kelas
                        </label>

                        <select
                            v-model="form.room_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih room</option>
                            <option
                                v-for="room in rooms"
                                :key="room.id"
                                :value="room.id"
                            >
                                {{ room.name }} - {{ room.dojang?.name ?? '-' }}
                            </option>
                        </select>

                        <div v-if="form.errors.room_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.room_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Hari
                        </label>

                        <select
                            v-model="form.day"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih hari</option>
                            <option
                                v-for="(label, value) in days"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>

                        <div v-if="form.errors.day" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.day }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jam Mulai
                            </label>

                            <input
                                v-model="form.start_time"
                                type="time"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.start_time" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.start_time }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jam Selesai
                            </label>

                            <input
                                v-model="form.end_time"
                                type="time"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.end_time" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.end_time }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('schedules.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Mengupdate...' : 'Update Jadwal' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>