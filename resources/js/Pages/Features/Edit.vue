<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    feature: Object,
})

const form = useForm({
    code: props.feature.code,
    name: props.feature.name,
    description: props.feature.description,
    is_active: props.feature.is_active,
})

const submit = () => {
    form.put(route('features.update', props.feature.id))
}
</script>

<template>
    <Head title="Edit Feature" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Edit Feature
                    </h2>
                    <p class="text-sm text-gray-500">
                        Perbarui data fitur SaaS.
                    </p>
                </div>

                <Link
                    :href="route('features.index')"
                    class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form
                    class="rounded-xl bg-white p-6 shadow-sm"
                    @submit.prevent="submit"
                >
                    <div class="space-y-5">
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
                                Hati-hati mengubah code karena dipakai oleh pengecekan fitur di kode aplikasi.
                            </p>
                            <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Nama Feature
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

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Deskripsi
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            <label for="is_active" class="text-sm text-gray-700">
                                Feature aktif
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('features.index')"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>