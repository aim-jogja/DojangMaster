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
        pending: 'bg-yellow-100 text-yellow-700',
        overdue: 'bg-orange-100 text-orange-700',
        paid: 'bg-green-100 text-green-700',
        expired: 'bg-red-100 text-red-700',
        failed: 'bg-red-100 text-red-700',
        cancelled: 'bg-gray-100 text-gray-700',
    }[status] ?? 'bg-gray-100 text-gray-700'
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
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Detail Dojang Payment
                    </h2>
                    <p class="text-sm text-gray-500">
                        Detail invoice subscription SaaS dojang.
                    </p>
                </div>

                <Link
                    :href="route('dojang-payments.index')"
                    class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
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
                                Nominal
                            </div>
                            <div class="mt-1 text-2xl font-bold text-gray-900">
                                {{ formatCurrency(payment.amount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Dojang
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500">Nama Dojang</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.dojang?.name ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Owner</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.dojang?.owner?.name ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Alamat</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.dojang?.address ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Telepon</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.dojang?.phone ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Subscription
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500">Plan</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.plan?.name ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Code Plan</div>
                                <div class="font-medium text-gray-900">
                                    {{ payment.plan?.code ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Periode</div>
                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.period_start) }}
                                    -
                                    {{ formatDate(payment.period_end) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Due Date</div>
                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.due_date) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Overdue At</div>
                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.overdue_at) }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Paid At</div>
                                <div class="font-medium text-gray-900">
                                    {{ formatDate(payment.paid_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="payment.status !== 'paid'"
                    class="rounded-xl bg-white p-6 shadow-sm"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        Konfirmasi Pembayaran
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Jika owner sudah membayar manual, Super Admin dapat menandai invoice ini sebagai paid.
                    </p>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Payment Method
                            </label>
                            <select
                                v-model="paidForm.payment_method"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="manual">Manual</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="other">Other</option>
                            </select>

                            <p v-if="paidForm.errors.payment_method" class="mt-1 text-sm text-red-600">
                                {{ paidForm.errors.payment_method }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Note
                            </label>
                            <input
                                v-model="paidForm.note"
                                type="text"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Catatan pembayaran"
                            />

                            <p v-if="paidForm.errors.note" class="mt-1 text-sm text-red-600">
                                {{ paidForm.errors.note }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap justify-end gap-2">
                        <button
                            type="button"
                            class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50"
                            @click="markAsFailed"
                        >
                            Mark Failed
                        </button>

                        <button
                            type="button"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            @click="cancelPayment"
                        >
                            Cancel Invoice
                        </button>

                        <button
                            type="button"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                            :disabled="paidForm.processing"
                            @click="markAsPaid"
                        >
                            Mark As Paid
                        </button>
                    </div>
                </div>

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