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
        unpaid: 'bg-red-100 text-red-700',
        partial: 'bg-yellow-100 text-yellow-700',
        paid: 'bg-green-100 text-green-700',
        cancelled: 'bg-gray-100 text-gray-700',
        expired: 'bg-orange-100 text-orange-700',
    }[status] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Tagihan Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tagihan Siswa
                </h2>

                <Link
                    :href="route('student-bills.create')"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Generate Tagihan
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
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

                <div class="rounded-xl bg-white p-4 shadow-sm">
                    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-6">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Cari
                            </label>
                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-300 text-sm"
                                placeholder="Siswa / invoice"
                                @keyup.enter="applyFilter"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Dojang
                            </label>
                            <select
                                v-model="form.dojang_id"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            >
                                <option value="">Semua</option>
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Room
                            </label>
                            <select
                                v-model="form.room_id"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            >
                                <option value="">Semua</option>
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Kategori
                            </label>
                            <select
                                v-model="form.payment_category_id"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            >
                                <option value="">Semua</option>
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Periode
                            </label>
                            <input
                                v-model="form.period"
                                type="month"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            >
                                <option value="">Semua</option>
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

                    <div class="mt-4 flex gap-2">
                        <button
                            type="button"
                            @click="applyFilter"
                            class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900"
                        >
                            Filter
                        </button>

                        <button
                            type="button"
                            @click="resetFilter"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Invoice
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Tagihan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Nominal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="bill in bills.data"
                                :key="bill.id"
                            >
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="font-medium text-gray-900">
                                        {{ bill.invoice_number }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ bill.period || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ bill.student?.name || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ bill.room?.name || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ bill.title }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ bill.category?.name || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div>{{ formatCurrency(bill.amount) }}</div>
                                    <div class="text-xs text-gray-500">
                                        Dibayar: {{ formatCurrency(bill.paid_amount) }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="statusClass(bill.status)"
                                    >
                                        {{ statuses[bill.status] || bill.status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        <Link
                                            :href="route('student-bills.show', bill.id)"
                                            class="text-blue-600 hover:text-blue-800"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            v-if="bill.status !== 'paid'"
                                            :href="route('student-bills.edit', bill.id)"
                                            class="text-indigo-600 hover:text-indigo-800"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="bill.status !== 'paid'"
                                            type="button"
                                            @click="destroyBill(bill)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="bills.data.length === 0">
                                <td
                                    colspan="6"
                                    class="px-6 py-8 text-center text-sm text-gray-500"
                                >
                                    Belum ada tagihan siswa.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="bills.links.length > 3"
                    class="flex flex-wrap gap-2"
                >
                    <Link
                        v-for="link in bills.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="rounded border px-3 py-1 text-sm"
                        :class="{
                            'bg-indigo-600 text-white': link.active,
                            'text-gray-400': !link.url,
                        }"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>