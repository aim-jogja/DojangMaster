<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Html5Qrcode } from 'html5-qrcode';
import { onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    schedules: Array,
    today: String,
});

const page = usePage();

const form = useForm({
    schedule_id: '',
    date: props.today,
    qr_token: '',
});

const scanner = ref(null);
const isScanning = ref(false);
const scannerMessage = ref('');
const readerId = 'qr-reader';

const startScanner = async () => {
    if (!form.schedule_id) {
        alert('Pilih schedule terlebih dahulu.');
        return;
    }

    if (!form.date) {
        alert('Pilih tanggal terlebih dahulu.');
        return;
    }

    if (isScanning.value) {
        return;
    }

    scannerMessage.value = 'Mengaktifkan kamera...';

    scanner.value = new Html5Qrcode(readerId);

    try {
        const cameras = await Html5Qrcode.getCameras();

        if (!cameras.length) {
            scannerMessage.value = 'Kamera tidak ditemukan.';
            return;
        }

        const cameraId = cameras[0].id;

        await scanner.value.start(
            cameraId,
            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250,
                },
            },
            onScanSuccess,
            onScanFailure
        );

        isScanning.value = true;
        scannerMessage.value = 'Scanner aktif. Arahkan kamera ke QR Code siswa.';
    } catch (error) {
        console.error(error);
        scannerMessage.value = 'Gagal mengaktifkan kamera.';
    }
};

const stopScanner = async () => {
    if (!scanner.value || !isScanning.value) {
        return;
    }

    try {
        await scanner.value.stop();
        await scanner.value.clear();
    } catch (error) {
        console.error(error);
    }

    isScanning.value = false;
    scannerMessage.value = 'Scanner berhenti.';
};

const onScanSuccess = async (decodedText) => {
    if (!decodedText) {
        return;
    }

    await stopScanner();

    form.qr_token = decodedText;

    form.post(route('attendances.scan.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.qr_token = '';
        },
        onFinish: () => {
            setTimeout(() => {
                startScanner();
            }, 800);
        },
    });
};

const onScanFailure = () => {
    // Sengaja dikosongkan agar tidak spam error saat kamera mencari QR.
};

onBeforeUnmount(async () => {
    await stopScanner();
});
</script>

<template>
    <Head title="Scan Attendance" />

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
                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75A1.125 1.125 0 013.75 8.625v-3.75zM14.25 4.875c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125v-3.75zM3.75 15.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125v-3.75z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14.25 14.25h2.25m-2.25 3h3.75m-3.75 3h6m0-6h.008v.008H20.25v-.008z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Scan Attendance
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Scan QR Attendance
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Pilih jadwal dan tanggal, lalu scan QR siswa untuk mencatat kehadiran
                    </p>
                </div>

                <Link
                    :href="route('attendances.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    Riwayat Attendance
                </Link>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="page.props.flash?.error"
                class="bg-red-50 border border-red-100 text-red-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.error }}
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="lg:col-span-1 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Pengaturan Scan
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Tentukan jadwal dan tanggal attendance
                        </p>
                    </div>

                    <div class="p-5 space-y-5">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Jadwal
                            </label>

                            <select
                                v-model="form.schedule_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed"
                                :disabled="isScanning"
                            >
                                <option value="">Pilih jadwal</option>

                                <option
                                    v-for="schedule in schedules"
                                    :key="schedule.id"
                                    :value="schedule.id"
                                >
                                    {{ schedule.room?.name }}
                                    -
                                    {{ schedule.room?.dojang?.name }}
                                    -
                                    {{ schedule.day }}
                                    {{ schedule.start_time }} - {{ schedule.end_time }}
                                </option>
                            </select>

                            <div v-if="form.errors.schedule_id" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.schedule_id }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Tanggal
                            </label>

                            <input
                                v-model="form.date"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed"
                                :disabled="isScanning"
                            />

                            <div v-if="form.errors.date" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.date }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <div class="flex items-start gap-3">
                                <span
                                    class="mt-1 h-2.5 w-2.5 rounded-full shrink-0"
                                    :class="isScanning ? 'bg-green-500' : 'bg-gray-300'"
                                ></span>

                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ isScanning ? 'Scanner aktif' : 'Scanner belum aktif' }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-gray-400">
                                        {{ scannerMessage || 'Tekan tombol mulai untuk mengaktifkan kamera.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                @click="startScanner"
                                :disabled="isScanning || form.processing"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Mulai Scan
                            </button>

                            <button
                                type="button"
                                @click="stopScanner"
                                :disabled="!isScanning"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Stop
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Kamera Scanner
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Arahkan kamera ke QR code attendance siswa
                            </p>
                        </div>

                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border"
                            :class="isScanning
                                ? 'bg-green-50 text-green-700 border-green-100'
                                : 'bg-gray-50 text-gray-600 border-gray-100'"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full"
                                :class="isScanning ? 'bg-green-500' : 'bg-gray-400'"
                            ></span>

                            {{ isScanning ? 'Aktif' : 'Standby' }}
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="mx-auto max-w-xl rounded-2xl border border-gray-100 bg-gray-50/60 p-4">
                            <div
                                id="qr-reader"
                                class="mx-auto overflow-hidden rounded-xl border border-gray-200 bg-white"
                            ></div>
                        </div>

                        <p class="mt-4 text-center text-sm text-gray-500">
                            {{ scannerMessage || 'Scanner siap digunakan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>