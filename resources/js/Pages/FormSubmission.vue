<template>
    <Head title="Form Test" />

    <BreezeAuthenticatedLayout :flash="flash">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Form Submission
            </h2>
        </template>

        <div class="py-12 flex">
            <div class="w-2/3 sm:px-6 lg:px-8">
                <form @submit.prevent="getForm" class="flex gap-1 mb-2">
                    <Select
                        v-model="submission.environment"
                        :options="options"
                    />
                    <Input
                        id="configuration"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="submission.configuration"
                    />
                    <Button>Get Form</Button>
                </form>
                <div class="py-5" style="height: 1000px">
                    <Vue3JsonEditor
                        v-model="form"
                        :show-btns="true"
                        :expandedOnStart="true"
                        mode="code"
                        @json-save="submitForm"
                        @json-change="jsonChange"
                    />
                </div>
            </div>
            <div class="w-1/3 flex flex-col px-2">
                <p class="h4 mx-auto font-bold">Recently fetched forms</p>
                <div
                    class="flex flex-col gap-1 mt-3"
                    v-for="(
                        { environment, configs }, $environment
                    ) in submissions_history"
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
import { useConfigStore } from "@/Store/config";
import { useAlertStore } from "@/Store/alert.js";
import { Vue3JsonEditor } from "vue3-json-editor";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";

const alertStore = useAlertStore();

const configStore = useConfigStore();
const { loading, submissions, submission, submissions_history } =
    storeToRefs(configStore);

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

const form = computed({
    get: () =>
        submissions.value?.[submission.value.environment]?.[
            submission.value.configuration
        ],
    set: (val) => {
        submissions.value[submission.value.environment][
            submission.value.configuration
        ] = val;
    },
});

const getForm = async () => {
    try {
        loading.value = true;
        const { data } = await window.axios.get("/api/form", {
            params: {
                ...submission.value,
            },
        });

        const { success, alert, form } = data;

        if (success) {
            if (
                !Object.prototype.hasOwnProperty.call(
                    submissions.value,
                    submission.value.environment
                )
            ) {
                submissions.value[submission.value.environment] = {};
            }
            submissions.value[submission.value.environment][
                submission.value.configuration
            ] = { ...form };
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

const submitForm = async (val) => {
    if (!Object.keys(val).length) return;
    try {
        loading.value = true;
        const { data } = await window.axios.post("/api/form", {
            params: {
                ...submission.value,
            },
            body: {
                ...val,
            },
        });

        const { success, alert } = data;

        if (success) {
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

const fetchItemFromHistory = (environment, config) => {
    submission.value.environment = environment;
    submission.value.configuration = config;
};

const jsonChange = (val) => {
    submissions.value[submission.value.environment][
        submission.value.configuration
    ] = { ...val };
};
</script>
