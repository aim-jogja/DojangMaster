<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    students: Object,
});

const page = usePage();

const deleteStudent = (id) => {
    if (confirm('Yakin ingin menghapus student ini?')) {
        router.delete(route('students.destroy', id));
    }
};
</script>

<template>
    <Head title="Student" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Data Student
                </h2>

                <Link
                    :href="route('students.create')"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Tambah Student
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Dojang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Room</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="student in students.data" :key="student.id">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ student.name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ student.email }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ student.dojang?.name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span v-if="student.rooms?.length">
                                        {{ student.rooms.map(room => room.name).join(', ') }}
                                    </span>
                                    <span v-else>-</span>
                                </td>

                                <td class="space-x-2 px-6 py-4 text-right text-sm">
                                    <Link :href="route('students.show', student.id)" class="text-gray-600 hover:text-gray-900">
                                        Detail
                                    </Link>

                                    <Link :href="route('students.edit', student.id)" class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </Link>

                                    <button
                                        type="button"
                                        @click="deleteStudent(student.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="students.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data student.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="students.links?.length > 3" class="mt-4 flex flex-wrap gap-2">
                    <Link
                        v-for="link in students.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="rounded px-3 py-1 text-sm"
                        :class="[
                            link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700',
                            !link.url ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'
                        ]"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>