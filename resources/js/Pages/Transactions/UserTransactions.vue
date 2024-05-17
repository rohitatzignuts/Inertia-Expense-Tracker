<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import Pagination from "@/Shared/Pagination.vue";
import EditDialog from "./EditDialog.vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
    userTransactions: Object,
    userName: String,
});

const isDialogOpen = ref(false);
const isCreateDialogOpen = ref(false);
const editTransaction = ref();
const transactionMonth = ref("");
const usersArray = ref([]);
const selectedUser = ref("");
const selectedID = computed(() => {
    const user = usersArray.value.find(
        (user) => user.email === selectedUser.value
    );
    return user ? user.id : null;
});

const totalIncome = computed(() => {
    if (props.userTransactions && props.userTransactions.data) {
        return props.userTransactions.data.reduce(
            (accumulator, currentValue) => {
                if (currentValue.type === "income") {
                    return accumulator + currentValue.amount;
                }
                return accumulator;
            },
            0
        );
    }
    return 0;
});

const totalExpense = computed(() => {
    if (props.userTransactions && props.userTransactions.data) {
        return props.userTransactions.data.reduce(
            (accumulator, currentValue) => {
                if (
                    currentValue.type === "expence" ||
                    currentValue.type === "transfer"
                ) {
                    return accumulator + currentValue.amount;
                }
                return accumulator;
            },
            0
        );
    }
    return 0;
});

const getAllUsers = async () => {
    const res = await axios.get("/users/all");
    usersArray.value = res.data;
};

const handleFilters = () => {
    try {
        router.visit(`/${selectedID.value}/transactions`, {
            method: "get",
            data: {
                month: transactionMonth.value ?? null,
            },
        });
    } catch (error) {}
};

const totalBalance = computed(() => totalIncome.value - totalExpense.value);

const openEditDialog = async (id) => {
    isDialogOpen.value = true;
    try {
        const res = await axios.get(`/transactions/${id}/edit`);
        editTransaction.value = res.data;
    } catch (error) {
        console.log(error);
    }
};

onMounted(() => {
    getAllUsers();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                userTransactions
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <div class="flex justify-between mb-4">
                    <div>
                        <input
                            v-model="transactionMonth"
                            type="month"
                            name="date"
                            id="date"
                            class="rounded border-indigo-500 border-l-8 bg-gray-800 text-gray-200"
                            @change="handleFilters"
                        />
                    </div>
                    <button
                        v-if="!$page.props.auth.user.is_admin"
                        class="bg-indigo-500 p-2 rounded text-white font-bold"
                        @click="isCreateDialogOpen = true"
                    >
                        CREATE
                    </button>
                    <div v-else>
                        <select
                            v-model="selectedUser"
                            name="user"
                            id="user"
                            class="rounded border-indigo-500 border-r-8 bg-gray-800 text-gray-200"
                            @change="handleFilters"
                        >
                            <option disabled selected>select user</option>
                            <option v-for="user in usersArray" :key="user.id">
                                {{ user.email }}
                            </option>
                        </select>
                    </div>
                </div>
                <div
                    class="flex justify-between p-4 rounded dark:bg-gray-800 text-gray-50 mb-4"
                >
                    <div>
                        <p>Income</p>
                        <strong class="text-2xl text-green-500">{{
                            totalIncome
                        }}</strong
                        ><sup class="ml-2">₹</sup>
                    </div>
                    <div>
                        <p>Expenses</p>
                        <strong class="text-2xl text-red-500">{{
                            totalExpense
                        }}</strong
                        ><sup class="ml-2">₹</sup>
                    </div>
                    <div>
                        <p>Balance</p>
                        <strong
                            class="text-2xl"
                            :class="
                                totalBalance < 0
                                    ? 'text-red-500'
                                    : 'text-green-500'
                            "
                            >{{ totalBalance }}</strong
                        ><sup class="ml-2">₹</sup>
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
                                v-for="transaction in userTransactions.data"
                                :key="transaction.id"
                                class="hover:bg-gray-100 hover:text-black focus-within:bg-gray-100 focus-within:text-black"
                            >
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 focus:text-indigo-500 cursor-pointer"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ userName }}
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
                            <tr v-if="userTransactions.data.length === 0">
                                <td
                                    class="px-6 py-4 border-t border-gray-500"
                                    colspan="5"
                                >
                                    No userTransactions found.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <pagination class="mt-6" :links="userTransactions.links" />
            </div>
        </div>
        <EditDialog
            :is-dialog-open="isDialogOpen"
            :transaction="editTransaction"
            @handle-dialog-close="isDialogOpen = false"
        />
    </AuthenticatedLayout>
</template>
