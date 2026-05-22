<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="min-h-screen overflow-hidden bg-slate-950 text-white">
            <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
                <!-- Background accents -->
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(79,70,229,0.24),transparent_32%),radial-gradient(circle_at_bottom_right,rgba(239,68,68,0.18),transparent_30%)]"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:32px_32px] opacity-20"></div>
                    <div class="absolute left-1/2 top-0 h-full w-px bg-gradient-to-b from-transparent via-white/10 to-transparent"></div>
                </div>

                <div class="relative z-10 grid w-full max-w-6xl overflow-hidden rounded-3xl border border-white/10 bg-white/[0.03] shadow-2xl backdrop-blur-xl lg:grid-cols-2">
                    <!-- Left panel -->
                    <div class="relative hidden min-h-[640px] overflow-hidden bg-slate-900 p-10 lg:block">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/25 via-slate-950 to-red-600/20"></div>
                        <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
                        <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-red-500/20 blur-3xl"></div>

                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <div class="inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-950">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold tracking-wide">
                                            Dojang Master
                                        </p>
                                        <p class="text-xs text-slate-300">
                                            Training Management System
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-20 max-w-md">
                                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-300">
                                        Discipline • Power • Progress
                                    </p>

                                    <h1 class="mt-5 text-5xl font-black leading-tight tracking-tight">
                                        Manage your dojang with warrior focus.
                                    </h1>

                                    <p class="mt-5 text-base leading-7 text-slate-300">
                                        Sistem manajemen dojang untuk mengelola siswa, pelatih, jadwal, attendance, tagihan, dan pembayaran dalam satu dashboard.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-2xl font-black">01</p>
                                    <p class="mt-1 text-xs text-slate-300">Train</p>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-2xl font-black">02</p>
                                    <p class="mt-1 text-xs text-slate-300">Track</p>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-2xl font-black">03</p>
                                    <p class="mt-1 text-xs text-slate-300">Grow</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right panel -->
                    <div class="relative bg-white px-6 py-8 text-slate-900 sm:px-10 lg:px-12 lg:py-12">
                        <div class="mx-auto w-full max-w-md">
                            <div class="mb-8 lg:hidden">
                                <div class="inline-flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-white">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-slate-900">
                                            Dojang Master
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Training Management System
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-indigo-600">
                                    Welcome Back
                                </p>

                                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">
                                    Masuk ke Dashboard
                                </h2>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Gunakan akun Anda untuk mengakses sistem manajemen dojang.
                                </p>
                            </div>

                            <div
                                v-if="status"
                                class="mt-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm font-medium text-green-700"
                            >
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="mt-8 space-y-5">
                                <div>
                                    <label
                                        for="email"
                                        class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-400"
                                    >
                                        Email
                                    </label>

                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75"
                                                />
                                            </svg>
                                        </div>

                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            required
                                            autofocus
                                            autocomplete="username"
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium text-slate-900 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                                            placeholder="admin@dojangmaster.com"
                                        />
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div>
                                    <label
                                        for="password"
                                        class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-400"
                                    >
                                        Password
                                    </label>

                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5z"
                                                />
                                            </svg>
                                        </div>

                                        <input
                                            id="password"
                                            v-model="form.password"
                                            type="password"
                                            required
                                            autocomplete="current-password"
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium text-slate-900 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                                            placeholder="••••••••"
                                        />
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <label class="flex items-center">
                                        <Checkbox name="remember" v-model:checked="form.remember" />
                                        <span class="ms-2 text-sm font-medium text-slate-600">
                                            Remember me
                                        </span>
                                    </label>

                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                                    >
                                        Forgot password?
                                    </Link>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:-translate-y-0.5 hover:bg-indigo-700 active:translate-y-0 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="h-4 w-4 animate-spin"
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

                                    <span>
                                        {{ form.processing ? 'Memproses...' : 'Masuk Dashboard' }}
                                    </span>

                                    <svg
                                        v-if="!form.processing"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>
                            </form>

                            <div class="mt-8 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-white">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-slate-900">
                                            Secure dojang access
                                        </p>
                                        <p class="mt-0.5 text-xs leading-5 text-slate-500">
                                            Sistem ini hanya untuk admin, owner, trainer, dan siswa yang terdaftar.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-8 text-center text-xs text-slate-400">
                                Powered by
                                <span class="font-semibold text-slate-700">
                                    Dojang Master
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>