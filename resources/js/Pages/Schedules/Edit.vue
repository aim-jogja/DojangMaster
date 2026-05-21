<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    schedule: Object,
    rooms: Array,
    days: Object,
});

const normalizeTime = (time) => {
    if (!time) return '';
    return String(time).slice(0, 5);
};

const form = useForm({
    room_id: props.schedule.room_id,
    day: props.schedule.day,
    start_time: normalizeTime(props.schedule.start_time),
    end_time: normalizeTime(props.schedule.end_time),
});

const submit = () => {
    form.put(route('schedules.update', props.schedule.id));
};
</script>

<template>
    <Head title="Edit Schedule" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Schedule / Jadwal
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6 rounded bg-white p-6 shadow">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Room / Kelas
                        </label>

                        <select
                            v-model="form.room_id"
                            class="w-full rounded border-gray-300"
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

                        <div v-if="form.errors.room_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.room_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Hari
                        </label>

                        <select
                            v-model="form.day"
                            class="w-full rounded border-gray-300"
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

                        <div v-if="form.errors.day" class="mt-1 text-sm text-red-600">
                            {{ form.errors.day }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Jam Mulai
                            </label>

                            <input
                                v-model="form.start_time"
                                type="time"
                                class="w-full rounded border-gray-300"
                            />

                            <div v-if="form.errors.start_time" class="mt-1 text-sm text-red-600">
                                {{ form.errors.start_time }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Jam Selesai
                            </label>

                            <input
                                v-model="form.end_time"
                                type="time"
                                class="w-full rounded border-gray-300"
                            />

                            <div v-if="form.errors.end_time" class="mt-1 text-sm text-red-600">
                                {{ form.errors.end_time }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link
                            :href="route('schedules.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>