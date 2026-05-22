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
        unpaid: 'bg-red-50 text-red-700 border-red-100',
        partial: 'bg-amber-50 text-amber-700 border-amber-100',
        paid: 'bg-green-50 text-green-700 border-green-100',
        cancelled: 'bg-gray-50 text-gray-600 border-gray-100',
        expired: 'bg-orange-50 text-orange-700 border-orange-100',
    }[status] || 'bg-gray-50 text-gray-600 border-gray-100';
};

const statusDotClass = (status) => {
    return {
        unpaid: 'bg-red-500',
        partial: 'bg-amber-500',
        paid: 'bg-green-500',
        cancelled: 'bg-gray-400',
        expired: 'bg-orange-500',
    }[status] || 'bg-gray-400';
};
</script>

<template>
    <Head title="Detail Tagihan Siswa" />

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
                        d="M9 14.25l6-6m-5.5.5h.01m4.99 4.99h.01M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Detail Tagihan Siswa
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
                        {{ bill.invoice_number }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900 truncate">
                        {{ bill.title }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ bill.category?.name || 'Tanpa kategori' }}
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="statusClass(bill.status)"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="statusDotClass(bill.status)"
                    ></span>

                    {{ statuses[bill.status] || bill.status }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Informasi Tagihan
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Detail invoice, siswa, room, nominal, dan jatuh tempo
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
                                    Periode
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ bill.period || '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Jatuh Tempo
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ bill.due_date || '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nominal Tagihan
                                </dt>
                                <dd class="mt-1 text-lg font-bold text-gray-900">
                                    {{ formatCurrency(bill.amount) }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </dt>
                                <dd class="mt-2">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium"
                                        :class="statusClass(bill.status)"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="statusDotClass(bill.status)"
                                        ></span>

                                        {{ statuses[bill.status] || bill.status }}
                                    </span>
                                </dd>
                            </div>

                            <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Deskripsi
                                </dt>
                                <dd class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-900">
                                    {{ bill.description || '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Pembayaran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Informasi nominal dan status tagihan
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400">
                                Total Tagihan
                            </p>
                            <p class="mt-1 text-2xl font-bold text-indigo-700">
                                {{ formatCurrency(bill.amount) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Invoice
                            </p>
                            <p class="mt-1 break-all font-mono text-xs font-semibold text-gray-900">
                                {{ bill.invoice_number }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Kategori
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ bill.category?.name || 'Tanpa kategori' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="bill.payments?.length"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Riwayat Pembayaran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Pembayaran yang terhubung dengan tagihan ini
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ bill.payments.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Tanggal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nominal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Metode
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="payment in bill.payments"
                                :key="payment.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4 text-gray-700">
                                    {{ payment.paid_at || payment.created_at || '-' }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ formatCurrency(payment.amount) }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ payment.method || '-' }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ payment.status || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('student-bills.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('student-bills.edit', bill.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>