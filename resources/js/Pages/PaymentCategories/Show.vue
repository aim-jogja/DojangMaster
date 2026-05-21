<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    category: Object,
});

const statusLabel = (value) => value ? 'Aktif' : 'Nonaktif';
</script>

<template>
    <Head title="Detail Kategori Pembayaran" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Kategori Pembayaran
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Nama Kategori
                            </div>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                {{ category.name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Dojang
                            </div>
                            <div class="mt-1 text-gray-900">
                                {{ category.dojang?.name || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Deskripsi
                            </div>
                            <div class="mt-1 whitespace-pre-line text-gray-900">
                                {{ category.description || '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">
                                Status
                            </div>
                            <div class="mt-1">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-medium"
                                    :class="category.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                                >
                                    {{ statusLabel(category.is_active) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <Link
                            :href="route('payment-categories.index')"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('payment-categories.edit', category.id)"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>