<script setup>
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { usePage } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    isDialogOpen: Boolean,
    auth: Object,
});

const emits = defineEmits(["handleDialogClose"]);
const page = usePage();
const userID = computed(() => page.props.auth.user.id);

const createForm = useForm({
    user_id: userID.value,
    description: null,
    amount: null,
    type: null,
    category: null,
});

const handleFormSubmission = () => {
    try {
        router.visit("transactions/store", {
            method: "post",
            data: createForm,
        });
        emits("handleDialogClose");
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
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
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-0white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <p class="font-bold">Create User</p>
                                <hr class="my-4" />
                                <div>
                                    <form
                                        action=""
                                        @submit.prevent="handleFormSubmission"
                                    >
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.amount"
                                                type="number"
                                                name="amount"
                                                id="amount"
                                                placeholder="Amount ..."
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                                required
                                            />
                                        </div>
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.description"
                                                type="text"
                                                name="description"
                                                id="description"
                                                placeholder="Description ..."
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.category"
                                                type="text"
                                                name="category"
                                                id="category"
                                                placeholder="Category ..."
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <div class="flex flex-col mb-4">
                                            <select
                                                v-model="createForm.type"
                                                name="type"
                                                id="type"
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            >
                                                <option
                                                    value="Select role"
                                                    disabled
                                                    selected
                                                >
                                                    Select role
                                                </option>
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
                                        <div
                                            class="flex gap-2 justify-end p-2 rounded"
                                        >
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
                                            <button
                                                type="submit"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                                ref="cancelButtonRef"
                                            >
                                                CREATE
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
