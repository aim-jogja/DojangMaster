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
            <div class="flex items-center gap-2 min-w-0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-indigo-500 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Edit Pembayaran Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-mono text-xs font-semibold text-indigo-700">
                        {{ payment.bill?.invoice_number || '-' }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900 truncate">
                        Edit Pembayaran
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Perbarui nominal, metode, tanggal, dan catatan pembayaran
                    </p>
                </div>

                <Link
                    :href="route('student-bills.show', payment.bill.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    Kembali
                </Link>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Pembayaran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Ringkasan pembayaran yang sedang diperbarui
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ payment.student?.name?.charAt(0) ?? 'S' }}
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Tagihan
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ payment.bill?.title || '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Siswa
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ payment.student?.name || '-' }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ payment.student?.email || '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nominal Tagihan
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ formatCurrency(payment.bill?.amount) }}
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Edit Pembayaran
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sesuaikan data pembayaran siswa
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nominal Pembayaran
                            </label>

                            <input
                                v-model="form.amount"
                                type="number"
                                min="1"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.amount" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.amount }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Metode Pembayaran
                            </label>

                            <select
                                v-model="form.payment_method"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option
                                    v-for="(label, value) in paymentMethods"
                                    :key="value"
                                    :value="value"
                                >
                                    {{ label }}
                                </option>
                            </select>

                            <div v-if="form.errors.payment_method" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.payment_method }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Tanggal Bayar
                            </label>

                            <input
                                v-model="form.paid_at"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <div v-if="form.errors.paid_at" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.paid_at }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Catatan
                        </label>

                        <textarea
                            v-model="form.note"
                            rows="4"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />

                        <div v-if="form.errors.note" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.note }}
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('student-bills.show', payment.bill.id)"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Mengupdate...' : 'Update Pembayaran' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>