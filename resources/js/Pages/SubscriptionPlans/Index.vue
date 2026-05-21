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
    return value === null || value === undefined ? 'Unlimited' : value
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
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Subscription Plans
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola paket SaaS untuk dojang.
                    </p>
                </div>

                <Link
                    :href="route('subscription-plans.create')"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Tambah Plan
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Plan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Harga
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Limit
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Fitur
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="plan in plans.data"
                                    :key="plan.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ plan.name }}
                                        </div>
                                        <div class="mt-1">
                                            <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                                {{ plan.code }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ plan.description || '-' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ formatCurrency(plan.price) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div>Trainer: {{ formatLimit(plan.max_trainers) }}</div>
                                        <div>Student: {{ formatLimit(plan.max_students) }}</div>
                                        <div>Room: {{ formatLimit(plan.max_rooms) }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex max-w-xs flex-wrap gap-1">
                                            <span
                                                v-for="feature in plan.features"
                                                :key="feature.id"
                                                class="rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700"
                                            >
                                                {{ feature.name }}
                                            </span>

                                            <span
                                                v-if="!plan.features || plan.features.length === 0"
                                                class="text-sm text-gray-400"
                                            >
                                                Tidak ada fitur
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-medium"
                                            :class="plan.is_active
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'"
                                        >
                                            {{ plan.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="route('subscription-plans.edit', plan.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Edit
                                            </Link>

                                            <button
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="deletePlan(plan)"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="plans.data.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada subscription plan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="plans.links"
                        class="border-t px-6 py-4"
                    >
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="link in plans.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                v-html="link.label"
                                class="rounded border px-3 py-1 text-sm"
                                :class="[
                                    link.active
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-white text-gray-700 hover:bg-gray-50',
                                    !link.url ? 'cursor-not-allowed opacity-50' : ''
                                ]"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>