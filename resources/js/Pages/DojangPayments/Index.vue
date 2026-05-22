<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'

defineProps({
    payments: Object,
})

const generateForm = useForm({
    period: new Date().toISOString().slice(0, 7),
    due_day: 5,
    overdue_day: 10,
})

const paidForm = useForm({
    payment_method: 'manual',
    note: '',
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
        month: 'short',
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

const generateMonthly = () => {
    if (confirm('Generate tagihan bulanan untuk semua dojang aktif?')) {
        generateForm.post(route('dojang-payments.generate-monthly'), {
            preserveScroll: true,
        })
    }
}

const markAsPaid = (payment) => {
    if (confirm(`Tandai invoice ${payment.invoice_number} sebagai paid?`)) {
        paidForm.patch(route('dojang-payments.mark-as-paid', payment.id), {
            preserveScroll: true,
        })
    }
}

const markAsFailed = (payment) => {
    if (confirm(`Tandai invoice ${payment.invoice_number} sebagai failed?`)) {
        router.patch(route('dojang-payments.mark-as-failed', payment.id), {}, {
            preserveScroll: true,
        })
    }
}

const cancelPayment = (payment) => {
    if (confirm(`Batalkan invoice ${payment.invoice_number}?`)) {
        router.patch(route('dojang-payments.cancel', payment.id), {}, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <Head title="Dojang Payments" />

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
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.75 9.75h.008v.008H6.75V9.75zm3 0h.008v.008H9.75V9.75z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Dojang Payments
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tagihan Dojang
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola invoice subscription SaaS untuk semua dojang aktif
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Generate Tagihan Bulanan
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Buat tagihan bulanan untuk seluruh dojang aktif berdasarkan subscription plan
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Periode
                            </label>

                            <input
                                v-model="generateForm.period"
                                type="month"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p v-if="generateForm.errors.period" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ generateForm.errors.period }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Due Day
                            </label>

                            <input
                                v-model="generateForm.due_day"
                                type="number"
                                min="1"
                                max="31"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p v-if="generateForm.errors.due_day" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ generateForm.errors.due_day }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Overdue Day
                            </label>

                            <input
                                v-model="generateForm.overdue_day"
                                type="number"
                                min="1"
                                max="31"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p v-if="generateForm.errors.overdue_day" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ generateForm.errors.overdue_day }}
                            </p>
                        </div>

                        <div class="flex items-end">
                            <button
                                type="button"
                                @click="generateMonthly"
                                :disabled="generateForm.processing"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {{ generateForm.processing ? 'Generating...' : 'Generate' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Invoice Dojang
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan riwayat tagihan subscription dojang
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ payments.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Invoice
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Plan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Periode
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nominal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="payment in payments.data"
                                :key="payment.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="min-w-[170px]">
                                        <Link
                                            :href="route('dojang-payments.show', payment.id)"
                                            class="font-mono text-xs font-semibold text-indigo-700 hover:underline"
                                        >
                                            {{ payment.invoice_number }}
                                        </Link>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Paid at: {{ formatDate(payment.paid_at) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[220px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ payment.dojang?.name?.charAt(0) ?? 'D' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ payment.dojang?.name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                Owner: {{ payment.dojang?.owner?.name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="min-w-[140px]">
                                        <p class="font-semibold text-gray-900 truncate">
                                            {{ payment.plan?.name ?? '-' }}
                                        </p>

                                        <p class="mt-1 font-mono text-xs font-semibold text-gray-400">
                                            {{ payment.plan?.code ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="min-w-[190px]">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ formatDate(payment.period_start) }}
                                            -
                                            {{ formatDate(payment.period_end) }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            Due: {{ formatDate(payment.due_date) }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Overdue: {{ formatDate(payment.overdue_at) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ formatCurrency(payment.amount) }}
                                </td>

                                <td class="px-5 py-4">
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
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('dojang-payments.show', payment.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <button
                                            v-if="payment.status !== 'paid'"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-100 rounded-lg hover:bg-green-100 transition-colors"
                                            @click="markAsPaid(payment)"
                                        >
                                            Paid
                                        </button>

                                        <button
                                            v-if="!['paid', 'failed', 'cancelled'].includes(payment.status)"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                            @click="markAsFailed(payment)"
                                        >
                                            Failed
                                        </button>

                                        <button
                                            v-if="!['paid', 'cancelled'].includes(payment.status)"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                            @click="cancelPayment(payment)"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="payments.data.length === 0">
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-10 h-10 text-gray-300"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 18.75h19.5M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5H3V6.75z"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada tagihan dojang
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="payments.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ payments.from }}–{{ payments.to }} dari {{ payments.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in payments.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 text-xs rounded-lg border transition-colors',
                                    link.active
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                                ]"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 text-xs rounded-lg border border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>