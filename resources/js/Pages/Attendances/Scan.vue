<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Scan Attendance
                </h2>

                <Link
                    :href="route('attendances.index')"
                    class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Riwayat Attendance
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash?.success"
                    class="rounded bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="rounded bg-red-100 px-4 py-3 text-red-700"
                >
                    {{ page.props.flash.error }}
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Schedule
                            </label>

                            <select
                                v-model="form.schedule_id"
                                class="w-full rounded border-gray-300"
                                :disabled="isScanning"
                            >
                                <option value="">Pilih schedule</option>

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

                            <div v-if="form.errors.schedule_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.schedule_id }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Tanggal
                            </label>

                            <input
                                v-model="form.date"
                                type="date"
                                class="w-full rounded border-gray-300"
                                :disabled="isScanning"
                            />

                            <div v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                                {{ form.errors.date }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <button
                            type="button"
                            @click="startScanner"
                            :disabled="isScanning || form.processing"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Mulai Scan
                        </button>

                        <button
                            type="button"
                            @click="stopScanner"
                            :disabled="!isScanning"
                            class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                        >
                            Stop
                        </button>
                    </div>
                </div>

                <div class="rounded bg-white p-6 shadow">
                    <div
                        id="qr-reader"
                        class="mx-auto max-w-md overflow-hidden rounded border"
                    ></div>

                    <p class="mt-4 text-center text-sm text-gray-600">
                        {{ scannerMessage }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>