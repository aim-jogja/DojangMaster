<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Super Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Super Admin Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h1 class="text-lg font-bold">
                        Dashboard Super Admin
                    </h1>

                    <p class="mt-2 text-gray-600">
                        Mengelola seluruh platform, dojang, pembayaran SaaS, role, dan data sistem.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>