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
            onFinish: () => {
                paidForm.reset('note')
                paidForm.payment_method = 'manual'
            },
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
    <Head title="Dojang Payments" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dojang Payments
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola tagihan bulanan SaaS untuk semua dojang.
                </p>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Generate Monthly Payment
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Sistem akan membuat tagihan untuk semua dojang aktif yang memakai paket berbayar.
                    </p>

                    <form
                        class="mt-5 grid gap-4 md:grid-cols-4"
                        @submit.prevent="generateMonthly"
                    >
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Periode
                            </label>
                            <input
                                v-model="generateForm.period"
                                type="month"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p v-if="generateForm.errors.period" class="mt-1 text-sm text-red-600">
                                {{ generateForm.errors.period }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Due Day
                            </label>
                            <input
                                v-model="generateForm.due_day"
                                type="number"
                                min="1"
                                max="28"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p class="mt-1 text-xs text-gray-500">
                                Contoh: 5 berarti jatuh tempo tanggal 5.
                            </p>
                            <p v-if="generateForm.errors.due_day" class="mt-1 text-sm text-red-600">
                                {{ generateForm.errors.due_day }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Overdue Day
                            </label>
                            <input
                                v-model="generateForm.overdue_day"
                                type="number"
                                min="1"
                                max="28"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p class="mt-1 text-xs text-gray-500">
                                Jika lewat tanggal ini, dojang kembali ke Free.
                            </p>
                            <p v-if="generateForm.errors.overdue_day" class="mt-1 text-sm text-red-600">
                                {{ generateForm.errors.overdue_day }}
                            </p>
                        </div>

                        <div class="flex items-end">
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                                :disabled="generateForm.processing"
                            >
                                Generate
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Daftar Tagihan Dojang
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Invoice
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Dojang
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Plan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Nominal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="payment in payments.data"
                                    :key="payment.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">
                                        <Link
                                            :href="route('dojang-payments.show', payment.id)"
                                            class="font-medium text-indigo-600 hover:text-indigo-900"
                                        >
                                            {{ payment.invoice_number }}
                                        </Link>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Paid at: {{ formatDate(payment.paid_at) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ payment.dojang?.name ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Owner: {{ payment.dojang?.owner?.name ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ payment.plan?.name ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ payment.plan?.code ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div>
                                            {{ formatDate(payment.period_start) }}
                                            -
                                            {{ formatDate(payment.period_end) }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Due: {{ formatDate(payment.due_date) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Overdue: {{ formatDate(payment.overdue_at) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ formatCurrency(payment.amount) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-medium capitalize"
                                            :class="statusClass(payment.status)"
                                        >
                                            {{ payment.status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="route('dojang-payments.show', payment.id)"
                                                class="text-gray-600 hover:text-gray-900"
                                            >
                                                Detail
                                            </Link>

                                            <button
                                                v-if="payment.status !== 'paid'"
                                                type="button"
                                                class="text-green-600 hover:text-green-900"
                                                @click="markAsPaid(payment)"
                                            >
                                                Paid
                                            </button>

                                            <button
                                                v-if="!['paid', 'failed', 'cancelled'].includes(payment.status)"
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="markAsFailed(payment)"
                                            >
                                                Failed
                                            </button>

                                            <button
                                                v-if="!['paid', 'cancelled'].includes(payment.status)"
                                                type="button"
                                                class="text-gray-600 hover:text-gray-900"
                                                @click="cancelPayment(payment)"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="payments.data.length === 0">
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada tagihan dojang.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="payments.links"
                        class="border-t px-6 py-4"
                    >
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="link in payments.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                v-html="link.label"
                                class="rounded border px-3 py-1 text-sm"
                                :class="[
                                    link.active
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-white text-gray-700 hover:bg-gray-50',
                                    !link.url ? 'cursor-not-allowed opacity-50' : ''
                                ]"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>