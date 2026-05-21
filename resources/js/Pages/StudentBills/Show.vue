<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    bill: Object,
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
        unpaid: 'bg-red-100 text-red-700',
        partial: 'bg-yellow-100 text-yellow-700',
        paid: 'bg-green-100 text-green-700',
        cancelled: 'bg-gray-100 text-gray-700',
        expired: 'bg-orange-100 text-orange-700',
    }[status] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Detail Tagihan Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detail Tagihan Siswa
                </h2>

                <Link
                    :href="route('student-bills.index')"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
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

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm text-gray-500">
                                {{ bill.invoice_number }}
                            </div>
                            <h3 class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ bill.title }}
                            </h3>
                            <div class="mt-2 text-sm text-gray-500">
                                {{ bill.category?.name || 'Tanpa kategori' }}
                            </div>
                        </div>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium"
                            :class="statusClass(bill.status)"
                        >
                            {{ statuses[bill.status] || bill.status }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Siswa</div>
                            <div class="mt-1 text-gray-900">
                                {{ bill.student?.name || '-' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ bill.student?.email || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Dojang / Room</div>
                            <div class="mt-1 text-gray-900">
                                {{ bill.dojang?.name || '-' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ bill.room?.name || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Periode</div>
                            <div class="mt-1 text-gray-900">
                                {{ bill.period || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Jatuh Tempo</div>
                            <div class="mt-1 text-gray-900">
                                {{ bill.due_date || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Nominal Tagihan</div>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                {{ formatCurrency(bill.amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Sisa Tagihan</div>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                {{ formatCurrency(bill.remaining_amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Sudah Dibayar</div>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                {{ formatCurrency(bill.paid_amount) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Dibuat Oleh</div>
                            <div class="mt-1 text-gray-900">
                                {{ bill.creator?.name || '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="text-sm font-medium text-gray-500">Deskripsi</div>
                        <div class="mt-1 whitespace-pre-line text-gray-900">
                            {{ bill.description || '-' }}
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <Link
                            v-if="bill.status !== 'paid'"
                            :href="route('student-bills.edit', bill.id)"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            Edit Tagihan
                        </Link>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Riwayat Pembayaran
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Daftar transaksi pembayaran untuk tagihan ini.
                            </p>
                        </div>

                        <Link
                            v-if="bill.status !== 'paid' && bill.status !== 'cancelled' && bill.status !== 'expired'"
                            :href="route('student-payments.create', bill.id)"
                            class="shrink-0 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            Tambah Pembayaran
                        </Link>
                    </div>

                    <div class="mt-4 overflow-hidden rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                        Nominal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                        Metode
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                        Penerima
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="payment in bill.payments"
                                    :key="payment.id"
                                >
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ payment.paid_at || '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ formatCurrency(payment.amount) }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ payment.payment_method }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ payment.status }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ payment.receiver?.name || '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-sm">
                                        <div class="flex justify-end gap-3">
                                            <Link
                                                :href="route('student-payments.show', payment.id)"
                                                class="text-blue-600 hover:text-blue-800"
                                            >
                                                Detail
                                            </Link>

                                            <Link
                                                v-if="payment.status === 'paid'"
                                                :href="route('student-payments.edit', payment.id)"
                                                class="text-indigo-600 hover:text-indigo-800"
                                            >
                                                Edit
                                            </Link>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="bill.payments.length === 0">
                                    <td
                                        colspan="6"
                                        class="px-4 py-6 text-center text-sm text-gray-500"
                                    >
                                        Belum ada pembayaran.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>