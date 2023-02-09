<template>
    <Head title="Welcome" />

    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
    >
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 text-white">
            <p class="text-4xl">Pfizerpro Stratus Helper</p>
            <p>
                Interact with the Stratus API, but without the complexity of
                Postman
            </p>
            <div v-if="canLogin" class="flex py-4 justify-end">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('user-token')"
                    class="text-sm text-gray-400"
                >
                    Login
                </Link>

                <template v-else>
                    <Link :href="route('login')" class="text-sm text-gray-400">
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="ml-4 text-sm text-gray-400"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>
