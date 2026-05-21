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
        paid: 'bg-green-100 text-green-700',
        cancelled: 'bg-gray-100 text-gray-700',
        refunded: 'bg-orange-100 text-orange-700',
    }[status] || 'bg-gray-100 text-gray-700';
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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detail Pembayaran Siswa
                </h2>

                <Link
                    :href="route('student-bills.show', payment.bill.id)"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali ke Tagihan
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="rounded-lg bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="rounded-lg bg-red-100 px-4 py-3 text-red-700"
                >
                    {{ page.props.flash.error }}
                </div>

                <!-- Header Card -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm text-gray-500">
                                Pembayaran untuk Invoice
                            </div>
                            <h3 class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ payment.bill?.invoice_number || '-' }}
                            </h3>
                            <div class="mt-1 text-sm text-gray-500">
                                {{ payment.bill?.title || '-' }}
                            </div>
                        </div>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium"
                            :class="statusClass(payment.status)"
                        >
                            {{ statuses?.[payment.status] || payment.status }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Nominal Pembayaran
                            </div>
                            <div class="mt-1 text-2xl font-bold text-gray-900">
                                {{ formatCurrency(payment.amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Metode Pembayaran
                            </div>
                            <div class="mt-1 text-gray-900">
                                {{ paymentMethods[payment.payment_method] || payment.payment_method }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Tanggal Bayar
                            </div>
                            <div class="mt-1 text-gray-900">
                                {{ payment.paid_at || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Diterima Oleh
                            </div>
                            <div class="mt-1 text-gray-900">
                                {{ payment.receiver?.name || '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="text-sm font-medium text-gray-500">
                            Catatan
                        </div>
                        <div class="mt-1 whitespace-pre-line text-gray-900">
                            {{ payment.note || '-' }}
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <Link
                            v-if="payment.status === 'paid'"
                            :href="route('student-payments.edit', payment.id)"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            Edit Pembayaran
                        </Link>

                        <button
                            v-if="payment.status === 'paid'"
                            type="button"
                            @click="cancelPayment"
                            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700"
                        >
                            Batalkan
                        </button>

                        <button
                            type="button"
                            @click="deletePayment"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- Student Info -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Siswa
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nama Siswa</div>
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
                    </div>
                </div>

                <!-- Bill Info -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Tagihan
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Judul Tagihan</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.bill?.title || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Invoice</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.bill?.invoice_number || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Nominal Tagihan</div>
                            <div class="mt-1 font-semibold text-gray-900">
                                {{ formatCurrency(payment.bill?.amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Sudah Dibayar</div>
                            <div class="mt-1 font-semibold text-gray-900">
                                {{ formatCurrency(payment.bill?.paid_amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Sisa Tagihan</div>
                            <div class="mt-1 font-semibold text-red-700">
                                {{ formatCurrency(payment.bill?.remaining_amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Status Tagihan</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ payment.bill?.status || '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <Link
                            :href="route('student-bills.show', payment.bill.id)"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        >
                            Lihat detail tagihan
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>