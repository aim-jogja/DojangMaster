<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Trainer Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Trainer Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h1 class="text-lg font-bold">
                        Dashboard Trainer
                    </h1>

                    <p class="mt-2 text-gray-600">
                        Melihat jadwal, siswa, dan mengelola absensi latihan.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>