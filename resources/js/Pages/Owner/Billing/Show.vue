<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    payment: Object,
    dojang: Object,
})

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value ?? 0)
}

const formatDate = (date) => {
    if (!date) return '-'

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(new Date(date))
}

const statusClass = (status) => {
    return {
        pending: 'bg-yellow-100 text-yellow-700',
        overdue: 'bg-orange-100 text-orange-700',
        paid: 'bg-green-100 text-green-700',
        expired: 'bg-red-100 text-red-700',
        failed: 'bg-red-100 text-red-700',
        cancelled: 'bg-gray-100 text-gray-700',
    }[status] ?? 'bg-gray-100 text-gray-700'
}
</script>

<template>
    <Head title="Detail Billing" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Detail Billing
                    </h2>
                    <p class="text-sm text-gray-500">
                        Detail tagihan SaaS dojang Anda.
                    </p>
                </div>

                <Link
                    :href="route('owner.billing.index')"
                    class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">

                <!-- HEADER INVOICE -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                        <div>
                            <div class="text-sm text-gray-500">
                                Invoice Number
                            </div>

                            <h3 class="mt-1 text-2xl font-bold text-gray-900">
                                {{ payment.invoice_number }}
                            </h3>

                            <span
                                class="mt-3 inline-flex rounded-full px-3 py-1 text-sm font-medium capitalize"
                                :class="statusClass(payment.status)"
                            >
                                {{ payment.status }}
                            </span>
                        </div>

                        <div class="text-left md:text-right">
                            <div class="text-sm text-gray-500">
                                Total Tagihan
                            </div>

                            <div class="mt-1 text-2xl font-bold text-gray-900">
                                {{ formatCurrency(payment.amount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATUS NOTICE -->
                <div
                    v-if="payment.status === 'pending'"
                    class="rounded-xl border border-yellow-200 bg-yellow-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-yellow-900">
                        Menunggu Pembayaran
                    </h3>

                    <p class="mt-2 text-sm text-yellow-800">
                        Silakan lakukan pembayaran manual sesuai nominal tagihan, lalu hubungi Super Admin agar pembayaran dikonfirmasi.
                    </p>
                </div>

                <div
                    v-if="payment.status === 'overdue'"
                    class="rounded-xl border border-orange-200 bg-orange-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-orange-900">
                        Tagihan Terlambat
                    </h3>

                    <p class="mt-2 text-sm text-orange-800">
                        Tagihan sudah melewati jatuh tempo. Jika melewati batas overdue, paket dojang akan kembali ke Free.
                    </p>
                </div>

                <div
                    v-if="payment.status === 'paid'"
                    class="rounded-xl border border-green-200 bg-green-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-green-900">
                        Pembayaran Berhasil
                    </h3>

                    <p class="mt-2 text-sm text-green-800">
                        Pembayaran telah dikonfirmasi. Paket dojang Anda telah diperpanjang sesuai periode tagihan.
                    </p>
                </div>

                <div
                    v-if="payment.status === 'expired'"
                    class="rounded-xl border border-red-200 bg-red-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-red-900">
                        Tagihan Expired
                    </h3>

                    <p class="mt-2 text-sm text-red-800">
                        Tagihan ini sudah melewati batas overdue. Paket dojang dapat dikembalikan ke Free oleh sistem.
                    </p>
                </div>

                <div
                    v-if="payment.status === 'failed'"
                    class="rounded-xl border border-red-200 bg-red-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-red-900">
                        Pembayaran Gagal
                    </h3>

                    <p class="mt-2 text-sm text-red-800">
                        Pembayaran pada invoice ini ditandai gagal. Silakan hubungi Super Admin untuk informasi lebih lanjut.
                    </p>
                </div>

                <div
                    v-if="payment.status === 'cancelled'"
                    class="rounded-xl border border-gray-200 bg-gray-50 p-6"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        Invoice Dibatalkan
                    </h3>

                    <p class="mt-2 text-sm text-gray-700">
                        Invoice ini telah dibatalkan dan tidak perlu dibayarkan.
                    </p>
                </div>

                <!-- DETAIL GRID -->
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Dojang
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500">
                                    Nama Dojang
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ dojang.name }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Alamat
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ dojang.address ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Telepon
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ dojang.phone ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Domain
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ dojang.domain ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Invoice
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500">
                                    Plan
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ payment.plan?.name ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Code Plan
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ payment.plan?.code ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Periode
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.period_start) }}
                                    -
                                    {{ formatDate(payment.period_end) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Due Date
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.due_date) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Overdue At
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.overdue_at) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Payment Method
                                </div>

                                <div class="font-medium text-gray-900 capitalize">
                                    {{ payment.payment_method ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Paid At
                                </div>

                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.paid_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INSTRUKSI PEMBAYARAN -->
                <div
                    v-if="['pending', 'overdue'].includes(payment.status)"
                    class="rounded-xl bg-white p-6 shadow-sm"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        Instruksi Pembayaran
                    </h3>

                    <div class="mt-4 rounded-lg border bg-gray-50 p-4">
                        <p class="text-sm text-gray-700">
                            Untuk saat ini pembayaran dilakukan secara manual. Transfer sesuai nominal tagihan:
                        </p>

                        <div class="mt-3 text-2xl font-bold text-gray-900">
                            {{ formatCurrency(payment.amount) }}
                        </div>

                        <p class="mt-3 text-sm text-gray-700">
                            Setelah melakukan pembayaran, hubungi Super Admin dengan menyertakan invoice number:
                        </p>

                        <div class="mt-2 rounded bg-white px-3 py-2 font-mono text-sm text-gray-900">
                            {{ payment.invoice_number }}
                        </div>
                    </div>
                </div>

                <!-- CATATAN -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Catatan
                    </h3>

                    <p class="mt-3 whitespace-pre-line text-sm text-gray-700">
                        {{ payment.note || 'Tidak ada catatan.' }}
                    </p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>