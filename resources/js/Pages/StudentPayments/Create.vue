<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    bill: Object,
    paymentMethods: Object,
});

const form = useForm({
    amount: props.bill.remaining_amount,
    payment_method: 'cash',
    paid_at: new Date().toISOString().slice(0, 10),
    note: '',
});

const submit = () => {
    form.post(route('student-payments.store', props.bill.id));
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value || 0);
};
</script>

<template>
    <Head title="Tambah Pembayaran Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tambah Pembayaran Siswa
                </h2>

                <Link
                    :href="route('student-bills.show', bill.id)"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <!-- Bill Info -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm text-gray-500">
                                {{ bill.invoice_number }}
                            </div>
                            <h3 class="mt-1 text-xl font-semibold text-gray-900">
                                {{ bill.title }}
                            </h3>
                            <div class="mt-1 text-sm text-gray-500">
                                {{ bill.category?.name || 'Tanpa kategori' }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-500">
                                Sisa Tagihan
                            </div>
                            <div class="text-xl font-bold text-red-700">
                                {{ formatCurrency(bill.remaining_amount) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Siswa</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ bill.student?.name || '-' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ bill.student?.email || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Dojang / Room</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ bill.dojang?.name || '-' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ bill.room?.name || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Nominal Tagihan</div>
                            <div class="mt-1 font-semibold text-gray-900">
                                {{ formatCurrency(bill.amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Sudah Dibayar</div>
                            <div class="mt-1 font-semibold text-gray-900">
                                {{ formatCurrency(bill.paid_amount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-xl bg-white p-6 shadow-sm"
                >
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nominal Pembayaran
                        </label>
                        <input
                            v-model="form.amount"
                            type="number"
                            min="1"
                            :max="bill.remaining_amount"
                            class="w-full rounded-lg border-gray-300"
                        />
                        <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                            {{ form.errors.amount }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            Maksimal pembayaran: {{ formatCurrency(bill.remaining_amount) }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Metode Pembayaran
                        </label>
                        <select
                            v-model="form.payment_method"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option
                                v-for="(label, value) in paymentMethods"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>
                        <div v-if="form.errors.payment_method" class="mt-1 text-sm text-red-600">
                            {{ form.errors.payment_method }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Tanggal Bayar
                        </label>
                        <input
                            v-model="form.paid_at"
                            type="date"
                            class="w-full rounded-lg border-gray-300"
                        />
                        <div v-if="form.errors.paid_at" class="mt-1 text-sm text-red-600">
                            {{ form.errors.paid_at }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Catatan
                        </label>
                        <textarea
                            v-model="form.note"
                            rows="4"
                            class="w-full rounded-lg border-gray-300"
                            placeholder="Contoh: Dibayar tunai setelah latihan"
                        />
                        <div v-if="form.errors.note" class="mt-1 text-sm text-red-600">
                            {{ form.errors.note }}
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
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>