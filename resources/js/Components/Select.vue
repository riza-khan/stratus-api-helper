<template>
    <select
        v-model="modelValue"
        class="form-select appearance-none block w-1/4 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
    >
        <option
            v-for="({ text, value }, $option) in props.options"
            :value="value"
            :key="$option"
        >
            {{ text }}
        </option>
    </select>
</template>

<script setup>
import { watch, defineProps, defineEmits } from "vue";

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    modelValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

watch(
    () => props.modelValue,
    (val) => emit("update:modelValue", val)
);
</script>
