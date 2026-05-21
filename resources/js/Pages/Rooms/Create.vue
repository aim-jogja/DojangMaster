<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    dojangs: Array,
});

const form = useForm({
    dojang_id: props.dojangs.length === 1 ? props.dojangs[0].id : '',
    name: '',
    description: '',
    registration_fee: 0,
    monthly_fee: 0,
    is_active: true,
});

const submit = () => {
    form.post(route('rooms.store'));
};
</script>

<template>
    <Head title="Tambah Room" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tambah Room / Kelas
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6 rounded bg-white p-6 shadow">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Dojang
                        </label>

                        <select
                            v-model="form.dojang_id"
                            class="w-full rounded border-gray-300"
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

                        <div v-if="form.errors.dojang_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.dojang_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nama Room
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded border-gray-300"
                            placeholder="Contoh: Kelas Pemula"
                        />

                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            class="w-full rounded border-gray-300"
                            rows="4"
                            placeholder="Deskripsi singkat kelas"
                        />

                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Biaya Pendaftaran
                            </label>

                            <input
                                v-model="form.registration_fee"
                                type="number"
                                min="0"
                                class="w-full rounded border-gray-300"
                            />

                            <div v-if="form.errors.registration_fee" class="mt-1 text-sm text-red-600">
                                {{ form.errors.registration_fee }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Biaya Bulanan
                            </label>

                            <input
                                v-model="form.monthly_fee"
                                type="number"
                                min="0"
                                class="w-full rounded border-gray-300"
                            />

                            <div v-if="form.errors.monthly_fee" class="mt-1 text-sm text-red-600">
                                {{ form.errors.monthly_fee }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300"
                        />

                        <label for="is_active" class="text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link
                            :href="route('rooms.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
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