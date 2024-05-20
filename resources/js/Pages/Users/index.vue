<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import EditDialog from "./EditDialog.vue";
import CreatDialog from "./CreatDialog.vue";
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
    users: Array,
});

const isDialogOpen = ref(false);
const isCreateDialogOpen = ref(false);
const editUser = ref();

const openEditDialog = async (id) => {
    isDialogOpen.value = true;
    try {
        const res = await axios.get(`/users/${id}/edit`);
        editUser.value = res.data;
    } catch (error) {
        console.log(error);
    }
};

const closeEditDialog = () => {
    isDialogOpen.value = false;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Users
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <div class="text-right mb-4">
                    <button
                        class="bg-indigo-500 p-2 rounded text-white font-bold"
                        @click="isCreateDialogOpen = true"
                    >
                        CREATE
                    </button>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="text-white rounded-md shadow overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <tr class="text-left font-bold">
                                <th class="pb-4 pt-6 px-6">Name</th>
                                <th class="pb-4 pt-6 px-6">Email</th>
                                <th class="pb-4 pt-6 px-6" colspan="2">Role</th>
                            </tr>
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-gray-100 hover:text-black focus-within:bg-gray-100 focus-within:text-black"
                            >
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 focus:text-indigo-500 cursor-pointer"
                                        @click="openEditDialog(user.id)"
                                    >
                                        {{ user.name }}
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(user.id)"
                                    >
                                        {{ user.email }}
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(user.id)"
                                    >
                                        {{ user.is_admin ? "Admin" : "User" }}
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td
                                    class="px-6 py-4 border-t border-gray-500"
                                    colspan="4"
                                >
                                    No users found.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <EditDialog
            :is-dialog-open="isDialogOpen"
            :user="editUser"
            @handle-dialog-close="closeEditDialog"
        />
        <CreatDialog
            :isDialogOpen="isCreateDialogOpen"
            @handle-dialog-close="isCreateDialogOpen = false"
        />
    </AuthenticatedLayout>
</template>
