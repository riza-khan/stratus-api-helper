<template>
    <Head title="User Token" />

    <BreezeAuthenticatedLayout :flash="flash">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Token
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="font-semibold text-md text-gray-800 leading-tight">
                    Add your token here
                </h2>
                <form @submit.prevent="savePfizerToken" class="flex gap-1">
                    <Input
                        id="token"
                        type="string"
                        required
                        class="px-2 mt-1 block w-full"
                        v-model="form.pfizer_token"
                    />
                    <Button>Add/Update Token</Button>
                </form>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import Input from "@/Components/Input.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Vue3JsonEditor } from "vue3-json-editor";

const form = ref({
    pfizer_token: "",
});

const flash = ref({ success: true, message: "" });

const savePfizerToken = async () => {
    try {
        const { data } = await axios.post("/api/save-pfizer-token", form.value);

        flash.value = { ...data };
    } catch (e) {
        console.log(e.response);
        message.value = { ...e.response.data };
    }
};
</script>
