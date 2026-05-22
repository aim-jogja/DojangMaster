<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    dojangs: Array,
    rooms: Array,
});

const form = useForm({
    dojang_id: props.dojangs.length === 1 ? props.dojangs[0].id : '',
    room_ids: [],
    name: '',
    email: '',
    password: '',
});

const roomsByDojang = () => {
    if (!form.dojang_id) return [];
    return props.rooms.filter(room => Number(room.dojang_id) === Number(form.dojang_id));
};

const submit = () => {
    form.post(route('students.store'));
};
</script>

<template>
    <Head title="Tambah Student" />

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
                    Tambah Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Tambah Siswa
                </h1>

                <p class="text-sm text-gray-500 mt-0.5">
                    Buat akun siswa baru dan hubungkan dengan dojang serta room/kelas
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Data Siswa
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Lengkapi identitas siswa, akses login, dan pilihan kelas
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </label>

                        <select
                            v-model="form.dojang_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih dojang</option>
                            <option
                                v-for="dojang in dojangs"
                                :key="dojang.id"
                                :value="dojang.id"
                            >
                                {{ dojang.name }}
                            </option>
                        </select>

                        <div v-if="form.errors.dojang_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.dojang_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Room / Kelas
                        </label>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <div v-if="roomsByDojang().length" class="grid grid-cols-1 gap-2 md:grid-cols-2">
                                <label
                                    v-for="room in roomsByDojang()"
                                    :key="room.id"
                                    class="flex items-center gap-3 rounded-lg border border-gray-100 bg-white px-3 py-2 text-sm text-gray-700 hover:border-indigo-100 hover:bg-indigo-50/40 transition-colors"
                                >
                                    <input
                                        v-model="form.room_ids"
                                        :value="room.id"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />

                                    <span class="font-medium">
                                        {{ room.name }}
                                    </span>
                                </label>
                            </div>

                            <p v-else class="text-sm text-gray-500">
                                Belum ada room untuk dojang ini.
                            </p>
                        </div>

                        <div v-if="form.errors.room_ids" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.room_ids }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Siswa
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: Budi Santoso"
                            />

                            <div v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Email
                            </label>

                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="siswa@email.com"
                            />

                            <div v-if="form.errors.email" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Password
                        </label>

                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Masukkan password akun siswa"
                        />

                        <div v-if="form.errors.password" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.password }}
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('students.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Siswa' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>