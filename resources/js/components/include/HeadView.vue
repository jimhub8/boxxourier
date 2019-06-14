<template>
<v-app id="inspire">
    <v-navigation-drawer :clipped="$vuetify.breakpoint.lgAndUp" v-model="drawer" fixed app>
        <v-list dense>
            <v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                <v-layout pa-2 column fill-height class="lightbox white--text">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <div class="subheading">{{ user.name }}</div>
                        <div class="body-1">{{ user.email }}</div>
                    </v-flex>
                </v-layout>
            </v-img>
            <template v-for="item in items">
                <v-layout v-if="item.heading" :key="item.heading" row align-center>
                    <v-flex xs6>
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}
                        </v-subheader>
                    </v-flex>
                    <v-flex xs6 class="text-xs-center">
                        <a href="#!" class="body-2 black--text">EDIT</a>
                    </v-flex>
                </v-layout>
                <v-list-group v-else-if="item.children" v-model="item.model" :key="item.text" :prepend-icon="item.model ? item.icon : item['icon-alt']" append-icon="">
                    <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile v-for="(child, i) in item.children" :key="i" @click="">
                        <router-link :to="child.path" class="v-list__tile v-list__tile--link">
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ child.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </router-link>
                    </v-list-tile>
                </v-list-group>
                <v-list-tile v-else :key="item.text" @click="">
                    <router-link :to="item.path" class="v-list__tile v-list__tile--link">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </router-link>
                </v-list-tile>

            </template>
        </v-list>
    </v-navigation-drawer>
    <v-toolbar dark app :color="color" :clipped-left="$vuetify.breakpoint.lgAndUp" fixed>
        <v-toolbar-title style="width: 600px" class="ml-0 pl-3">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            Boxleo
            <img src="storage/logo1.jpg" alt="" style="width: 60px; height: 60px; border-radius: 25%;">
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-icon @click.stop="right = !right" style="cursor: pointer">apps</v-icon>
            <form action="/logout" method="post">
                <v-btn flat color="white" type="submit">Logout</v-btn>
            </form>
            <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
    </v-toolbar>

</v-app>
</template>

<script>
export default {
    props: ['user', 'role', 'logo'],
    data: () => ({
        dialog: false,
        drawer: null,
        sheet: false,
        color: 'indigo',
        panel: false,
        dialog: false,
        changeColor: 'item.color',
        drawer: true,
        drawerRight: false,
        right: null,
        menu: false,
        mode: '',
        company: {},
        items: [{
                icon: 'home',
                text: 'Dashboard',
                path: '/'
            },
            {
                icon: 'local_shipping',
                text: 'Manage Shipments',
                path: '/shipments',
                can: "user.can['view shipments']"
            },
            {
                icon: 'attach_money',
                text: 'Manage Charges',
                path: '/charges',
                can: "user.can['view charges']"
            },
            {
                icon: 'keyboard_arrow_up',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Users&Roles',
                model: false,
                children: [{
                        text: 'Manage Users',
                        icon: 'account_circle',
                        path: '/users',
                        can: "user.can['view users']"
                    },
                    {
                        text: 'Manage Roles',
                        icon: 'account_circle',
                        path: '/roles',
                        can: "user.can['view roles']"
                    },
                ]
            },
            {
                icon: 'keyboard_arrow_up',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Account Setting',
                model: false,
                children: [{
                        text: 'Invoices',
                        icon: 'book',
                        path: '/invoices'
                    },
                    {
                        text: 'Receipt',
                        icon: 'book',
                        path: '/receipts'
                    },
                ]
            },
            {
                icon: 'settings',
                text: 'Settings',
                path: '/setings'
            },
            {
                icon: 'account_circle',
                text: 'Manage Subscribers',
                path: '/subscribers',
                can: "user.can['view subscribers']"
            },
            {
                icon: 'phonelink',
                text: 'Scan Shipments',
                path: '/scan',
                can: "user.can['view scan']"
            },
            {
                icon: 'book',
                text: 'Reports',
                path: '/reports',
                can: "user.can['view reports']"
            }
        ]
    }),
    mounted() {
        // axios.post('/getLogo')
        //     .then((response) => {
        //         this.company = response.data
        //     })
        //     .catch((error) => {
        //         this.errors = error.response.data.errors
        //     })
    },
}
</script>
