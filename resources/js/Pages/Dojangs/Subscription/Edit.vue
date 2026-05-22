<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

const props = defineProps({
    dojang: Object,
    plans: Array,
})

const currentSubscription = props.dojang.subscription

const form = useForm({
    subscription_plan_id: currentSubscription?.subscription_plan_id ?? '',
    started_at: currentSubscription?.started_at ?? new Date().toISOString().slice(0, 10),
    expired_at: currentSubscription?.expired_at ?? '',
    status: currentSubscription?.status ?? 'free',
})

const selectedPlan = computed(() => {
    return props.plans.find(plan => plan.id === Number(form.subscription_plan_id))
})

const isFreePlan = computed(() => {
    return selectedPlan.value?.code === 'free'
})

watch(
    () => form.subscription_plan_id,
    () => {
        if (isFreePlan.value) {
            form.status = 'free'
            form.expired_at = ''
        } else if (form.status === 'free') {
            form.status = 'active'
        }
    }
)

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value ?? 0)
}

const submit = () => {
    form.put(route('dojangs.subscription.update', props.dojang.id))
}
</script>

<template>
    <Head title="Ubah Paket Dojang" />

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
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m9-6a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Ubah Paket Dojang
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <!-- Page heading -->
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        Ubah Paket {{ dojang.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Atur paket subscription, masa aktif, dan status pembayaran dojang
                    </p>
                </div>

                <Link
                    :href="route('dojangs.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>

                    Kembali
                </Link>
            </div>

            <!-- Informasi Dojang -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Dojang
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Ringkasan data dojang dan subscription saat ini
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ dojang.name?.charAt(0) ?? 'D' }}
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nama Dojang
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ dojang.name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Owner
                        </p>
                        <div class="mt-2 flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                {{ dojang.owner?.name?.charAt(0) ?? '-' }}
                            </div>

                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ dojang.owner?.name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Paket Saat Ini
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ dojang.subscription?.plan?.name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Status Subscription
                        </p>

                        <span
                            class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border capitalize"
                            :class="{
                                'bg-green-50 text-green-700 border-green-100': dojang.subscription?.status === 'active',
                                'bg-gray-50 text-gray-600 border-gray-100': dojang.subscription?.status === 'free' || !dojang.subscription?.status,
                                'bg-amber-50 text-amber-700 border-amber-100': dojang.subscription?.status === 'overdue',
                                'bg-red-50 text-red-700 border-red-100': ['cancelled', 'expired'].includes(dojang.subscription?.status),
                            }"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full"
                                :class="{
                                    'bg-green-500': dojang.subscription?.status === 'active',
                                    'bg-gray-400': dojang.subscription?.status === 'free' || !dojang.subscription?.status,
                                    'bg-amber-500': dojang.subscription?.status === 'overdue',
                                    'bg-red-500': ['cancelled', 'expired'].includes(dojang.subscription?.status),
                                }"
                            ></span>

                            {{ dojang.subscription?.status ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Paket -->
            <form
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                @submit.prevent="submit"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Pengaturan Paket
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Pilih paket dan tentukan periode subscription dojang
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Pilih Paket
                        </label>

                        <select
                            v-model="form.subscription_plan_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih paket</option>
                            <option
                                v-for="plan in plans"
                                :key="plan.id"
                                :value="plan.id"
                            >
                                {{ plan.name }} - {{ formatCurrency(plan.price) }}
                            </option>
                        </select>

                        <p v-if="form.errors.subscription_plan_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.subscription_plan_id }}
                        </p>
                    </div>

                    <!-- Selected plan preview -->
                    <div
                        v-if="selectedPlan"
                        class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl bg-white border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ selectedPlan.name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ formatCurrency(selectedPlan.price) }}
                                        </p>
                                    </div>
                                </div>

                                <p class="mt-3 text-sm text-gray-600">
                                    {{ selectedPlan.description || '-' }}
                                </p>
                            </div>

                            <span
                                class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full border capitalize shrink-0"
                                :class="isFreePlan
                                    ? 'bg-gray-50 text-gray-600 border-gray-200'
                                    : 'bg-green-50 text-green-700 border-green-100'"
                            >
                                {{ isFreePlan ? 'Free Plan' : 'Paid Plan' }}
                            </span>
                        </div>

                        <div class="mt-4 grid gap-3 text-sm md:grid-cols-3">
                            <div class="rounded-lg bg-white border border-indigo-100 p-3">
                                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Trainer
                                </p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ selectedPlan.max_trainers ?? 'Unlimited' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-white border border-indigo-100 p-3">
                                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Student
                                </p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ selectedPlan.max_students ?? 'Unlimited' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-white border border-indigo-100 p-3">
                                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Room
                                </p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ selectedPlan.max_rooms ?? 'Unlimited' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Tanggal Mulai
                            </label>

                            <input
                                v-model="form.started_at"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p v-if="form.errors.started_at" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.started_at }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Tanggal Expired
                            </label>

                            <input
                                v-model="form.expired_at"
                                type="date"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed"
                                :disabled="isFreePlan"
                            />

                            <p class="mt-1.5 text-xs text-gray-400">
                                Paket Free tidak membutuhkan tanggal expired.
                            </p>

                            <p v-if="form.errors.expired_at" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.expired_at }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Status
                        </label>

                        <select
                            v-model="form.status"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed"
                            :disabled="isFreePlan"
                        >
                            <option value="active">Active</option>
                            <option value="free">Free</option>
                            <option value="overdue">Overdue</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="expired">Expired</option>
                        </select>

                        <p v-if="form.errors.status" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.status }}
                        </p>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('dojangs.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing"
                    >
                        <svg
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            />
                        </svg>

                        {{ form.processing ? 'Menyimpan...' : 'Simpan Paket' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>