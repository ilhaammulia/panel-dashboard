<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    username: '',
    password: '',
});

const submit = () => {
    form.transform(data => ({
        ...data,
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <form @submit.prevent="submit" class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Welcome, back!</div>
                        <span class="text-600 font-medium">Sign in to continue</span>
                    </div>

                    <div>
                        <label for="username" class="block text-900 text-xl font-medium mb-2">Username or Email</label>
                        <InputText id="username" type="text" placeholder="Username or Email" class="w-full md:w-30rem" style="padding: 1rem" v-model="form.username" />
                        <p class="text-red-500">{{ form.errors.username }}</p>

                        <label for="password" class="block text-900 font-medium text-xl mb-2">Password</label>
                        <Password id="password" v-model="form.password" placeholder="Password" :feedback="false" :toggleMask="true" class="w-full" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
                        <p class="text-red-500">{{ form.errors.password }}</p>

                        <Button label="Sign In" type="submit" class="w-full p-3 text-xl" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"></Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
