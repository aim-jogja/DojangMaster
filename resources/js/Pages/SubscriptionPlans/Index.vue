<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
    plans: Object,
})

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value ?? 0)
}

const formatLimit = (value) => {
    return value === null || value === undefined || value === '' ? 'Unlimited' : value
}

const deletePlan = (plan) => {
    if (confirm(`Hapus plan "${plan.name}"?`)) {
        router.delete(route('subscription-plans.destroy', plan.id))
    }
}
</script>

<template>
    <Head title="Subscription Plans" />

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
                        d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Subscription Plans
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Subscription Plans
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola paket SaaS, harga, limit resource, dan fitur untuk dojang
                    </p>
                </div>

                <Link
                    :href="route('subscription-plans.create')"
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

                    Tambah Plan
                </Link>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Plan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan paket subscription yang tersedia pada sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ plans.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Plan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Harga
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Limit
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Fitur
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
                                v-for="plan in plans.data"
                                :key="plan.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[240px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ plan.name?.charAt(0) ?? 'P' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ plan.name }}
                                            </p>

                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="inline-flex items-center rounded-full border border-gray-100 bg-gray-50 px-2.5 py-0.5 font-mono text-xs font-semibold text-gray-600">
                                                    {{ plan.code }}
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs text-gray-400 line-clamp-2">
                                                {{ plan.description || 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="min-w-[140px]">
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ formatCurrency(plan.price) }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Per bulan
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="grid min-w-[180px] gap-1.5">
                                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-2.5 py-1">
                                            <span class="text-xs text-gray-400">Trainer</span>
                                            <span class="text-xs font-semibold text-gray-800">{{ formatLimit(plan.max_trainers) }}</span>
                                        </div>

                                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-2.5 py-1">
                                            <span class="text-xs text-gray-400">Student</span>
                                            <span class="text-xs font-semibold text-gray-800">{{ formatLimit(plan.max_students) }}</span>
                                        </div>

                                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-2.5 py-1">
                                            <span class="text-xs text-gray-400">Room</span>
                                            <span class="text-xs font-semibold text-gray-800">{{ formatLimit(plan.max_rooms) }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex max-w-xs flex-wrap gap-1.5">
                                        <span
                                            v-for="feature in plan.features"
                                            :key="feature.id"
                                            class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700"
                                        >
                                            {{ feature.name }}
                                        </span>

                                        <span
                                            v-if="!plan.features || plan.features.length === 0"
                                            class="inline-flex items-center rounded-full border border-gray-100 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-500"
                                        >
                                            Tidak ada fitur
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium"
                                        :class="plan.is_active
                                            ? 'bg-green-50 text-green-700 border-green-100'
                                            : 'bg-red-50 text-red-700 border-red-100'"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="plan.is_active ? 'bg-green-500' : 'bg-red-500'"
                                        ></span>

                                        {{ plan.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('subscription-plans.edit', plan.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            @click="deletePlan(plan)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="plans.data.length === 0">
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
                                                d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada subscription plan
                                        </p>

                                        <Link
                                            :href="route('subscription-plans.create')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Tambah plan pertama
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="plans.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ plans.from }}–{{ plans.to }} dari {{ plans.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in plans.links" :key="link.label">
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