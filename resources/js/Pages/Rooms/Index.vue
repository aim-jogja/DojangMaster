<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    rooms: Object,
});

const page = usePage();

const deleteRoom = (id) => {
    if (confirm('Yakin ingin menghapus room ini?')) {
        router.delete(route('rooms.destroy', id));
    }
};
</script>

<template>
    <Head title="Room" />

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
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Room / Kelas
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Room / Kelas
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola room, biaya pendaftaran, biaya bulanan, dan status kelas
                    </p>
                </div>

                <Link
                    :href="route('rooms.create')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Room
                </Link>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Room
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan data room yang terdaftar di sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ rooms.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Room
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Biaya Daftar
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Biaya Bulanan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="room in rooms.data"
                                :key="room.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[220px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ room.name?.charAt(0) ?? 'R' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ room.name }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                {{ room.description ?? 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 min-w-[160px]">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                            {{ room.dojang?.name?.charAt(0) ?? '-' }}
                                        </div>

                                        <span class="font-medium text-gray-700 truncate">
                                            {{ room.dojang?.name ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-medium text-gray-700">
                                        Rp {{ Number(room.registration_fee).toLocaleString('id-ID') }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-medium text-gray-700">
                                        Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border"
                                        :class="room.is_active
                                            ? 'bg-green-50 text-green-700 border-green-100'
                                            : 'bg-red-50 text-red-700 border-red-100'"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="room.is_active ? 'bg-green-500' : 'bg-red-500'"
                                        ></span>

                                        {{ room.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('rooms.show', room.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            :href="route('rooms.edit', room.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            @click="deleteRoom(room.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="rooms.data.length === 0">
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-10 h-10 text-gray-300"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada data room
                                        </p>

                                        <Link
                                            :href="route('rooms.create')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Tambah room pertama
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="rooms.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ rooms.from }}–{{ rooms.to }} dari {{ rooms.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in rooms.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 text-xs rounded-lg border transition-colors',
                                    link.active
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                                ]"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 text-xs rounded-lg border border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>