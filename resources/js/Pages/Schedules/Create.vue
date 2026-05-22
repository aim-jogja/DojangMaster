<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    rooms: Array,
    days: Object,
});

const form = useForm({
    room_id: props.rooms.length === 1 ? props.rooms[0].id : '',
    day: '',
    start_time: '',
    end_time: '',
});

const submit = () => {
    form.post(route('schedules.store'));
};
</script>

<template>
    <Head title="Tambah Schedule" />

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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Tambah Jadwal
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Tambah Jadwal
                </h1>

                <p class="text-sm text-gray-500 mt-0.5">
                    Buat jadwal latihan baru berdasarkan room, hari, dan jam latihan
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Data Jadwal
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Lengkapi informasi jadwal latihan
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
                        <svg
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            />
                        </svg>

                        {{ form.processing ? 'Menyimpan...' : 'Simpan Jadwal' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>