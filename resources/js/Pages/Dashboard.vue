<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    loggedUsers: Array,
    currentUser: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>
        <!-- main content  -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <!-- show list of users to admins  -->
                    <div
                        v-if="currentUser.is_admin === 1"
                        class="p-6 text-gray-900 dark:text-gray-100"
                    >
                        <div class="font-semibold uppercase">
                            Logged In users
                        </div>
                        <hr class="opacity-15 my-4" />
                        <ul v-for="user in loggedUsers" :key="user.id">
                            <Link :href="`${user.id}/transactions`">
                                <li class="text-xl pb-2">~ {{ user.name }}</li>
                            </Link>
                        </ul>
                    </div>
                    <!-- show message for normal users  -->
                    <div v-else class="text-white p-4">you are logged in!!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
