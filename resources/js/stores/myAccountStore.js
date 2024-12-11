import {defineStore} from 'pinia'

export let useMyAccountStore = defineStore('my-account', {
    state: () => ([
        {
            id: 1,
            name: 'Subscriptions',
            href: 'subscriptions.index',
            description: 'View and manage current subscriptions',
            icon: 'fa-solid fa-right-to-bracket'
        },
        {
            id: 2,
            name: 'Chats',
            href: 'chats.index',
            description: 'Read and respond to current or saved messages',
            icon: 'fa-regular fa-message'
        },
        {
            id: 3,
            name: 'Profile',
            href: 'profile.edit',
            description: 'Manage your password, phone number and email',
            icon: 'fa-solid fa-shield-halved'
        },
        {
            id: 4,
            name: 'Orders',
            href: 'orders.index',
            description: 'Access, cancel or request help on your orders',
            icon: 'fa-solid fa-cube'
        },
        {
            id: 5,
            name: 'Help',
            href: 'help',
            description: 'Contact our customer support team',
            icon: 'fa-solid fa-question'
        }
    ]),
});
