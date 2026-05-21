<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    bill: Object,
    categories: Array,
});

const form = useForm({
    payment_category_id: props.bill.payment_category_id || '',
    title: props.bill.title,
    description: props.bill.description || '',
    period: props.bill.period || '',
    amount: props.bill.amount,
    due_date: props.bill.due_date || '',
    status: props.bill.status,
});

const submit = () => {
    form.put(route('student-bills.update', props.bill.id));
};
</script>

<template>
    <Head title="Edit Tagihan Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Tagihan Siswa
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-xl bg-white p-6 shadow-sm"
                >
                    <div class="rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                        <div>
                            <span class="font-medium">Invoice:</span>
                            {{ bill.invoice_number }}
                        </div>
                        <div>
                            <span class="font-medium">Siswa:</span>
                            {{ bill.student?.name }}
                        </div>
                        <div>
                            <span class="font-medium">Room:</span>
                            {{ bill.room?.name }}
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
                                v-for="category in categories"
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
                            Status
                        </label>
                        <select
                            v-model="form.status"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="unpaid">Belum Bayar</option>
                            <!-- <option value="partial">Sebagian</option> -->
                            <option value="cancelled">Dibatalkan</option>
                            <option value="expired">Kadaluarsa</option>
                        </select>
                        <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                            {{ form.errors.status }}
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
                        />
                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            :href="route('student-bills.show', bill.id)"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>