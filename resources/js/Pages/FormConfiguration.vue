<template>
    <Head title="Form Configuration" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Configuration
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form
                    @submit.prevent="handleGetConfiguration"
                    class="flex gap-1 mb-2"
                >
                    <Select v-model="form.environment" :options="options" />
                    <Input
                        id="configuration"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="form.configuration"
                    />
                    <Button>Get Configuration</Button>
                </form>
                <div class="py-5" style="height: 1000px">
                    <Vue3JsonEditor
                        v-model="configuration"
                        :show-btns="true"
                        :expandedOnStart="true"
                        mode="code"
                        @json-save="saveConfiguration"
                    />
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Vue3JsonEditor } from "vue3-json-editor";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";

const form = ref({
    configuration: "",
    environment: "uat",
});

const configuration = ref({});

const options = [
    {
        text: "Staging",
        value: "staging",
    },
    {
        text: "UAT",
        value: "uat",
    },
    {
        text: "Production",
        value: "production",
    },
];

const handleGetConfiguration = async () => {
    try {
        const { data } = await axios.get("/api/configuration", {
            params: {
                ...form.value,
            },
        });

        configuration.value = data.data;
    } catch (e) {
        console.log(e);
    }
};

const saveConfiguration = async (val) => {
    try {
        const { data } = await axios.put("/api/configuration", {
            params: {
                ...form.value,
            },
            body: {
                ...val,
            },
        });

        configuration.value = data.data;
    } catch (e) {
        console.log(e.response);
    }
};
</script>
