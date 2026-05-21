<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    plan: Object,
    features: Array,
    selectedFeatureIds: Array,
})

const form = useForm({
    code: props.plan.code,
    name: props.plan.name,
    description: props.plan.description,
    price: props.plan.price,
    max_trainers: props.plan.max_trainers ?? '',
    max_students: props.plan.max_students ?? '',
    max_rooms: props.plan.max_rooms ?? '',
    is_active: props.plan.is_active,
    feature_ids: props.selectedFeatureIds ?? [],
})

const toggleFeature = (featureId) => {
    if (form.feature_ids.includes(featureId)) {
        form.feature_ids = form.feature_ids.filter(id => id !== featureId)
    } else {
        form.feature_ids.push(featureId)
    }
}

const submit = () => {
    form.put(route('subscription-plans.update', props.plan.id))
}
</script>

<template>
    <Head title="Edit Subscription Plan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Edit Subscription Plan
                    </h2>
                    <p class="text-sm text-gray-500">
                        Perbarui paket SaaS dan fitur yang tersedia.
                    </p>
                </div>

                <Link
                    :href="route('subscription-plans.index')"
                    class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <form
                    class="space-y-6"
                    @submit.prevent="submit"
                >
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Informasi Plan
                        </h3>

                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Code
                                </label>
                                <input
                                    v-model="form.code"
                                    type="text"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Hati-hati mengubah code karena bisa dipakai pada billing dan subscription.
                                </p>
                                <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.code }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Nama Plan
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Deskripsi
                                </label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.description }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Harga Bulanan
                                </label>
                                <input
                                    v-model="form.price"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.price }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 pt-6">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="is_active" class="text-sm text-gray-700">
                                    Plan aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Limit Resource
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Kosongkan field jika ingin unlimited.
                        </p>

                        <div class="mt-5 grid gap-5 md:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Max Trainer
                                </label>
                                <input
                                    v-model="form.max_trainers"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Unlimited"
                                />
                                <p v-if="form.errors.max_trainers" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.max_trainers }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Max Student
                                </label>
                                <input
                                    v-model="form.max_students"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Unlimited"
                                />
                                <p v-if="form.errors.max_students" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.max_students }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Max Room
                                </label>
                                <input
                                    v-model="form.max_rooms"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Unlimited"
                                />
                                <p v-if="form.errors.max_rooms" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.max_rooms }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Fitur Plan
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Pilih fitur yang tersedia pada paket ini.
                        </p>

                        <div class="mt-5 grid gap-3 md:grid-cols-2">
                            <label
                                v-for="feature in features"
                                :key="feature.id"
                                class="flex cursor-pointer items-start gap-3 rounded-lg border p-4 hover:bg-gray-50"
                                :class="form.feature_ids.includes(feature.id)
                                    ? 'border-indigo-500 bg-indigo-50'
                                    : 'border-gray-200'"
                            >
                                <input
                                    type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    :checked="form.feature_ids.includes(feature.id)"
                                    @change="toggleFeature(feature.id)"
                                />

                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ feature.name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ feature.code }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ feature.description || '-' }}
                                    </div>
                                </div>
                            </label>
                        </div>

                        <p v-if="form.errors.feature_ids" class="mt-2 text-sm text-red-600">
                            {{ form.errors.feature_ids }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link
                            :href="route('subscription-plans.index')"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Update Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>