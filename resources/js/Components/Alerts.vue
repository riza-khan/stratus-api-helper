<template>
    <div
        v-if="show"
        class="absolute top-12 right-3 border px-2 py-3 rounded flex"
        :class="type?.wrapper"
        role="alert"
    >
        <div class="my-auto">
            <strong class="font-bold mr-1">{{ firstAlert?.title }}</strong>
            <p class="max-w-sm">
                {{ firstAlert?.message }}
            </p>
        </div>
        <span class="px-2 py-3">
            <svg
                @click="alertStore.removeFirstAlert()"
                class="fill-current h-6 w-6"
                :class="type?.close"
                role="button"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                />
            </svg>
        </span>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useAlertStore } from "@/Store/alert.js";
import { storeToRefs } from "pinia";

const alertStore = useAlertStore();
const { alerts, firstAlert } = storeToRefs(alertStore);

const show = computed(() => !!alerts.value.length);
const type = computed(() => {
    const types = {
        error: {
            wrapper: "bg-red-50 border-red-500",
            close: "text-red-500 hover:bg-red-200",
        },
        success: {
            wrapper: "bg-green-50 border-green-500",
            close: "text-green-500 hover:bg-green-200",
        },
        info: {
            wrapper: "bg-yellow-50 border-green-500",
            close: "text-yellow-500 hover:bg-yellow-200",
        },
    };

    return types[firstAlert?.value?.type] ?? false;
});
</script>
