<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    features: Array,
})

const form = useForm({
    code: '',
    name: '',
    description: '',
    price: 0,
    max_trainers: '',
    max_students: '',
    max_rooms: '',
    is_active: true,
    feature_ids: [],
})

const toggleFeature = (featureId) => {
    if (form.feature_ids.includes(featureId)) {
        form.feature_ids = form.feature_ids.filter(id => id !== featureId)
    } else {
        form.feature_ids.push(featureId)
    }
}

const submit = () => {
    form.post(route('subscription-plans.store'))
}
</script>

<template>
    <Head title="Tambah Subscription Plan" />

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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Tambah Subscription Plan
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tambah Subscription Plan
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Buat paket SaaS baru untuk dojang beserta fitur dan limit resource
                    </p>
                </div>

                <Link
                    :href="route('subscription-plans.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    Kembali
                </Link>
            </div>

            <form
                class="space-y-5"
                @submit.prevent="submit"
            >
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Plan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Isi kode, nama, deskripsi, harga, dan status aktif plan
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
                                    placeholder="contoh: starter"
                                />

                                <p class="mt-1.5 text-xs text-gray-400">
                                    Gunakan huruf kecil, angka, underscore, atau dash.
                                </p>

                                <p v-if="form.errors.code" class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ form.errors.code }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nama Plan
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="contoh: Starter"
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
                                placeholder="Jelaskan paket ini..."
                            />

                            <p v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Harga Bulanan
                                </label>

                                <input
                                    v-model="form.price"
                                    type="number"
                                    min="0"
                                    class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />

                                <p v-if="form.errors.price" class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ form.errors.price }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <label for="is_active" class="text-sm font-semibold text-gray-800">
                                            Status Plan
                                        </label>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Aktifkan plan agar bisa digunakan pada subscription dojang.
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
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Limit Resource
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Kosongkan field jika ingin unlimited
                        </p>
                    </div>

                    <div class="p-5 grid gap-5 md:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Max Trainer
                            </label>

                            <input
                                v-model="form.max_trainers"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Unlimited"
                            />

                            <p v-if="form.errors.max_trainers" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.max_trainers }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Max Student
                            </label>

                            <input
                                v-model="form.max_students"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Unlimited"
                            />

                            <p v-if="form.errors.max_students" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.max_students }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Max Room
                            </label>

                            <input
                                v-model="form.max_rooms"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Unlimited"
                            />

                            <p v-if="form.errors.max_rooms" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.max_rooms }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Fitur Plan
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Pilih fitur yang tersedia pada paket ini
                            </p>
                        </div>

                        <span class="text-xs text-gray-400 shrink-0">
                            {{ form.feature_ids.length }} dipilih
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="grid gap-3 md:grid-cols-2">
                            <label
                                v-for="feature in features"
                                :key="feature.id"
                                class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors"
                                :class="form.feature_ids.includes(feature.id)
                                    ? 'border-indigo-100 bg-indigo-50/70'
                                    : 'border-gray-100 bg-white hover:bg-gray-50'"
                            >
                                <input
                                    type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    :checked="form.feature_ids.includes(feature.id)"
                                    @change="toggleFeature(feature.id)"
                                />

                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900">
                                        {{ feature.name }}
                                    </div>

                                    <div class="mt-1 inline-flex rounded-full border border-gray-100 bg-gray-50 px-2 py-0.5 font-mono text-xs font-medium text-gray-500">
                                        {{ feature.code }}
                                    </div>

                                    <div class="mt-2 text-sm text-gray-500 line-clamp-2">
                                        {{ feature.description || '-' }}
                                    </div>
                                </div>
                            </label>
                        </div>

                        <p v-if="form.errors.feature_ids" class="mt-2 text-xs font-medium text-red-600">
                            {{ form.errors.feature_ids }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <Link
                        :href="route('subscription-plans.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Plan' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>