<template>
    <Head title="Email Template" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Email Template
            </h2>
        </template>

        <div class="py-12 flex">
            <div class="w-2/3 sm:px-6 lg:px-8">
                <form
                    @submit.prevent="handleGetEmailTemplate"
                    class="flex gap-1 mb-2"
                >
                    <Input
                        id="configuration"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="email_template"
                    />
                    <Button>Get Email Template</Button>
                </form>
                <div class="py-5">
                    <Vue3JsonEditor
                        v-model="email_templates[email_template]"
                        :show-btns="true"
                        mode="code"
                        @json-save="updateEmailTemplate"
                    />
                </div>
            </div>
            <div class="w-1/3 flex flex-col px-2">
                <p class="h4 mx-auto font-bold">
                    Recently viewed email templates
                </p>
                <div class="flex flex-col gap-1 mt-3">
                    <button
                        v-for="(template, $template) in email_templates_history"
                        :key="$template"
                        @click="email_template = template"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                    >
                        {{ template }}
                    </button>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { useConfigStore } from "@/Store/config";
import { useAlertStore } from "@/Store/alert.js";
import { Vue3JsonEditor } from "vue3-json-editor";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import Input from "@/Components/Input.vue";

const alertStore = useAlertStore();

const configStore = useConfigStore();
const { email_template, email_templates, email_templates_history, loading } =
    storeToRefs(configStore);

const handleGetEmailTemplate = async () => {
    try {
        loading.value = true;
        const { data } = await window.axios.get("/api/email-template", {
            params: {
                name: email_template.value,
            },
        });

        email_templates.value[email_template.value] = { ...data.data };
    } catch (e) {
        console.log(e);
    } finally {
        loading.value = false;
    }
};

const updateEmailTemplate = async (val) => {
    console.log("val", val);
    try {
        loading.value = true;
        const { data } = await window.axios.put("/api/email-template", {
            params: {
                name: email_template.value,
            },
            body: {
                name: val.TemplateName,
                subject: val.SubjectPart,
                text: val.TextPart,
                html: val.HtmlPart,
            },
        });

        const { alert, success } = data;

        if (success) {
            email_templates.value[email_template.value] = { ...val };
            alertStore.addAlert(alert);
        } else {
            alertStore.addAlert(alert);
        }
    } catch (e) {
        console.log(e);
    } finally {
        loading.value = false;
    }
};
</script>
