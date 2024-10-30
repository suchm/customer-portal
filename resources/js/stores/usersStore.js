import { defineStore } from 'pinia'
import axios from 'axios'

export let useUsersStore = defineStore( 'users', {

    state: () => ({
        users: []
    }),

    actions: {

        setUsers(users) {
            this.users = users
        },

        addUser(user) {
            // check if user being added is in the list
            if (typeof this.users.find(u => u.id === user.id) !== 'undefined') {
                return
            }

            this.users.push(user)
        },

        removeUser(user) {
            this.users = this.users.filter(u => u.id !== user.id)
        },

        setTyping(e) {
            // find the user who is typing and set their typing state
            this.users.find(u => u.id === e.id).typing = e.typing
        }
    },

    getters: {

        allUsers ($state) {
            return $state.users
        },

        count ($state) {
            return $state.users.length
        }
    }
});
