<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    url: String,
});

const searchValue = ref(null);
const selectedListing = ref(null);

// get movies listing on the movie title
const handleProductionSearch = () => {
    setTimeout(() => {
        router.visit(props.url, {
            method: "get",
            data: { searchValue: searchValue.value },
            preserveState: true,
        });
    }, 150);
};

// get production listing with trashed models and only trashed models
const handleListingType = () => {
    router.visit(props.url, {
        method: "get",
        data: {
            selectedListing: selectedListing.value,
        },
    });
};
</script>
<template>
    <div>
        <div>
            <input
                v-model="searchValue"
                id="searchForm"
                name="searchForm"
                type="search"
                class="rounded-sm border-indigo-400 border-l-8"
                placeholder="search..."
                @input="handleProductionSearch"
            />
            <!-- trashed filter  -->
            <select
                v-model="selectedListing"
                name="trashed"
                id="trashed"
                class="rounded-sm border-indigo-400 border-r-8"
                @change="handleListingType"
            >
                <option value="tashed" disabled selected>tashed</option>
                <option value="withTrashed">with trashed</option>
                <option value="onlyTrashed">only trashed</option>
            </select>
        </div>
    </div>
</template>
