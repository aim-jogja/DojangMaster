<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    dojangs: Array,
    payments: Object,
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

const formatLimit = (value) => {
    return value === null || value === undefined ? 'Unlimited' : value
}

const statusClass = (status) => {
    return {
        active: 'bg-green-100 text-green-700',
        free: 'bg-gray-100 text-gray-700',
        overdue: 'bg-orange-100 text-orange-700',
        expired: 'bg-red-100 text-red-700',
        cancelled: 'bg-gray-100 text-gray-700',

        pending: 'bg-yellow-100 text-yellow-700',
        paid: 'bg-green-100 text-green-700',
        failed: 'bg-red-100 text-red-700',
    }[status] ?? 'bg-gray-100 text-gray-700'
}
</script>

<template>
    <Head title="Billing" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Billing
                </h2>
                <p class="text-sm text-gray-500">
                    Informasi paket dan tagihan SaaS untuk semua dojang Anda.
                </p>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <!-- LIST DOJANG OWNER -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Paket Aktif Dojang
                    </h3>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div
                            v-for="dojang in dojangs"
                            :key="dojang.id"
                            class="rounded-xl bg-white p-6 shadow-sm"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ dojang.name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ dojang.address ?? '-' }}
                                    </p>
                                </div>

                                <span
                                    class="rounded-full px-3 py-1 text-sm font-medium capitalize"
                                    :class="statusClass(dojang.subscription?.status)"
                                >
                                    {{ dojang.subscription?.status ?? 'free' }}
                                </span>
                            </div>

                            <div class="mt-6">
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ dojang.subscription?.plan?.name ?? 'Free' }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    {{ dojang.subscription?.plan?.description ?? '-' }}
                                </div>

                                <div class="mt-3 text-lg font-semibold text-indigo-600">
                                    {{ formatCurrency(dojang.subscription?.plan?.price ?? 0) }}
                                    <span class="text-sm font-normal text-gray-500">
                                        / bulan
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-3">
                                <div class="rounded-lg border p-4">
                                    <div class="text-sm text-gray-500">
                                        Max Trainer
                                    </div>
                                    <div class="mt-1 text-xl font-semibold text-gray-900">
                                        {{ formatLimit(dojang.subscription?.plan?.max_trainers) }}
                                    </div>
                                </div>

                                <div class="rounded-lg border p-4">
                                    <div class="text-sm text-gray-500">
                                        Max Student
                                    </div>
                                    <div class="mt-1 text-xl font-semibold text-gray-900">
                                        {{ formatLimit(dojang.subscription?.plan?.max_students) }}
                                    </div>
                                </div>

                                <div class="rounded-lg border p-4">
                                    <div class="text-sm text-gray-500">
                                        Max Room
                                    </div>
                                    <div class="mt-1 text-xl font-semibold text-gray-900">
                                        {{ formatLimit(dojang.subscription?.plan?.max_rooms) }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-sm font-semibold text-gray-900">
                                    Fitur Paket
                                </h4>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span
                                        v-for="feature in dojang.subscription?.plan?.features ?? []"
                                        :key="feature.id"
                                        class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700"
                                    >
                                        {{ feature.name }}
                                    </span>

                                    <span
                                        v-if="!dojang.subscription?.plan?.features?.length"
                                        class="text-sm text-gray-500"
                                    >
                                        Tidak ada fitur aktif.
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6 border-t pt-4 text-sm text-gray-600">
                                <div>
                                    Mulai:
                                    <span class="font-medium text-gray-900">
                                        {{ formatDate(dojang.subscription?.started_at) }}
                                    </span>
                                </div>

                                <div>
                                    Berakhir:
                                    <span class="font-medium text-gray-900">
                                        {{ formatDate(dojang.subscription?.expired_at) }}
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="dojang.subscription?.status === 'free'"
                                class="mt-6 rounded-lg bg-gray-50 p-4 text-sm text-gray-600"
                            >
                                Dojang ini sedang menggunakan paket Free. Hubungi admin untuk upgrade paket.
                            </div>

                            <div
                                v-if="dojang.subscription?.status === 'overdue'"
                                class="mt-6 rounded-lg bg-orange-50 p-4 text-sm text-orange-700"
                            >
                                Tagihan dojang ini sudah melewati jatuh tempo. Segera lakukan pembayaran agar paket tidak kembali ke Free.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIWAYAT TAGIHAN -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Riwayat Tagihan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Semua tagihan SaaS dari dojang yang Anda miliki.
                        </p>
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
                                        <div class="font-medium text-gray-900">
                                            {{ payment.invoice_number }}
                                        </div>

                                        <div class="mt-1 text-xs text-gray-500">
                                            Paid at: {{ formatDate(payment.paid_at) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ payment.dojang?.name ?? '-' }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ payment.dojang?.address ?? '-' }}
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
                                        <Link
                                            :href="route('owner.billing.show', payment.id)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Detail
                                        </Link>
                                    </td>
                                </tr>

                                <tr v-if="payments.data.length === 0">
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada tagihan.
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