<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
    features: Object,
})

const deleteFeature = (feature) => {
    if (confirm(`Hapus feature "${feature.name}"?`)) {
        router.delete(route('features.destroy', feature.id))
    }
}
</script>

<template>
    <Head title="Features" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Features
                    </h2>
                    <p class="text-sm text-gray-500">
                        Daftar fitur SaaS yang tersedia di sistem.
                    </p>
                </div>

                <Link
                    :href="route('features.create')"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Tambah Feature
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
                                        Feature
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Code
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Dipakai Plan
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
                                    v-for="feature in features.data"
                                    :key="feature.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ feature.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ feature.description || '-' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ feature.code }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ feature.plans_count ?? 0 }} plan
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-medium"
                                            :class="feature.is_active
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'"
                                        >
                                            {{ feature.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="route('features.edit', feature.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Edit
                                            </Link>

                                            <button
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="deleteFeature(feature)"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="features.data.length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada feature.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="features.links"
                        class="border-t px-6 py-4"
                    >
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="link in features.links"
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