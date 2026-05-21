<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    dojang: Object,
});
</script>

<template>
    <Head title="Detail Dojang" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Dojang
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="rounded bg-white p-6 shadow">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ dojang.name }}
                            </h1>
                            <p class="text-sm text-gray-500">
                                {{ dojang.domain }}
                            </p>
                        </div>

                        <span
                            class="rounded-full px-3 py-1 text-sm"
                            :class="dojang.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                        >
                            {{ dojang.is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Owner
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ dojang.owner?.name ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Email Owner
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ dojang.owner?.email ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Telepon
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ dojang.phone ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Alamat
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                {{ dojang.address ?? '-' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">
                            Room / Kelas
                        </h3>

                        <div v-if="dojang.rooms?.length" class="overflow-hidden rounded border">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Nama
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Pendaftaran
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Bulanan
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm text-gray-500">
                                            Status
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="room in dojang.rooms" :key="room.id">
                                        <td class="px-4 py-2 text-sm">
                                            {{ room.name }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            Rp {{ Number(room.registration_fee).toLocaleString('id-ID') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ room.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p v-else class="text-sm text-gray-500">
                            Belum ada room/kelas.
                        </p>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('dojangs.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('dojangs.edit', dojang.id)"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>