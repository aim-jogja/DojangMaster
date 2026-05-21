<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import QrcodeVue from 'qrcode.vue';

const props = defineProps({
    student: Object,
    availableRooms: Array,
});

const page = usePage();

const form = useForm({
    room_id: '',
});

const attachedRoomIds = () => {
    return props.student.rooms?.map(room => room.id) ?? [];
};

const remainingRooms = () => {
    return props.availableRooms.filter(room => !attachedRoomIds().includes(room.id));
};

const attachRoom = () => {
    form.post(route('students.rooms.attach', props.student.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const detachRoom = (roomId) => {
    if (confirm('Keluarkan student dari room ini?')) {
        router.delete(route('students.rooms.detach', [props.student.id, roomId]), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Detail Student" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Student
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="rounded bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ student.name }}
                    </h1>

                    <dl class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-gray-900">{{ student.email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-500">Dojang</dt>
                            <dd class="text-gray-900">{{ student.dojang?.name ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('students.index')"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </Link>

                        <Link
                            :href="route('students.edit', student.id)"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Edit
                        </Link>
                    </div>
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">
                        QR Code Attendance
                    </h2>

                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="rounded border bg-white p-4">
                            <QrcodeVue
                                v-if="student.qr_token"
                                :value="student.qr_token"
                                :size="220"
                                level="H"
                            />
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                QR Code ini digunakan untuk absensi student.
                            </p>

                            <p class="mt-1 break-all text-xs text-gray-400">
                                {{ student.qr_token }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">
                        Room / Kelas Student
                    </h2>

                    <form @submit.prevent="attachRoom" class="mb-6 flex gap-2">
                        <select v-model="form.room_id" class="w-full rounded border-gray-300">
                            <option value="">Pilih room</option>
                            <option v-for="room in remainingRooms()" :key="room.id" :value="room.id">
                                {{ room.name }}
                            </option>
                        </select>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Tambah
                        </button>
                    </form>

                    <div v-if="student.rooms?.length" class="overflow-hidden rounded border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm text-gray-500">
                                        Room
                                    </th>
                                    <th class="px-4 py-2 text-left text-sm text-gray-500">
                                        Biaya Bulanan
                                    </th>
                                    <th class="px-4 py-2 text-right text-sm text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="room in student.rooms" :key="room.id">
                                    <td class="px-4 py-2 text-sm">
                                        {{ room.name }}
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                                    </td>

                                    <td class="px-4 py-2 text-right text-sm">
                                        <button
                                            type="button"
                                            @click="detachRoom(room.id)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Keluarkan
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p v-else class="text-sm text-gray-500">
                        Student belum masuk room mana pun.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>