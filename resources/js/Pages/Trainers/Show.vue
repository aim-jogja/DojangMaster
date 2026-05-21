<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    trainer: Object,
});
</script>

<template>
    <Head title="Detail Trainer" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Trainer
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="rounded bg-white p-6 shadow">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ trainer.name }}
                    </h1>

                    <dl class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-gray-900">{{ trainer.email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-500">Dojang</dt>
                            <dd class="text-gray-900">{{ trainer.dojang?.name ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link :href="route('trainers.index')" class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Kembali
                        </Link>

                        <Link :href="route('trainers.edit', trainer.id)" class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>