<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    payment: Object,
})

const paidForm = useForm({
    payment_method: props.payment.payment_method ?? 'manual',
    note: props.payment.note ?? '',
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
        pending: 'bg-amber-50 text-amber-700 border-amber-100',
        overdue: 'bg-orange-50 text-orange-700 border-orange-100',
        paid: 'bg-green-50 text-green-700 border-green-100',
        expired: 'bg-red-50 text-red-700 border-red-100',
        failed: 'bg-red-50 text-red-700 border-red-100',
        cancelled: 'bg-gray-50 text-gray-600 border-gray-100',
    }[status] ?? 'bg-gray-50 text-gray-600 border-gray-100'
}

const statusDotClass = (status) => {
    return {
        pending: 'bg-amber-500',
        overdue: 'bg-orange-500',
        paid: 'bg-green-500',
        expired: 'bg-red-500',
        failed: 'bg-red-500',
        cancelled: 'bg-gray-400',
    }[status] ?? 'bg-gray-400'
}

const markAsPaid = () => {
    if (confirm(`Tandai invoice ${props.payment.invoice_number} sebagai paid?`)) {
        paidForm.patch(route('dojang-payments.mark-as-paid', props.payment.id), {
            preserveScroll: true,
        })
    }
}

const markAsFailed = () => {
    if (confirm(`Tandai invoice ${props.payment.invoice_number} sebagai failed?`)) {
        router.patch(route('dojang-payments.mark-as-failed', props.payment.id), {}, {
            preserveScroll: true,
        })
    }
}

const cancelPayment = () => {
    if (confirm(`Batalkan invoice ${props.payment.invoice_number}?`)) {
        router.patch(route('dojang-payments.cancel', props.payment.id), {}, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <Head title="Detail Dojang Payment" />

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
                    Detail Dojang Payment
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-mono text-xs font-semibold text-indigo-700">
                        {{ payment.invoice_number }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900 truncate">
                        Detail Invoice Dojang
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Detail invoice subscription SaaS dojang
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border capitalize"
                        :class="statusClass(payment.status)"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full"
                            :class="statusDotClass(payment.status)"
                        ></span>

                        {{ payment.status }}
                    </span>

                    <Link
                        :href="route('dojang-payments.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Kembali
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Informasi Invoice
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Ringkasan invoice, periode, dan nominal tagihan
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                            {{ payment.dojang?.name?.charAt(0) ?? 'D' }}
                        </div>
                    </div>

                    <div class="p-5">
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Invoice Number
                                </dt>
                                <dd class="mt-1 font-mono text-xs font-semibold text-indigo-700">
                                    {{ payment.invoice_number }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-indigo-400">
                                    Nominal
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-indigo-700">
                                    {{ formatCurrency(payment.amount) }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Periode
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ formatDate(payment.period_start) }}
                                    -
                                    {{ formatDate(payment.period_end) }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </dt>
                                <dd class="mt-2">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium capitalize"
                                        :class="statusClass(payment.status)"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="statusDotClass(payment.status)"
                                        ></span>

                                        {{ payment.status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Timeline Pembayaran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Informasi jatuh tempo dan pembayaran
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Due Date
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ formatDate(payment.due_date) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-orange-100 bg-orange-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-orange-400">
                                Overdue At
                            </p>
                            <p class="mt-1 text-sm font-semibold text-orange-700">
                                {{ formatDate(payment.overdue_at) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-green-100 bg-green-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-green-400">
                                Paid At
                            </p>
                            <p class="mt-1 text-sm font-semibold text-green-700">
                                {{ formatDate(payment.paid_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Dojang
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail dojang dan owner
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Dojang
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ payment.dojang?.name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Owner
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ payment.dojang?.owner?.name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Alamat
                            </p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ payment.dojang?.address ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Telepon
                            </p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ payment.dojang?.phone ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Subscription
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail plan subscription yang ditagihkan
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Plan
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ payment.plan?.name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Code Plan
                            </p>
                            <p class="mt-1 font-mono text-xs font-semibold text-indigo-700">
                                {{ payment.plan?.code ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Payment Method
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 capitalize">
                                {{ payment.payment_method ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="payment.status !== 'paid'"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Konfirmasi Pembayaran
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Jika owner sudah membayar manual, Super Admin dapat menandai invoice ini sebagai paid
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Payment Method
                            </label>

                            <select
                                v-model="paidForm.payment_method"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="manual">Manual</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="other">Other</option>
                            </select>

                            <p v-if="paidForm.errors.payment_method" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ paidForm.errors.payment_method }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Note
                            </label>

                            <input
                                v-model="paidForm.note"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Catatan pembayaran"
                            />

                            <p v-if="paidForm.errors.note" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ paidForm.errors.note }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg border border-red-100 hover:bg-red-100 transition-colors"
                        @click="markAsFailed"
                    >
                        Mark Failed
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                        @click="cancelPayment"
                    >
                        Cancel Invoice
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-green-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="paidForm.processing"
                        @click="markAsPaid"
                    >
                        {{ paidForm.processing ? 'Processing...' : 'Mark As Paid' }}
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Catatan
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Catatan tambahan pada invoice ini
                    </p>
                </div>

                <div class="p-5">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="whitespace-pre-line text-sm font-medium leading-6 text-gray-900">
                            {{ payment.note || 'Tidak ada catatan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>