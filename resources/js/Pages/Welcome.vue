<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div
            class="min-h-screen bg-gray-100 dark:bg-gray-900 grid place-items-center mx-auto"
        >
            <main class="text-white">
                <p class="text-4xl">
                    Welcome to the
                    <span class="font-bold text-green-500"
                        >Expense Manager</span
                    >
                </p>
                <p class="text-xl text-gray-300">
                    manage all of your expenses at one place
                </p>
                <!-- direct user to dashboard if he's logged in  -->
                <div v-if="$page.props.auth.user" class="my-8">
                    <Link href="/dashboard">
                        <p
                            class="bg-green-500 inline px-6 py-3 rounded font-bold"
                        >
                            Lets Go!!
                        </p>
                    </Link>
                </div>
                <!-- direct user to login or register if he's not logged in  -->
                <div v-else class="my-8">
                    <Link href="/login">
                        <p
                            class="bg-green-500 inline px-6 py-3 rounded font-bold"
                        >
                            Login
                        </p>
                    </Link>
                    <Link href="/register" class="ml-2">
                        <p
                            class="bg-green-500 inline px-6 py-3 rounded font-bold"
                        >
                            Register
                        </p>
                    </Link>
                </div>
            </main>
        </div>
    </div>
</template>
