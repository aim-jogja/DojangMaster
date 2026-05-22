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
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Detail Trainer
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ trainer.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ trainer.email }}
                    </p>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border bg-indigo-50 text-indigo-700 border-indigo-100 shrink-0">
                    Trainer
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Trainer
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail identitas pelatih dan dojang tempat mengajar
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ trainer.name?.charAt(0) ?? 'T' }}
                    </div>
                </div>

                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Trainer
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ trainer.name ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ trainer.email ?? '-' }}
                            </dd>
                        </div>

                        <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dojang
                            </dt>

                            <dd class="mt-2 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                    {{ trainer.dojang?.name?.charAt(0) ?? '-' }}
                                </div>

                                <span class="text-sm font-semibold text-gray-900">
                                    {{ trainer.dojang?.name ?? '-' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('trainers.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('trainers.edit', trainer.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>