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
            alerts: [],
        };
    },
    getters: {
        firstAlert: (state) => state.alerts[0],
    },
    actions: {
        removeFirstAlert() {
            this.alerts.shift();
        },
        addAlert(alert) {
            this.alerts.push(alert);
        },
    },
});
