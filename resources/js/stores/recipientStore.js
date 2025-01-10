import {defineStore} from 'pinia'
import axios from 'axios'

export let useRecipientStore = defineStore('recipients', {

    state: () => ({
        recipients: [],
    }),

    actions: {

        fetchState(chatSlug, page = 1) {

            axios.get(`/my-account/recipients`)
                .then(response => {

                    this.recipients = [...this.recipients, ...response.data.data] // Push to a new array binding the previous messages
                })
        },
    },

    getters: {
        allRecipients(state) {
            return state.recipients
        }
    }
});
