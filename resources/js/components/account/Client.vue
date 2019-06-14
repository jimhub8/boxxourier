<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <template>
                <v-card class="mx-auto" max-width="1400">
                    <v-card-title class="title font-weight-regular justify-space-between">
                        <span>{{ currentTitle }}</span>
                        <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar>
                    </v-card-title>

                    <v-window v-model="step">
                        <v-window-item :value="1">
                            <Profile :user="user"></Profile>
                        </v-window-item>

                        <v-window-item :value="2">
                            <Account></Account>
                        </v-window-item>

                    </v-window>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-btn :disabled="step === 1" flat @click="step--" color="orange">
                            Profile
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn flat :disabled="step === 2" color="primary" depressed @click="step++">
                            Account
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </template>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import Account from './Account';
import Profile from './Profile';

export default {
    props: ["user"],
    components: {
        Account,
        Profile,
    },
    data: () => ({
        // length: 3,
        // window: 0,
        step: 1
    }),
    computed: {
        currentTitle() {
            switch (this.step) {
                case 1:
                    return 'Sign-up'
                case 2:
                    return 'Create a password'
                default:
                    return 'Account created'
            }
        }
    }
}
</script>
