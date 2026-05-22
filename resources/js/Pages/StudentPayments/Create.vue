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
                        d="M2.25 18.75h19.5M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5H3V6.75z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Tambah Pembayaran Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-mono text-xs font-semibold text-indigo-700">
                        {{ bill.invoice_number }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900 truncate">
                        Tambah Pembayaran
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Input pembayaran untuk tagihan siswa
                    </p>
                </div>

                <Link
                    :href="route('student-bills.show', bill.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    Kembali
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Informasi Tagihan
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Pastikan data tagihan sudah benar sebelum mencatat pembayaran
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                            {{ bill.student?.name?.charAt(0) ?? 'S' }}
                        </div>
                    </div>

                    <div class="p-5">
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Tagihan
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ bill.title }}
                                </dd>
                                <dd class="text-xs text-gray-400">
                                    {{ bill.category?.name || 'Tanpa kategori' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Siswa
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ bill.student?.name || '-' }}
                                </dd>
                                <dd class="text-xs text-gray-400">
                                    {{ bill.student?.email || '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang / Room
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ bill.dojang?.name || '-' }}
                                </dd>
                                <dd class="text-xs text-gray-400">
                                    {{ bill.room?.name || '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Sudah Dibayar
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ formatCurrency(bill.paid_amount) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Nominal
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Nominal tagihan dan sisa pembayaran
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Total Tagihan
                            </p>
                            <p class="mt-1 text-lg font-bold text-gray-900">
                                {{ formatCurrency(bill.amount) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-red-100 bg-red-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-red-400">
                                Sisa Tagihan
                            </p>
                            <p class="mt-1 text-2xl font-bold text-red-700">
                                {{ formatCurrency(bill.remaining_amount) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Pembayaran
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Masukkan nominal, metode, tanggal, dan catatan pembayaran
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
                                :max="bill.remaining_amount"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p class="mt-1.5 text-xs text-gray-400">
                                Maksimal: {{ formatCurrency(bill.remaining_amount) }}
                            </p>

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
                            placeholder="Contoh: Dibayar tunai setelah latihan"
                        />

                        <div v-if="form.errors.note" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.note }}
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('student-bills.show', bill.id)"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Pembayaran' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>