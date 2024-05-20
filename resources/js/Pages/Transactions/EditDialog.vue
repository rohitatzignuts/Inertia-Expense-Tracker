<script setup>
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    isDialogOpen: Boolean,
    transaction: {
        type: Object,
        default: () => ({
            type: "",
            description: "",
            category: "",
            amount: "",
            created_at: "",
        }),
    },
});

const emits = defineEmits(["handleDialogClose"]);

// store selected transaction data into editForm ref
let editForm = ref({
    type: props.transaction.type,
    description: props.transaction.description,
    category: props.transaction.category,
    amount: props.transaction.amount,
});

// format transaction date
const dateFormatter = (date) => {
    let newDate = new Date(date);
    let formattedDate = newDate.toLocaleDateString();
    return formattedDate;
};

// edit transaction data
const handletransactionEdit = () => {
    try {
        router.visit(`/transactions/${props.transaction.id}/update`, {
            method: "put",
            data: editForm,
        });
        // close edit dialog
        emits("handleDialogClose");
    } catch (error) {
        console.log(error);
    }
};

// delete transaction
const handletransactionDelete = () => {
    try {
        router.visit(`/transactions/${props.transaction.id}`, {
            method: "delete",
        });
        // close delete dialog
        emits("handleDialogClose");
    } catch (error) {
        console.log(error);
    }
};

// update edit form data when transaction changes
watch(
    () => props.transaction,
    (newtransaction) => {
        editForm = newtransaction;
    },
    { immediate: true }
);
</script>

<template>
    <!-- edit transaction dialog  -->
    <TransitionRoot as="template" :show="isDialogOpen">
        <Dialog class="relative z-10" @close="emits('handleDialogClose')">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <!-- edit transaction dialog content  -->
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-0white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <p class="font-bold">Edit transaction</p>
                                <small>{{
                                    dateFormatter(transaction.created_at)
                                }}</small>

                                <hr class="my-4" />
                                <!-- edit transaction form  -->
                                <div>
                                    <form action="">
                                        <!-- transaction amount input  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="transaction.amount"
                                                type="text"
                                                name="amount"
                                                id="amount"
                                                required
                                                placeholder="Amount ..."
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <!-- transaction amount description  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="
                                                    transaction.description
                                                "
                                                type="text"
                                                name="description"
                                                id="description"
                                                placeholder="Description ..."
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <!-- transaction amount category  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="transaction.category"
                                                type="text"
                                                name="category"
                                                id="category"
                                                placeholder="Category ..."
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <!-- transaction amount type  -->
                                        <div class="flex flex-col mb-4">
                                            <select
                                                v-model="transaction.type"
                                                name="type"
                                                id="type"
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            >
                                                <option value="income">
                                                    income
                                                </option>
                                                <option value="expence">
                                                    expence
                                                </option>
                                                <option value="transfer">
                                                    transfer
                                                </option>
                                            </select>
                                        </div>
                                        <!-- transaction action buttons -->
                                        <div
                                            class="flex gap-2 justify-end p-2 rounded"
                                        >
                                            <!-- transaction edit button -->
                                            <button
                                                type="button"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 sm:mt-0 sm:w-auto"
                                                @click="handletransactionEdit"
                                            >
                                                EDIT
                                            </button>
                                            <!-- transaction delete button -->
                                            <button
                                                type="button"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 sm:mt-0 sm:w-auto border-red-500"
                                                @click="handletransactionDelete"
                                            >
                                                DELETE
                                            </button>
                                            <!-- dialog close button -->
                                            <button
                                                type="button"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-red-400 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                                @click="
                                                    emits('handleDialogClose')
                                                "
                                                ref="cancelButtonRef"
                                            >
                                                CLOSE
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
