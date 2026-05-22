<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    payment: Object,
    paymentMethods: Object,
    statuses: Object,
});

const page = usePage();

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value || 0);
};

const statusClass = (status) => {
    return {
        paid: 'bg-green-50 text-green-700 border-green-100',
        cancelled: 'bg-gray-50 text-gray-600 border-gray-100',
        refunded: 'bg-orange-50 text-orange-700 border-orange-100',
    }[status] || 'bg-gray-50 text-gray-600 border-gray-100';
};

const statusDotClass = (status) => {
    return {
        paid: 'bg-green-500',
        cancelled: 'bg-gray-400',
        refunded: 'bg-orange-500',
    }[status] || 'bg-gray-400';
};

const cancelPayment = () => {
    if (!confirm('Batalkan pembayaran ini? Status tagihan akan dihitung ulang.')) return;

    router.patch(route('student-payments.cancel', props.payment.id), {}, {
        preserveScroll: true,
    });
};

const deletePayment = () => {
    if (!confirm('Hapus pembayaran ini secara permanen? Status tagihan akan dihitung ulang.')) return;

    router.delete(route('student-payments.destroy', props.payment.id));
};
</script>

<template>
    <Head title="Detail Pembayaran Siswa" />

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
                    Detail Pembayaran Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="page.props.flash?.error"
                class="bg-red-50 border border-red-100 text-red-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.error }}
            </div>

            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-mono text-xs font-semibold text-indigo-700">
                        {{ payment.bill?.invoice_number || '-' }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900 truncate">
                        Detail Pembayaran
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ payment.bill?.title || '-' }}
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="statusClass(payment.status)"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="statusDotClass(payment.status)"
                    ></span>

                    {{ statuses?.[payment.status] || payment.status }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Informasi Pembayaran
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Detail nominal, metode, tanggal, dan penerima pembayaran
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                            {{ payment.student?.name?.charAt(0) ?? 'S' }}
                        </div>
                    </div>

                    <div class="p-5">
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-indigo-400">
                                    Nominal Pembayaran
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-indigo-700">
                                    {{ formatCurrency(payment.amount) }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Metode Pembayaran
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ paymentMethods[payment.payment_method] || payment.payment_method }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Tanggal Bayar
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ payment.paid_at || '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Diterima Oleh
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ payment.receiver?.name || '-' }}
                                </dd>
                            </div>

                            <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Catatan
                                </dt>
                                <dd class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-900">
                                    {{ payment.note || '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                        <Link
                            v-if="payment.status === 'paid'"
                            :href="route('student-payments.edit', payment.id)"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                        >
                            Edit Pembayaran
                        </Link>

                        <button
                            v-if="payment.status === 'paid'"
                            type="button"
                            @click="cancelPayment"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-700 text-sm font-medium rounded-lg border border-orange-100 hover:bg-orange-100 transition-colors"
                        >
                            Batalkan
                        </button>

                        <button
                            type="button"
                            @click="deletePayment"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg border border-red-100 hover:bg-red-100 transition-colors"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Tagihan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Status tagihan setelah pembayaran
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nominal Tagihan
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ formatCurrency(payment.bill?.amount) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-green-100 bg-green-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-green-400">
                                Sudah Dibayar
                            </p>
                            <p class="mt-1 text-lg font-bold text-green-700">
                                {{ formatCurrency(payment.bill?.paid_amount) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-red-100 bg-red-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-red-400">
                                Sisa Tagihan
                            </p>
                            <p class="mt-1 text-lg font-bold text-red-700">
                                {{ formatCurrency(payment.bill?.remaining_amount) }}
                            </p>
                        </div>

                        <Link
                            :href="route('student-bills.show', payment.bill.id)"
                            class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 bg-white text-indigo-700 text-sm font-medium rounded-lg border border-indigo-100 hover:bg-indigo-50 transition-colors"
                        >
                            Lihat Detail Tagihan
                        </Link>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Informasi Siswa
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Data siswa yang melakukan pembayaran
                    </p>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nama Siswa
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ payment.student?.name || '-' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ payment.student?.email || '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ payment.dojang?.name || '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('student-bills.show', payment.bill.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali ke Tagihan
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>