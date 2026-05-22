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
                    Generate Tagihan Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Generate Tagihan Siswa
                </h1>

                <p class="text-sm text-gray-500 mt-0.5">
                    Buat tagihan untuk seluruh siswa aktif pada room yang dipilih
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Generate Tagihan
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Tagihan akan dibuat untuk semua siswa aktif di room terpilih
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                        <p class="text-sm font-semibold text-gray-900">
                            Informasi
                        </p>
                        <p class="mt-0.5 text-sm text-gray-600">
                            Form ini akan membuat tagihan massal untuk semua siswa aktif di room yang dipilih.
                        </p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </label>

                        <select
                            v-model="form.dojang_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                        <div v-if="form.errors.dojang_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.dojang_id }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Room
                            </label>

                            <select
                                v-model="form.room_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                            <div v-if="form.errors.room_id" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.room_id }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Kategori
                            </label>

                            <select
                                v-model="form.payment_category_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Pilih Kategori</option>
                                <option
                                    v-for="category in filteredCategories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <div v-if="form.errors.payment_category_id" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.payment_category_id }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Judul Tagihan
                        </label>

                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: SPP Bulanan Mei 2026"
                        />

                        <div v-if="form.errors.title" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Periode
                            </label>

                            <input
                                v-model="form.period"
                                type="month"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.period" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.period }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nominal
                            </label>

                            <input
                                v-model="form.amount"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0"
                            />

                            <div v-if="form.errors.amount" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.amount }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jatuh Tempo
                            </label>

                            <input
                                v-model="form.due_date"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.due_date" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.due_date }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Opsional"
                        />

                        <div v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('student-bills.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Membuat...' : 'Generate Tagihan' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>