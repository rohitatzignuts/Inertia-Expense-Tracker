<script setup>
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    isDialogOpen: Boolean,
});

const emits = defineEmits(["handleDialogClose"]);

// user form data
const createForm = useForm({
    name: null,
    password: null,
    email: null,
    is_admin: null,
});

// create a new user
const handleUserCreate = () => {
    try {
        router.visit("users/store", {
            method: "post",
            data: createForm,
        });
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <!-- create user dialog  -->
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
                        <!-- create user dialog content  -->
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-0white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <p class="font-bold">Create User</p>
                                <hr class="my-4" />
                                <div>
                                    <!-- create user form  -->
                                    <form
                                        action=""
                                        @submit.prevent="handleUserCreate"
                                    >
                                        <!-- user name input  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.name"
                                                type="text"
                                                name="name"
                                                id="name"
                                                placeholder="Name ..."
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                                required
                                            />
                                        </div>
                                        <!-- user password input  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.password"
                                                type="password"
                                                name="password"
                                                id="password"
                                                placeholder="password ..."
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <!-- user email input  -->
                                        <div class="flex flex-col mb-4">
                                            <input
                                                v-model="createForm.email"
                                                type="email"
                                                name="email"
                                                id="email"
                                                placeholder="Email ..."
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            />
                                        </div>
                                        <!-- user type input  -->
                                        <div class="flex flex-col mb-4">
                                            <select
                                                v-model="createForm.is_admin"
                                                name="role"
                                                id="role"
                                                required
                                                class="border-b-2 border-t-0 bo border-x-0 rounded"
                                            >
                                                <option
                                                    value=""
                                                    disabled
                                                    selected
                                                >
                                                    Select role
                                                </option>
                                                <option value="admin">
                                                    Admin
                                                </option>
                                                <option value="user">
                                                    user
                                                </option>
                                            </select>
                                        </div>
                                        <!-- user form actions -->
                                        <div
                                            class="flex gap-2 justify-end p-2 rounded"
                                        >
                                            <!-- create user dialog close button  -->
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
                                            <!-- user form submit button -->
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
