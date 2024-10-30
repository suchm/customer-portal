import { defineStore } from 'pinia'
import axios from 'axios'

export let useMessageStore = defineStore( 'messages', {

    state: () => ({
        page: 1,
        messages: [],
        container: null,
        previousSlug: null,
    }),

    actions: {

        fetchState (chatSlug, page = 1) {

        axios.get(`/my-account/chats/${chatSlug}/messages?page=${page}`)
            .then(response => {

                this.page = response.data.meta.current_page

                if ( chatSlug === this.previousSlug) {
                    this.messages = [...this.messages, ...response.data.data] // Push to a new array binding the previous messages
                } else {
                    this.messages = [...response.data.data]
                }

                this.previousSlug = chatSlug;
            })
        },

        fetchPreviousState (chatSlug) {

          this.fetchState(chatSlug, this.page + 1)
        },

        storeMessage (chatSlug, payload) {

            axios.post(`/my-account/chats/${chatSlug}/messages`, payload, {

              headers: {
                  'X-Socket-Id': Echo.socketId()
              }

            })
              .then(response => {
                  this.pushMessage(response.data)
                  this.scrollToTop()
              })
        },

        pushMessage(message) {

            this.messages.pop(); // Prevents duplicate messages loading with pagination

            this.messages = [message, ...this.messages]
        },

        scrollToTop() {
            if ( this.container ) {
                let container = this.container;
                container.scrollTop = 0; // Scroll to the top
            }
        },
    },

    getters: {
      allMessages (state) {
          return state.messages
      }
    }
});
