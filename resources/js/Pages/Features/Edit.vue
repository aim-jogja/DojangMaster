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
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Edit Feature
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        Edit {{ feature.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Perbarui data fitur SaaS, code, deskripsi, dan status aktif
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="form.is_active
                        ? 'bg-green-50 text-green-700 border-green-100'
                        : 'bg-red-50 text-red-700 border-red-100'"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="form.is_active ? 'bg-green-500' : 'bg-red-500'"
                    ></span>

                    {{ form.is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Feature
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data feature saat ini sebelum diperbarui
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ feature.name?.charAt(0) ?? 'F' }}
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nama
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ feature.name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Code
                        </p>
                        <p class="mt-1 font-mono text-xs font-semibold text-indigo-700 truncate">
                            {{ feature.code ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Status
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ feature.is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Edit Feature
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sesuaikan data feature yang perlu diperbarui
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Code
                            </label>

                            <input
                                v-model="form.code"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p class="mt-1.5 text-xs text-gray-400">
                                Hati-hati mengubah code karena dipakai oleh pengecekan fitur di kode aplikasi.
                            </p>

                            <p v-if="form.errors.code" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Feature
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />

                        <p v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800">
                                    Status Feature
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Aktifkan atau nonaktifkan feature ini.
                                </p>
                            </div>

                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('features.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Mengupdate...' : 'Update Feature' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>