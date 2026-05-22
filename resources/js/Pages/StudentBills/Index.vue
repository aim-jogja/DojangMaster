<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    bills: Object,
    filters: Object,
    dojangs: Array,
    rooms: Array,
    categories: Array,
    statuses: Object,
});

const page = usePage();

const form = reactive({
    search: props.filters?.search || '',
    dojang_id: props.filters?.dojang_id || '',
    room_id: props.filters?.room_id || '',
    payment_category_id: props.filters?.payment_category_id || '',
    period: props.filters?.period || '',
    status: props.filters?.status || '',
});

const applyFilter = () => {
    router.get(route('student-bills.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilter = () => {
    router.get(route('student-bills.index'));
};

const destroyBill = (bill) => {
    if (!confirm(`Hapus tagihan "${bill.title}" milik ${bill.student?.name}?`)) return;

    router.delete(route('student-bills.destroy', bill.id), {
        preserveScroll: true,
    });
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
    <Head title="Tagihan Siswa" />

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
                    Tagihan Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Tagihan Siswa
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola tagihan siswa berdasarkan dojang, room, kategori, periode, dan status pembayaran
                    </p>
                </div>

                <Link
                    :href="route('student-bills.create')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>

                    Generate Tagihan
                </Link>
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
                        Filter Tagihan
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Gunakan filter untuk mempersempit data tagihan yang ditampilkan
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-6">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Cari
                            </label>

                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Siswa / invoice"
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
                                Room
                            </label>

                            <select
                                v-model="form.room_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Room</option>
                                <option
                                    v-for="room in rooms"
                                    :key="room.id"
                                    :value="room.id"
                                >
                                    {{ room.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Kategori
                            </label>

                            <select
                                v-model="form.payment_category_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Kategori</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Periode
                            </label>

                            <input
                                v-model="form.period"
                                type="month"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
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
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            @click="resetFilter"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                        >
                            Reset
                        </button>

                        <button
                            type="button"
                            @click="applyFilter"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-95"
                        >
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Tagihan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan tagihan siswa yang tercatat di sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ bills.data.length }} item
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
                                    Tagihan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nominal
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Jatuh Tempo
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
                                v-for="bill in bills.data"
                                :key="bill.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <Link
                                        :href="route('student-bills.show', bill.id)"
                                        class="font-mono text-xs font-semibold text-indigo-700 hover:underline"
                                    >
                                        {{ bill.invoice_number }}
                                    </Link>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ bill.student?.name?.charAt(0) ?? 'S' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ bill.student?.name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                {{ bill.student?.email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="min-w-[220px]">
                                        <p class="font-semibold text-gray-900 truncate">
                                            {{ bill.title }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5 truncate">
                                            {{ bill.category?.name || 'Tanpa kategori' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ formatCurrency(bill.amount) }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full border border-gray-100 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ bill.due_date ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium capitalize"
                                        :class="statusClass(bill.status)"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="statusDotClass(bill.status)"
                                        ></span>

                                        {{ statuses[bill.status] || bill.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('student-bills.show', bill.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            :href="route('student-bills.edit', bill.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="bill.status !== 'paid'"
                                            type="button"
                                            @click="destroyBill(bill)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="bills.data.length === 0">
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
                                                d="M9 14.25l6-6m-5.5.5h.01m4.99 4.99h.01M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada tagihan siswa
                                        </p>

                                        <Link
                                            :href="route('student-bills.create')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Generate tagihan pertama
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="bills.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ bills.from }}–{{ bills.to }} dari {{ bills.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in bills.links" :key="link.label">
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