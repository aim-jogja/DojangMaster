<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    dojangs: Object,
    canCreate: Boolean,
});

const page = usePage();

const deleteDojang = (id) => {
    if (confirm('Yakin ingin menghapus dojang ini?')) {
        router.delete(route('dojangs.destroy', id));
    }
};
</script>

<template>
    <Head title="Dojang" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Data Dojang
                </h2>

                <Link
                    v-if="canCreate"
                    :href="route('dojangs.create')"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Tambah Dojang
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Owner
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Domain
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Plan
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="dojang in dojangs.data" :key="dojang.id">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ dojang.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ dojang.phone ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ dojang.owner?.name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ dojang.domain }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="rounded-full px-2 py-1 text-xs"
                                        :class="dojang.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                    >
                                        {{ dojang.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ dojang.subscription?.plan?.name ?? 'Free' }}
                                    </div>
                                    <span
                                        class="rounded-full px-2 py-1 text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-700': dojang.subscription?.status === 'active',
                                            'bg-gray-100 text-gray-700': dojang.subscription?.status === 'free',
                                            'bg-yellow-100 text-yellow-700': dojang.subscription?.status === 'overdue',
                                            'bg-red-100 text-red-700': ['cancelled', 'expired'].includes(dojang.subscription?.status),
                                        }"
                                    >
                                        {{ dojang.subscription?.status ?? 'free' }}
                                    </span>
                                </td>

                                <td class="space-x-2 px-6 py-4 text-right text-sm">
                                    <Link
                                        :href="route('dojangs.subscription.edit', dojang.id)"
                                        class="text-emerald-600 hover:text-emerald-900"
                                    >
                                        Paket
                                    </Link>
                                    <Link
                                        :href="route('dojangs.show', dojang.id)"
                                        class="text-gray-600 hover:text-gray-900"
                                    >
                                        Detail
                                    </Link>

                                    <Link
                                        :href="route('dojangs.edit', dojang.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Edit
                                    </Link>

                                    <button
                                        v-if="canCreate"
                                        type="button"
                                        @click="deleteDojang(dojang.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="dojangs.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data dojang.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="dojangs.links?.length > 3" class="mt-4 flex flex-wrap gap-2">
                    <Link
                        v-for="link in dojangs.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="rounded px-3 py-1 text-sm"
                        :class="[
                            link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700',
                            !link.url ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'
                        ]"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>