<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    payments: Object,
    filters: Object,
    dojangs: Array,
    students: Array,
    paymentMethods: Object,
    statuses: Object,
});

const page = usePage();

const form = reactive({
    search: props.filters?.search || '',
    dojang_id: props.filters?.dojang_id || '',
    student_id: props.filters?.student_id || '',
    payment_method: props.filters?.payment_method || '',
    status: props.filters?.status || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
});

const applyFilter = () => {
    router.get(route('student-payments.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilter = () => {
    router.get(route('student-payments.index'));
};

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

const cancelPayment = (payment) => {
    if (!confirm('Batalkan pembayaran ini? Status tagihan akan dihitung ulang.')) return;

    router.patch(route('student-payments.cancel', payment.id), {}, {
        preserveScroll: true,
    });
};

const deletePayment = (payment) => {
    if (!confirm('Hapus pembayaran ini secara permanen? Status tagihan akan dihitung ulang.')) return;

    router.delete(route('student-payments.destroy', payment.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pembayaran Siswa" />

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
                    Pembayaran Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Pembayaran Siswa
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola pembayaran siswa berdasarkan invoice, metode bayar, tanggal, dan status transaksi
                    </p>
                </div>
            </div>

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

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Filter Pembayaran
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Cari pembayaran berdasarkan invoice, siswa, dojang, metode, status, atau rentang tanggal
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Cari
                            </label>

                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Invoice / nama siswa / tagihan"
                                @keyup.enter="applyFilter"
                            />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dojang
                            </label>

                            <select
                                v-model="form.dojang_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Dojang</option>
                                <option
                                    v-for="dojang in dojangs"
                                    :key="dojang.id"
                                    :value="dojang.id"
                                >
                                    {{ dojang.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Siswa
                            </label>

                            <select
                                v-model="form.student_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Siswa</option>
                                <option
                                    v-for="student in students"
                                    :key="student.id"
                                    :value="student.id"
                                >
                                    {{ student.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Metode
                            </label>

                            <select
                                v-model="form.payment_method"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Metode</option>
                                <option
                                    v-for="(label, value) in paymentMethods"
                                    :key="value"
                                    :value="value"
                                >
                                    {{ label }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Status
                            </label>

                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Status</option>
                                <option
                                    v-for="(label, value) in statuses"
                                    :key="value"
                                    :value="value"
                                >
                                    {{ label }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dari Tanggal
                            </label>

                            <input
                                v-model="form.date_from"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Sampai Tanggal
                            </label>

                            <input
                                v-model="form.date_to"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="button"
                                @click="resetFilter"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                            >
                                Reset
                            </button>

                            <button
                                type="button"
                                @click="applyFilter"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-95"
                            >
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Riwayat Pembayaran
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan transaksi pembayaran yang tercatat di sistem
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
                                    Siswa
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Pembayaran
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Tanggal
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
                                    <div class="min-w-[160px]">
                                        <Link
                                            :href="route('student-payments.show', payment.id)"
                                            class="font-mono text-xs font-semibold text-indigo-700 hover:underline"
                                        >
                                            {{ payment.bill?.invoice_number || '-' }}
                                        </Link>
                                        <p class="mt-1 text-xs text-gray-400 truncate">
                                            {{ payment.bill?.title || '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ payment.student?.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ payment.student?.name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                {{ payment.student?.email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="min-w-[160px]">
                                        <p class="font-semibold text-gray-900">
                                            {{ formatCurrency(payment.amount) }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ paymentMethods[payment.payment_method] || payment.payment_method }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full border border-gray-100 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ payment.paid_at || '-' }}
                                    </span>
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

                                        {{ statuses[payment.status] || payment.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('student-payments.show', payment.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            v-if="payment.status === 'paid'"
                                            :href="route('student-payments.edit', payment.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="payment.status === 'paid'"
                                            type="button"
                                            @click="cancelPayment(payment)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-orange-600 bg-orange-50 border border-orange-100 rounded-lg hover:bg-orange-100 transition-colors"
                                        >
                                            Batalkan
                                        </button>

                                        <button
                                            type="button"
                                            @click="deletePayment(payment)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="payments.data.length === 0">
                                <td colspan="6" class="py-16 text-center">
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
                                            Belum ada data pembayaran
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