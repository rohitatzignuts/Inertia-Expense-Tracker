<template>
    <div
        class="flex items-center justify-between my-4 p-4 bg-yellow-300 rounded"
    >
        <div class="flex items-center">
            <span class="pr-2">🗑️</span>
            <div class="text-yellow-800 text-sm font-medium">
                <slot />
            </div>
        </div>
        <button
            class="text-yellow-800 hover:underline text-sm"
            tabindex="-1"
            type="button"
            @click="$emit('restore')"
        >
            Restore
        </button>
    </div>
</template>

<script setup lang="ts">
const emits = defineEmits(["restore"]);
</script>
