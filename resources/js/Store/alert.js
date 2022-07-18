import { defineStore } from "pinia";

/*
types: sucess, error, info

interface Alert {
    types: 'succes' | 'error' | 'info'
    title: string
    message?: string
}

*/
export const useAlertStore = defineStore("alert", {
    state: () => {
        return {
            alerts: [
                { type: "success", title: "A great thing was done" },
                {
                    type: "error",
                    title: "A terrible thing happen",
                    message: "Here is more info about that terrible thing",
                },
                {
                    type: "info",
                    title: "This just happen and doesn't really do anything special",
                },
            ],
        };
    },
    getters: {
        firstAlert: (state) => state.alerts[0],
    },
    actions: {
        removeFirstAlert() {
            this.alerts.shift();
        },
        addARandomAlert() {
            this.alerts.push({
                type: "error",
                title: "fooobar",
                message: "grrrrrrrrr",
            });
        },
    },
});
