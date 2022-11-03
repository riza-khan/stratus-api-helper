<template>
    <Head title="Form Configuration" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Configuration
            </h2>
        </template>

        <div class="py-12 flex">
            <div class="w-2/3 sm:px-6 lg:px-8">
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
                    <Button
                        type="button"
                        :disabled="
                            Object.keys(configuration).length ||
                            !form.configuration
                        "
                        @click="handleCreateNewClick"
                        >Create New</Button
                    >
                </form>
                <div class="py-5">
                    <Vue3JsonEditor
                        v-model="configuration"
                        :show-btns="true"
                        :expandedOnStart="true"
                        mode="code"
                        @json-save="saveConfiguration"
                    />
                </div>
            </div>
            <div class="w-1/3 flex flex-col px-2">
                <p class="h4 mx-auto font-bold">
                    Recently viewed configurations
                </p>
                <div
                    class="flex flex-col gap-1 mt-3"
                    v-for="(
                        { environment, configs }, $environment
                    ) in configurations_history"
                    :key="$environment"
                >
                    <p class="text-center text-stone-500">{{ environment }}</p>
                    <button
                        v-for="(config, $config) in configs"
                        :key="$config"
                        @click="fetchItemFromHistory(environment, config)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                    >
                        {{ config }}
                    </button>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { computed } from "vue";
import { storeToRefs } from "pinia";
import { useAlertStore } from "../Store/alert.js";
import { useConfigStore } from "../Store/config.js";
import { Vue3JsonEditor } from "vue3-json-editor";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";

const configStore = useConfigStore();
const { configurations, configurations_history, form, loading } =
    storeToRefs(configStore);

const alertStore = useAlertStore();

const configuration = computed({
    get: () =>
        configurations.value?.[form.value.environment]?.[
            form.value.configuration
        ] ?? {},
    set: (val) => {
        configurations.value[form.value.environment][form.value.configuration] =
            val;
    },
});

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
        loading.value = true;
        const { data } = await window.axios.get("/api/configuration", {
            params: {
                ...form.value,
            },
        });

        const { success, alert } = data;

        if (success) {
            if (
                !Object.prototype.hasOwnProperty.call(
                    configurations.value,
                    form.value.environment
                )
            ) {
                configurations.value[form.value.environment] = {};
            }
            configurations.value[form.value.environment][
                form.value.configuration
            ] = { ...data.data };
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

const saveConfiguration = async (val) => {
    try {
        loading.value = true;
        const { data } = await window.axios.put("/api/configuration", {
            params: {
                ...form.value,
            },
            body: {
                ...val,
            },
        });

        const { success, alert } = data;

        if (success) {
            configurations.value[form.value.environment][
                form.value.configuration
            ] = data.data;
        }
        alertStore.addAlert(alert);
    } catch (e) {
        console.log(e.response);
    } finally {
        loading.value = false;
    }
};

const fetchItemFromHistory = (environment, config) => {
    form.value.environment = environment;
    form.value.configuration = config;
};

const handleCreateNewClick = async () => {
    try {
        loading.value = true;
        const { data } = await window.axios.post("/api/configuration", {
            params: {
                ...form.value,
            },
            body: {
                configToken: form.value.configuration,
                name: form.value.configuration.split("-").join(""),
                siteName: "pfizerpro",
                service: "ms-form",
                domain: "pfizerpro.com",
                region: "US",
                description: "To update",
                active: true,
                config: {
                    staticRequestData: {
                        dateTime: {
                            FuncRef: {
                                "Conf:Func": "generate_current_time",
                                "Conf:Arguments": ["EST", "%d-%b-%Y %H:%M:%S"],
                            },
                        },
                    },
                    fields: [
                        {
                            name: "submit",
                            id: "submit",
                            label: "Submit",
                            type: "button",
                            order: 0,
                        },
                    ],
                    types: ["email"],
                    email: [
                        {
                            cc: [],
                            template: "AskAQuestionByEmailUSTemplateUAT",
                            bcc: [],
                            subject: "#{{questionId}} - New Question",
                            templateDataMapping: {
                                dateTime: {
                                    FieldRef: "dateTime",
                                },
                                question: {
                                    FieldRef: "question",
                                },
                                hcpEmail: {
                                    FieldRef: "hcpEmail",
                                },
                                hcpName: {
                                    FieldRef: "hcpName",
                                },
                                grvId: {
                                    FieldRef: "grvId",
                                },
                                hcpZipcode: {
                                    FieldRef: "hcpZipcode",
                                },
                                hcpAddress: {
                                    FieldRef: "hcpAddress",
                                },
                            },
                            replyTo: "no-reply@pfizer.com",
                            fromText: "No reply",
                            description: "This is description",
                            from: "no-reply@pfizer.com",
                            to: ["RizaHasan.Khan@pfizer.com"],
                        },
                    ],
                },
            },
        });

        const { alert, success } = data;

        if (success) {
            configurations.value[form.value.environment][
                form.value.configuration
            ] = data.data;
        }

        alertStore.addAlert(alert);
    } catch {
        // do nothing
    } finally {
        loading.value = false;
    }
};
</script>
