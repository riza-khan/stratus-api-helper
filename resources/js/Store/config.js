import { defineStore } from "pinia";

export const useConfigStore = defineStore("config", {
    state: () => {
        return {
            configurations: {},
            form: {
                configuration: "",
                environment: "uat",
            },
            email_templates: [],
            loading: false
        };
    },
    getters: {
        history: (state) => {
            return Object.keys(state.configurations).map((environment) => ({
                environment,
                configs: Object.keys(state.configurations[environment]).map(
                    (config) => config
                ),
            }));
        },
    },
    actions: {},
});
