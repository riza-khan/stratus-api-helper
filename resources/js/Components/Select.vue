<template>
    <select v-model="modelValue">
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
