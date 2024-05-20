<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CreatDialog from "./CreatDialog.vue";
import { ref } from "vue";
import Pagination from "@/Shared/Pagination.vue";
import EditDialog from "./EditDialog.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    transactions: Object,
    totalIncome: Number | String,
    totalExpence: Number | String,
    userTransactions: Object,
});

const isDialogOpen = ref(false);
const isCreateDialogOpen = ref(false);
const editTransaction = ref();
const transactionMonth = ref("");

const totalIncome = props.totalIncome;
const totalExpense = props.totalExpence;
const totalBalance = ref(totalIncome - totalExpense);

// filter transactions by month
const handleFilterbyDate = () => {
    try {
        router.visit(`/transactions`, {
            method: "get",
            data: {
                month: transactionMonth.value,
            },
        });
    } catch (error) {
        console.log(error);
    }
};

// fill the input feilds with the data of clicked transaction
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

        <!-- main content  -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <div class="flex justify-between mb-4">
                    <!-- select month filter  -->
                    <div>
                        <input
                            v-model="transactionMonth"
                            type="month"
                            name="date"
                            id="date"
                            class="rounded border-indigo-500 border-l-8 bg-gray-800 text-gray-200"
                            @change.prevent="handleFilterbyDate"
                        />
                    </div>
                    <!-- create transaction button  -->
                    <button
                        class="bg-indigo-500 p-2 rounded text-white font-bold"
                        @click="isCreateDialogOpen = true"
                    >
                        CREATE
                    </button>
                </div>
                <!-- transaction statistics  -->
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
                <!-- transactions list  -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="text-white rounded-md shadow overflow-x-auto">
                        <!-- transactions table  -->
                        <table class="w-full whitespace-nowrap">
                            <!-- transactions table headers -->
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
                                <!-- transaction done by  -->
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 focus:text-indigo-500 cursor-pointer"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                </td>
                                <!-- transaction description  -->
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.description }}
                                    </div>
                                </td>
                                <!-- transaction amount  -->
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.amount }} ₹
                                    </div>
                                </td>
                                <!-- transaction type  -->
                                <td class="border-t border-gray-500">
                                    <div
                                        class="flex items-center px-6 py-4 cursor-pointer"
                                        :class="
                                            transaction.type === 'income'
                                                ? 'text-green-500 '
                                                : 'text-red-500'
                                        "
                                        tabindex="-1"
                                        @click="openEditDialog(transaction.id)"
                                    >
                                        {{ transaction.type }}
                                    </div>
                                </td>
                                <!-- transaction category  -->
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
                            <!-- No transactions found message  -->
                            <tr v-if="transactions.data.length === 0">
                                <td
                                    class="px-6 py-4 border-t border-gray-500"
                                    colspan="5"
                                >
                                    No transactions found.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- pagination  -->
                <pagination class="mt-6" :links="transactions.links" />
            </div>
        </div>
        <!-- create transaction dialog  -->
        <CreatDialog
            :isDialogOpen="isCreateDialogOpen"
            @handle-dialog-close="isCreateDialogOpen = false"
        />
        <!-- edit transaction dialog  -->
        <EditDialog
            :is-dialog-open="isDialogOpen"
            :transaction="editTransaction"
            @handle-dialog-close="isDialogOpen = false"
        />
    </AuthenticatedLayout>
</template>
