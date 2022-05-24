<template>
    <Head title="Email Template" />

    <BreezeAuthenticatedLayout :flash="flash">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Email Template
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form
                    @submit.prevent="handleGetEmailTemplate"
                    class="flex gap-1 mb-2"
                >
                    <Select v-model="form.environment" :options="options" />
                    <Input
                        id="configuration"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="form.name"
                    />
                    <Button>Get Email Template</Button>
                </form>
                <div class="py-5">
                    <Vue3JsonEditor
                        v-model="emailTemplate"
                        :show-btns="true"
                        mode="code"
                        @json-save="updateEmailTemplate"
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
    name: "",
    environment: "uat",
});

const flash = ref({ success: true, message: "" });
const emailTemplate = ref({});

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

const handleGetEmailTemplate = async () => {
    try {
        const { data } = await axios.get("/api/email-template", {
            params: {
                ...form.value,
            },
        });

        emailTemplate.value = data.data;
    } catch (e) {
        console.log(e);
    }
};

const updateEmailTemplate = async (val) => {
    try {
        const { data } = await axios.put("/api/email-template", {
            params: {
                ...form.value,
            },
            body: {
                name: val.TemplateName,
                subject: val.SubjectPart,
                text: val.TextPart,
                html: val.HtmlPart,
            },
        });
        console.log("updated");
    } catch (e) {
        console.log(e);
    }
};
</script>
