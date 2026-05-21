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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tambah Student
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6 rounded bg-white p-6 shadow">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Dojang</label>
                        <select v-model="form.dojang_id" class="w-full rounded border-gray-300">
                            <option value="">Pilih dojang</option>
                            <option v-for="dojang in dojangs" :key="dojang.id" :value="dojang.id">
                                {{ dojang.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.dojang_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.dojang_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Room / Kelas</label>

                        <div class="space-y-2 rounded border p-3">
                            <label
                                v-for="room in roomsByDojang()"
                                :key="room.id"
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                <input
                                    v-model="form.room_ids"
                                    :value="room.id"
                                    type="checkbox"
                                    class="rounded border-gray-300"
                                />
                                <span>{{ room.name }}</span>
                            </label>

                            <p v-if="roomsByDojang().length === 0" class="text-sm text-gray-500">
                                Belum ada room untuk dojang ini.
                            </p>
                        </div>

                        <div v-if="form.errors.room_ids" class="mt-1 text-sm text-red-600">
                            {{ form.errors.room_ids }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                        <input v-model="form.name" type="text" class="w-full rounded border-gray-300" />
                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input v-model="form.email" type="email" class="w-full rounded border-gray-300" />
                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                        <input v-model="form.password" type="password" class="w-full rounded border-gray-300" />
                        <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link :href="route('students.index')" class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>