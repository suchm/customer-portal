import { defineStore } from 'pinia'
import axios from 'axios'

export let useChatsStore = defineStore( 'chats', {

    state: () => ({
        activeChat: {},
        activeChatMessage: {},
        chats: [],
    }),

    actions: {

        setChats(chats) {
            this.chats = chats;
        },

        setActiveChat(activeChat) {
            if ( activeChat ) {
                this.activeChat = this.chats.filter((chat) => chat.id === activeChat.id)[0];
                this.setActiveChatMessage(activeChat);
            }
        },

        setActiveChatMessage() {
            this.activeChatMessage = this.activeChat.message[0];
        },

        updateLastRead(chat) {

        },
    },

    getters: {
        allChats (state) {
            return state.chats
        },

        getActiveChat(state) {
            return state.activeChat
        },

        getActiveChatMessage(state) {
            return state.activeChatMessage
        }
    }
});
