<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    categories: Object,
    filters: Object,
    dojangs: Array,
});

const page = usePage();

const form = reactive({
    search: props.filters.search || '',
    dojang_id: props.filters.dojang_id || '',
    status: props.filters.status || '',
});

const applyFilter = () => {
    router.get(route('payment-categories.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilter = () => {
    router.get(route('payment-categories.index'));
};

const destroyCategory = (category) => {
    if (!confirm(`Hapus kategori "${category.name}"?`)) return;

    router.delete(route('payment-categories.destroy', category.id), {
        preserveScroll: true,
    });
};

const statusLabel = (value) => value ? 'Aktif' : 'Nonaktif';

const statusClass = (value) => {
    return value
        ? 'bg-green-100 text-green-700'
        : 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Kategori Pembayaran" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Kategori Pembayaran
                </h2>

                <Link
                    :href="route('payment-categories.create')"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Tambah Kategori
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
                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Cari
                            </label>
                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-300 text-sm"
                                placeholder="Nama kategori"
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
                                Status
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-gray-300 text-sm"
                            >
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
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

                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Dojang
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
                                v-for="category in categories.data"
                                :key="category.id"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ category.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ category.description || '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ category.dojang?.name || '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="statusClass(category.is_active)"
                                    >
                                        {{ statusLabel(category.is_active) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        <Link
                                            :href="route('payment-categories.show', category.id)"
                                            class="text-blue-600 hover:text-blue-800"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            :href="route('payment-categories.edit', category.id)"
                                            class="text-indigo-600 hover:text-indigo-800"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            @click="destroyCategory(category)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="categories.data.length === 0">
                                <td
                                    colspan="4"
                                    class="px-6 py-8 text-center text-sm text-gray-500"
                                >
                                    Belum ada kategori pembayaran.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="categories.links.length > 3"
                    class="flex flex-wrap gap-2"
                >
                    <Link
                        v-for="link in categories.links"
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