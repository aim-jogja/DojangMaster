<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    payment: Object,
    paymentMethods: Object,
});

const form = useForm({
    amount: props.payment.amount,
    payment_method: props.payment.payment_method,
    paid_at: props.payment.paid_at ? props.payment.paid_at.slice(0, 10) : '',
    note: props.payment.note || '',
});

const submit = () => {
    form.put(route('student-payments.update', props.payment.id));
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
    <Head title="Edit Pembayaran Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Edit Pembayaran Siswa
                </h2>

                <Link
                    :href="route('student-bills.show', payment.bill.id)"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <!-- Payment Info -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Pembayaran
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Invoice</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.bill?.invoice_number || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Tagihan</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.bill?.title || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Siswa</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.student?.name || '-' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ payment.student?.email || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Dojang</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.dojang?.name || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Nominal Tagihan</div>
                            <div class="mt-1 font-semibold text-gray-900">
                                {{ formatCurrency(payment.bill?.amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Status Pembayaran</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.status }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
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
                            class="w-full rounded-lg border-gray-300"
                        />
                        <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                            {{ form.errors.amount }}
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
                        />
                        <div v-if="form.errors.note" class="mt-1 text-sm text-red-600">
                            {{ form.errors.note }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            :href="route('student-bills.show', payment.bill.id)"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Update Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>