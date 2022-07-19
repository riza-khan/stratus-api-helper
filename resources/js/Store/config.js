import { defineStore } from "pinia";

export const useConfigStore = defineStore("config", {
    state: () => {
        return {
            configurations: {},
            form: {
                configuration: "",
                environment: "uat",
            },
            email_templates: {},
            email_template: "",
            loading: false,
        };
    },
    getters: {
        configurations_history: (state) => {
            return Object.keys(state.configurations).map((environment) => ({
                environment,
                configs: Object.keys(state.configurations[environment]).map(
                    (config) => config
                ),
            }));
        },
        email_templates_history: (state) => Object.keys(state.email_templates),
    },
    actions: {},
});