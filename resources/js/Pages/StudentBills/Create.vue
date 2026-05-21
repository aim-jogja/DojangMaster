<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    dojangs: Array,
    rooms: Array,
    categories: Array,
});

const form = useForm({
    dojang_id: props.dojangs.length === 1 ? props.dojangs[0].id : '',
    room_id: '',
    payment_category_id: '',
    title: '',
    description: '',
    period: '',
    amount: '',
    due_date: '',
});

const filteredRooms = computed(() => {
    if (!form.dojang_id) return props.rooms;

    return props.rooms.filter((room) => Number(room.dojang_id) === Number(form.dojang_id));
});

const filteredCategories = computed(() => {
    if (!form.dojang_id) return props.categories;

    return props.categories.filter((category) => Number(category.dojang_id) === Number(form.dojang_id));
});

watch(
    () => form.dojang_id,
    () => {
        form.room_id = '';
        form.payment_category_id = '';
    }
);

const submit = () => {
    form.post(route('student-bills.store'));
};
</script>

<template>
    <Head title="Generate Tagihan Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Generate Tagihan Siswa
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-xl bg-white p-6 shadow-sm"
                >
                    <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-700">
                        Form ini akan membuat tagihan untuk semua siswa aktif di room yang dipilih.
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Dojang
                        </label>
                        <select
                            v-model="form.dojang_id"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="">Pilih Dojang</option>
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
                            Room
                        </label>
                        <select
                            v-model="form.room_id"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="">Pilih Room</option>
                            <option
                                v-for="room in filteredRooms"
                                :key="room.id"
                                :value="room.id"
                            >
                                {{ room.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.room_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.room_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Kategori
                        </label>
                        <select
                            v-model="form.payment_category_id"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="">Tanpa Kategori</option>
                            <option
                                v-for="category in filteredCategories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.payment_category_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.payment_category_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Judul Tagihan
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="Contoh: Iuran Bulanan Mei 2026"
                        />
                        <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Periode
                        </label>
                        <input
                            v-model="form.period"
                            type="month"
                            class="w-full rounded-lg border-gray-300"
                        />
                        <div v-if="form.errors.period" class="mt-1 text-sm text-red-600">
                            {{ form.errors.period }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nominal
                        </label>
                        <input
                            v-model="form.amount"
                            type="number"
                            min="0"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="150000"
                        />
                        <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                            {{ form.errors.amount }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Jatuh Tempo
                        </label>
                        <input
                            v-model="form.due_date"
                            type="date"
                            class="w-full rounded-lg border-gray-300"
                        />
                        <div v-if="form.errors.due_date" class="mt-1 text-sm text-red-600">
                            {{ form.errors.due_date }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="Opsional"
                        />
                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            :href="route('student-bills.index')"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Generate Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>