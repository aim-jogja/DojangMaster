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
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Ubah Paket Dojang
                    </h2>
                    <p class="text-sm text-gray-500">
                        Atur paket subscription untuk dojang.
                    </p>
                </div>

                <Link
                    :href="route('dojangs.index')"
                    class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Dojang
                    </h3>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm text-gray-500">Nama Dojang</div>
                            <div class="font-medium text-gray-900">
                                {{ dojang.name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500">Owner</div>
                            <div class="font-medium text-gray-900">
                                {{ dojang.owner?.name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500">Paket Saat Ini</div>
                            <div class="font-medium text-gray-900">
                                {{ dojang.subscription?.plan?.name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500">Status Subscription</div>
                            <div class="font-medium text-gray-900">
                                {{ dojang.subscription?.status ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <form
                    class="rounded-xl bg-white p-6 shadow-sm"
                    @submit.prevent="submit"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        Pengaturan Paket
                    </h3>

                    <div class="mt-5 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Pilih Paket
                            </label>

                            <select
                                v-model="form.subscription_plan_id"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                            <p v-if="form.errors.subscription_plan_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.subscription_plan_id }}
                            </p>
                        </div>

                        <div
                            v-if="selectedPlan"
                            class="rounded-lg border bg-gray-50 p-4"
                        >
                            <div class="font-medium text-gray-900">
                                {{ selectedPlan.name }}
                            </div>

                            <div class="mt-1 text-sm text-gray-600">
                                {{ selectedPlan.description || '-' }}
                            </div>

                            <div class="mt-3 grid gap-2 text-sm text-gray-700 md:grid-cols-3">
                                <div>
                                    Trainer:
                                    {{ selectedPlan.max_trainers ?? 'Unlimited' }}
                                </div>
                                <div>
                                    Student:
                                    {{ selectedPlan.max_students ?? 'Unlimited' }}
                                </div>
                                <div>
                                    Room:
                                    {{ selectedPlan.max_rooms ?? 'Unlimited' }}
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tanggal Mulai
                                </label>

                                <input
                                    v-model="form.started_at"
                                    type="date"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />

                                <p v-if="form.errors.started_at" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.started_at }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tanggal Expired
                                </label>

                                <input
                                    v-model="form.expired_at"
                                    type="date"
                                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100"
                                    :disabled="isFreePlan"
                                />

                                <p class="mt-1 text-xs text-gray-500">
                                    Paket Free tidak membutuhkan tanggal expired.
                                </p>

                                <p v-if="form.errors.expired_at" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.expired_at }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Status
                            </label>

                            <select
                                v-model="form.status"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100"
                                :disabled="isFreePlan"
                            >
                                <option value="active">Active</option>
                                <option value="free">Free</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="expired">Expired</option>
                            </select>

                            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                                {{ form.errors.status }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <Link
                            :href="route('dojangs.index')"
                            class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>