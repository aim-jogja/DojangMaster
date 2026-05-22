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
                        d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-3M9 9h1m-1 4h1m-1 4h1m4-4h1m-1 4h1"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Dojang
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <!-- Page heading + action -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Dojang
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola data dojang, owner, status, dan paket langganan
                    </p>
                </div>

                <Link
                    v-if="canCreate"
                    :href="route('dojangs.create')"
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

                    Tambah Dojang
                </Link>
            </div>

            <!-- Flash message -->
            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <!-- Table card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Dojang
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan data dojang yang terdaftar di sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ dojangs.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Owner
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Domain
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Plan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="dojang in dojangs.data"
                                :key="dojang.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <!-- Dojang -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[220px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ dojang.name?.charAt(0) ?? 'D' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ dojang.name }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                {{ dojang.phone ?? 'Tidak ada nomor telepon' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Owner -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 min-w-[160px]">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                            {{ dojang.owner?.name?.charAt(0) ?? '-' }}
                                        </div>

                                        <span class="font-medium text-gray-700 truncate">
                                            {{ dojang.owner?.name ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Domain -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-mono text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-3 h-3"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 21a9 9 0 100-18 9 9 0 000 18z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18"
                                            />
                                        </svg>

                                        {{ dojang.domain ?? '-' }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border"
                                        :class="dojang.is_active
                                            ? 'bg-green-50 text-green-700 border-green-100'
                                            : 'bg-red-50 text-red-700 border-red-100'"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="dojang.is_active ? 'bg-green-500' : 'bg-red-500'"
                                        ></span>

                                        {{ dojang.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <!-- Plan -->
                                <td class="px-5 py-4">
                                    <div class="min-w-[120px]">
                                        <p class="font-medium text-gray-900">
                                            {{ dojang.subscription?.plan?.name ?? 'Free' }}
                                        </p>

                                        <span
                                            class="mt-1 inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full border capitalize"
                                            :class="{
                                                'bg-green-50 text-green-700 border-green-100': dojang.subscription?.status === 'active',
                                                'bg-gray-50 text-gray-600 border-gray-100': dojang.subscription?.status === 'free' || !dojang.subscription?.status,
                                                'bg-amber-50 text-amber-700 border-amber-100': dojang.subscription?.status === 'overdue',
                                                'bg-red-50 text-red-700 border-red-100': ['cancelled', 'expired'].includes(dojang.subscription?.status),
                                            }"
                                        >
                                            {{ dojang.subscription?.status ?? 'free' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('dojangs.subscription.edit', dojang.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg hover:bg-emerald-100 transition-colors"
                                        >
                                            Paket
                                        </Link>

                                        <Link
                                            :href="route('dojangs.show', dojang.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            :href="route('dojangs.edit', dojang.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="canCreate"
                                            type="button"
                                            @click="deleteDojang(dojang.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty state -->
                            <tr v-if="dojangs.data.length === 0">
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
                                                d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-3M9 9h1m-1 4h1m-1 4h1m4-4h1m-1 4h1"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada data dojang
                                        </p>

                                        <Link
                                            v-if="canCreate"
                                            :href="route('dojangs.create')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Tambah dojang pertama
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="dojangs.links && dojangs.data.length > 0"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ dojangs.from }}–{{ dojangs.to }} dari {{ dojangs.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in dojangs.links" :key="link.label">
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