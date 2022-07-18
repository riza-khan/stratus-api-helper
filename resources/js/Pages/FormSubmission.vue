<template>
    <Head title="Form Test" />

    <BreezeAuthenticatedLayout :flash="flash">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Submission
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="getForm" class="flex gap-1 mb-2">
                    <Select v-model="form.environment" :options="options" />
                    <Input
                        id="configuration"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="form.form"
                    />
                    <Button>Get Form</Button>
                </form>
                <div class="py-5" style="height: 1000px">
                    <Vue3JsonEditor
                        v-model="formToSubmit"
                        :show-btns="true"
                        :expandedOnStart="true"
                        mode="code"
                        @json-save="submitForm"
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
    form: "",
    environment: "uat",
});

const flash = ref({ success: true, message: "" });

const formToSubmit = ref({});

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

const resetFlash = () => {
    flash.value.success = true;
    flash.value.message = "";
};

const getForm = async () => {
    resetFlash();
    try {
        const { data } = await window.axios.get("/api/form", {
            params: {
                ...form.value,
            },
        });

        const { success, message, error } = data;

        if (success) {
            formToSubmit.value = data.form;
            flash.value.success = success;
            flash.value.message = message;
        } else {
            flash.value.success = success;
            flash.value.message = message;
            flash.value.error = error;
        }
    } catch (e) {
        flash.value.success = false;
        flash.value.message = e;
    }
};

const submitForm = async (val) => {
    if (!Object.keys(val).length) return;
    try {
        const { data } = await window.axios.post("/api/form", {
            params: {
                ...form.value,
            },
            body: {
                ...val,
            },
        });

        const { success, message, error } = data;

        if (success) {
            flash.value.success = success;
            flash.value.message = message;
        } else {
            flash.value.success = success;
            flash.value.message = message;
            flash.value.error = error;
        }

        // formToSubmit.value = data.data;
    } catch (e) {
        flash.value.success = false;
        flash.value.message = e;
    }
};
</script>
