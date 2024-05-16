<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CreatDialog from "./CreatDialog.vue";
import { ref } from "vue";
import Pagination from "@/Shared/Pagination.vue";
import EditDialog from "./EditDialog.vue";

const props = defineProps({
    transactions: Object,
});
const isDialogOpen = ref(false);
const isCreateDialogOpen = ref(false);
const editTransaction = ref();

const openEditDialog = async (id) => {
    isDialogOpen.value = true;
    try {
        const res = await axios.get(`/transactions/${id}/edit`);
        editTransaction.value = res.data;
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Transactions
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 absolute w-full">
                <div class="text-right mb-4">
                    <button
                        class="bg-indigo-500 p-2 rounded text-white"
                        @click="isCreateDialogOpen = true"
                    >
                        CREATE
                    </button>
                </div>
                <div
                    class="flex justify-between p-4 rounded dark:bg-gray-800 text-gray-50 mb-4"
                >
                    <div>
                        <p>Income</p>
                        <strong class="text-2xl">52</strong>
                    </div>
                    <div>
                        <p>Expenses</p>
                        <strong class="text-2xl">10</strong>
                    </div>
                    <div>
                        <p>Balance</p>
                        <strong class="text-2xl">42</strong>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="text-white rounded-md shadow overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <tr class="text-left font-bold">
                                <th class="pb-4 pt-6 px-6">BY</th>
                                <th class="pb-4 pt-6 px-6">Description</th>
                                <th class="pb-4 pt-6 px-6">Amount</th>
                                <th class="pb-4 pt-6 px-6">Type</th>
                                <th class="pb-4 pt-6 px-6">category</th>
                            </tr>
                            <tr
                                v-for="transaction in transactions.data"
                                :key="transaction.id"
                                class="hover:bg-gray-100 hover:text-black focus-within:bg-gray-100 focus-within:text-black"
                            >
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 focus:text-indigo-500 cursor-pointer"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.description }}
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.amount }} ₹
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.type }}
                                    </div>
                                </td>
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.category }}
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="transactions.length === 0">
                                <td
                                    class="px-6 py-4 border-t border-gray-500"
                                    colspan="4"
                                >
                                    No transactions found.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <pagination class="mt-6" :links="transactions.links" />
            </div>
        </div>
        <CreatDialog
            :isDialogOpen="isCreateDialogOpen"
            @handle-dialog-close="isCreateDialogOpen = false"
        />
        <EditDialog
            :is-dialog-open="isDialogOpen"
            :transaction="editTransaction"
            @handle-dialog-close="isDialogOpen = false"
        />
    </AuthenticatedLayout>
</template>
