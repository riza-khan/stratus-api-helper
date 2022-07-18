import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => {
        return {
            user: {},
        };
    },
    getters: {},
    actions: {
        setUser(user) {
            this.user = user;
        },
    },
});
