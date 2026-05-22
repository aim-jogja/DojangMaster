<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import QrcodeVue from 'qrcode.vue';

const props = defineProps({
    student: Object,
    availableRooms: Array,
});

const page = usePage();

const showQrModal = ref(false);
const showCardModal = ref(false);

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

const printPreview = () => {
    window.print();
};

const closeModals = () => {
    showQrModal.value = false;
    showCardModal.value = false;
};
</script>

<template>
    <Head title="Detail Student" />

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
                    Detail Siswa
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ student.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ student.email }}
                    </p>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border bg-indigo-50 text-indigo-700 border-indigo-100 shrink-0">
                    Siswa
                </span>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Informasi Siswa
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Detail identitas siswa dan dojang utama
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                            {{ student.name?.charAt(0) ?? 'S' }}
                        </div>
                    </div>

                    <div class="p-5">
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Nama Siswa
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ student.name ?? '-' }}
                                </dd>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Email
                                </dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ student.email ?? '-' }}
                                </dd>
                            </div>

                            <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang
                                </dt>

                                <dd class="mt-2 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                        {{ student.dojang?.name?.charAt(0) ?? '-' }}
                                    </div>

                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ student.dojang?.name ?? '-' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            QR Code Attendance
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Digunakan untuk absensi siswa
                        </p>
                    </div>

                    <div class="p-5 flex flex-col items-center justify-center gap-4">
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                            <QrcodeVue
                                v-if="student.qr_token"
                                :value="student.qr_token"
                                :size="220"
                                level="H"
                            />
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                QR Code absensi siswa
                            </p>

                            <p class="mt-1 break-all text-xs text-gray-400">
                                {{ student.qr_token }}
                            </p>
                        </div>

                        <div class="grid w-full grid-cols-1 gap-2 sm:grid-cols-2">
                            <button
                                type="button"
                                @click="showQrModal = true"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition-colors hover:bg-indigo-100"
                            >
                                Preview QR
                            </button>

                            <button
                                type="button"
                                @click="showCardModal = true"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50"
                            >
                                Preview KTA
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Room / Kelas Siswa
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Kelola room atau kelas yang diikuti siswa
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ student.rooms?.length ?? 0 }} item
                    </span>
                </div>

                <div class="p-5 border-b border-gray-100 bg-gray-50/40">
                    <form @submit.prevent="attachRoom" class="flex flex-col gap-2 sm:flex-row">
                        <select
                            v-model="form.room_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih room</option>
                            <option
                                v-for="room in remainingRooms()"
                                :key="room.id"
                                :value="room.id"
                            >
                                {{ room.name }}
                            </option>
                        </select>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed shrink-0"
                        >
                            Tambah
                        </button>
                    </form>
                </div>

                <div v-if="student.rooms?.length" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Room
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Biaya Bulanan
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="room in student.rooms"
                                :key="room.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase shrink-0">
                                            {{ room.name?.charAt(0) ?? 'R' }}
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ room.name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm font-medium text-gray-700">
                                    Rp {{ Number(room.monthly_fee).toLocaleString('id-ID') }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <button
                                        type="button"
                                        @click="detachRoom(room.id)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                    >
                                        Keluarkan
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-12 text-center">
                    <p class="text-sm text-gray-400">
                        Siswa belum masuk room mana pun.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('students.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('students.edit', student.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>

        <!-- QR PRINT MODAL -->
        <div
            v-if="showQrModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 px-4 py-6 backdrop-blur-sm no-print"
            @click.self="closeModals"
        >
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            Preview QR Attendance
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Preview QR code siswa sebelum dicetak
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="closeModals"
                        class="rounded-lg p-2 text-gray-400 hover:bg-gray-50 hover:text-gray-600"
                    >
                        ✕
                    </button>
                </div>

                <div class="p-5">
                    <div class="print-area mx-auto max-w-sm rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-lg font-bold uppercase text-indigo-600">
                            {{ student.name?.charAt(0) ?? 'S' }}
                        </div>

                        <h2 class="text-lg font-bold text-gray-900">
                            {{ student.name }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ student.dojang?.name ?? '-' }}
                        </p>

                        <div class="my-6 flex justify-center">
                            <div class="rounded-xl border border-gray-100 bg-white p-4">
                                <QrcodeVue
                                    v-if="student.qr_token"
                                    :value="student.qr_token"
                                    :size="260"
                                    level="H"
                                />
                            </div>
                        </div>

                        <p class="text-xs font-medium uppercase tracking-widest text-gray-400">
                            Attendance QR Code
                        </p>

                        <p class="mt-2 break-all text-xs text-gray-400">
                            {{ student.qr_token }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-gray-100 bg-gray-50/60 px-5 py-4">
                    <button
                        type="button"
                        @click="closeModals"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        Tutup
                    </button>

                    <button
                        type="button"
                        @click="printPreview"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95"
                    >
                        Print QR
                    </button>
                </div>
            </div>
        </div>

        <!-- KTA PRINT MODAL -->
        <div
            v-if="showCardModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 px-4 py-6 backdrop-blur-sm no-print"
            @click.self="closeModals"
        >
            <div class="w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            Preview KTA Siswa
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Tampak depan dan belakang kartu anggota siswa
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="closeModals"
                        class="rounded-lg p-2 text-gray-400 hover:bg-gray-50 hover:text-gray-600"
                    >
                        ✕
                    </button>
                </div>

                <div class="p-5">
                    <div class="print-area flex flex-col items-center justify-center gap-5 lg:flex-row">
                        <!-- KTA FRONT -->
                        <div class="id-card relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500"></div>
                            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
                            <div class="absolute -left-8 top-12 h-24 w-24 rounded-full bg-white/10"></div>

                            <div class="relative z-10 flex h-full flex-col p-5">
                                <div class="flex items-center justify-between text-white">
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-indigo-100">
                                            Dojang Master
                                        </p>
                                        <h2 class="mt-1 text-lg font-bold leading-tight">
                                            Student Card
                                        </h2>
                                    </div>

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-sm font-bold uppercase ring-1 ring-white/20">
                                        DM
                                    </div>
                                </div>

                                <div class="mt-8 flex gap-4">
                                    <div class="h-24 w-20 shrink-0 overflow-hidden rounded-xl border-4 border-white bg-gray-100 shadow-sm">
                                        <div class="flex h-full w-full flex-col items-center justify-center bg-gray-100 text-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-10 w-10"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"
                                                />
                                            </svg>
                                            <span class="mt-1 text-[9px] font-medium uppercase">
                                                Photo
                                            </span>
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1 pt-1">
                                        <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                            Nama Siswa
                                        </p>
                                        <h3 class="mt-1 truncate text-base font-bold text-gray-900">
                                            {{ student.name }}
                                        </h3>

                                        <div class="mt-3 grid grid-cols-1 gap-2">
                                            <div>
                                                <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                                    Dojang
                                                </p>
                                                <p class="truncate text-xs font-semibold text-gray-800">
                                                    {{ student.dojang?.name ?? '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                                    Email
                                                </p>
                                                <p class="truncate text-xs font-medium text-gray-700">
                                                    {{ student.email ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto flex items-end justify-between">
                                    <div>
                                        <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                            Member ID
                                        </p>
                                        <p class="font-mono text-xs font-bold text-indigo-700">
                                            STD-{{ String(student.id).padStart(5, '0') }}
                                        </p>
                                    </div>

                                    <span class="rounded-full border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-widest text-indigo-700">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- KTA BACK -->
                        <div class="id-card relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <div class="absolute inset-x-0 top-0 h-4 bg-indigo-600"></div>
                            <div class="absolute inset-x-0 bottom-0 h-4 bg-indigo-600"></div>

                            <div class="flex h-full flex-col p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-indigo-600">
                                            Attendance Access
                                        </p>
                                        <h2 class="mt-1 text-base font-bold text-gray-900">
                                            Scan QR Code
                                        </h2>
                                        <p class="mt-1 max-w-[190px] text-[10px] leading-relaxed text-gray-500">
                                            Gunakan QR code ini untuk proses attendance siswa pada jadwal latihan.
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-100 bg-white p-2 shadow-sm">
                                        <QrcodeVue
                                            v-if="student.qr_token"
                                            :value="student.qr_token"
                                            :size="105"
                                            level="H"
                                        />
                                    </div>
                                </div>

                                <div class="mt-5 rounded-xl border border-gray-100 bg-gray-50/80 p-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                                Nama
                                            </p>
                                            <p class="truncate text-xs font-semibold text-gray-900">
                                                {{ student.name }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400">
                                                Dojang
                                            </p>
                                            <p class="truncate text-xs font-semibold text-gray-900">
                                                {{ student.dojang?.name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="mt-3 break-all font-mono text-[9px] leading-relaxed text-gray-400">
                                        {{ student.qr_token }}
                                    </p>
                                </div>

                                <div class="mt-auto flex items-center justify-between">
                                    <p class="text-[9px] font-medium text-gray-400">
                                        Kartu ini digunakan untuk identifikasi siswa.
                                    </p>

                                    <p class="text-[9px] font-semibold text-gray-500">
                                        Powered by Dojang Master
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-gray-100 bg-gray-50/60 px-5 py-4">
                    <button
                        type="button"
                        @click="closeModals"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        Tutup
                    </button>

                    <button
                        type="button"
                        @click="printPreview"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95"
                    >
                        Print KTA
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.id-card {
    width: 340px;
    height: 214px;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 12mm;
    }

    body * {
        visibility: hidden !important;
    }

    .print-area,
    .print-area * {
        visibility: visible !important;
    }

    .print-area {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        min-height: auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8mm !important;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
    }

    .no-print {
        background: white !important;
        backdrop-filter: none !important;
    }

    .id-card {
        width: 85.6mm !important;
        height: 54mm !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
        box-shadow: none !important;
    }

    button,
    .no-print button {
        display: none !important;
    }
}
</style>