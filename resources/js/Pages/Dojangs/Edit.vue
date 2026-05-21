<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    dojang: Object,
    owners: Array,
    isSuperAdmin: Boolean,
});

const form = useForm({
    user_id: props.dojang.user_id,
    name: props.dojang.name,
    address: props.dojang.address ?? '',
    domain: props.dojang.domain,
    phone: props.dojang.phone ?? '',
    is_active: Boolean(props.dojang.is_active),
});

const submit = () => {
    form.put(route('dojangs.update', props.dojang.id));
};
</script>

<template>
    <Head title="Edit Dojang" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Dojang
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6 rounded bg-white p-6 shadow">
                    <div v-if="isSuperAdmin">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Owner
                        </label>
                        <select
                            v-model="form.user_id"
                            class="w-full rounded border-gray-300"
                        >
                            <option value="">Pilih owner</option>
                            <option
                                v-for="owner in owners"
                                :key="owner.id"
                                :value="owner.id"
                            >
                                {{ owner.name }} - {{ owner.email }}
                            </option>
                        </select>
                        <div v-if="form.errors.user_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.user_id }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nama Dojang
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded border-gray-300"
                        />
                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Domain
                        </label>
                        <input
                            v-model="form.domain"
                            type="text"
                            class="w-full rounded border-gray-300"
                        />
                        <div v-if="form.errors.domain" class="mt-1 text-sm text-red-600">
                            {{ form.errors.domain }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Alamat
                        </label>
                        <textarea
                            v-model="form.address"
                            class="w-full rounded border-gray-300"
                        />
                        <div v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Telepon
                        </label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full rounded border-gray-300"
                        />
                        <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300"
                        />
                        <label for="is_active" class="text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link
                            :href="route('dojangs.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>