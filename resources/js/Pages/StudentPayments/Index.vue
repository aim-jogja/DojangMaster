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
        paid: 'bg-green-100 text-green-700',
        cancelled: 'bg-gray-100 text-gray-700',
        refunded: 'bg-orange-100 text-orange-700',
    }[status] || 'bg-gray-100 text-gray-700';
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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pembayaran Siswa
            </h2>
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

                <!-- Filter -->
                <div class="rounded-xl bg-white p-4 shadow-sm">
                    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Cari
                            </label>
                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-300 text-sm"
                                placeholder="Invoice / nama siswa / judul tagihan"
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Siswa
                            </label>
                            <select
                                v-model="form.student_id"
                                class="w-full rounded-lg border-gray-300 text-sm"
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Metode
                            </label>
                            <select
                                v-model="form.payment_method"
                                class="w-full rounded-lg border-gray-300 text-sm"
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-gray-300 text-sm"
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
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Dari Tanggal
                            </label>
                            <input
                                v-model="form.date_from"
                                type="date"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Sampai Tanggal
                            </label>
                            <input
                                v-model="form.date_to"
                                type="date"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            />
                        </div>

                        <div class="flex items-end gap-2">
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
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Pembayaran
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
                                v-for="payment in payments.data"
                                :key="payment.id"
                            >
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ payment.paid_at || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ paymentMethods[payment.payment_method] || payment.payment_method }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Diterima oleh: {{ payment.receiver?.name || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ payment.student?.name || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ payment.student?.email || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ payment.dojang?.name || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ payment.bill?.title || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ payment.bill?.invoice_number || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ formatCurrency(payment.amount) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="statusClass(payment.status)"
                                    >
                                        {{ statuses[payment.status] || payment.status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        <Link
                                            :href="route('student-payments.show', payment.id)"
                                            class="text-blue-600 hover:text-blue-800"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            v-if="payment.status === 'paid'"
                                            :href="route('student-payments.edit', payment.id)"
                                            class="text-indigo-600 hover:text-indigo-800"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="payment.status === 'paid'"
                                            type="button"
                                            @click="cancelPayment(payment)"
                                            class="text-orange-600 hover:text-orange-800"
                                        >
                                            Batalkan
                                        </button>

                                        <button
                                            type="button"
                                            @click="deletePayment(payment)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="payments.data.length === 0">
                                <td
                                    colspan="6"
                                    class="px-6 py-8 text-center text-sm text-gray-500"
                                >
                                    Belum ada data pembayaran siswa.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="payments.links.length > 3"
                    class="flex flex-wrap gap-2"
                >
                    <Link
                        v-for="link in payments.links"
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