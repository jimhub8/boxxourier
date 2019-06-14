<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-layout row>
                    <v-flex sm4 style="max-height: 600px;overflow: scroll;">
                        <v-card>
                            <v-list two-line v-chat-scroll>
                                <div v-for="Chatuser in AllUsers" :key="Chatuser.id" v-if="Chatuser.id != user.id">
                                    <v-divider></v-divider>
                                    <v-list-tile avatar @click="getChatty(Chatuser.id)">
                                        <v-list-tile-avatar>
                                            <v-icon>account_circle</v-icon>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="Chatuser.name"></v-list-tile-title>
                                            <v-list-tile-sub-title v-html="Chatuser.email"></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </div>
                            </v-list>
                        </v-card>
                    </v-flex>
                    <v-divider vertical></v-divider>
                    <v-flex sm7>
                        <v-card style="padding: 40px;max-height: 600px;overflow: scroll;" id="back">
                            <v-layout row v-for="Chatmsg in AllMessages" :key="Chatmsg.id">
                                <v-flex sm5 v-if="Chatmsg.sender.id === user.id" style="background: rgb(19, 47, 81); padding: 10px; color: #fff;" id="chatty">
                                    <div id="bordersR"></div>
                                    <p>{{ Chatmsg.chat }}</p>
                                    <v-chip color="indigo" text-color="white">
                                        <v-avatar>
                                            <v-icon>account_circle</v-icon>
                                        </v-avatar>
                                        {{ Chatmsg.sender.name }}
                                    </v-chip>
                                    <v-divider></v-divider>
                                </v-flex>

                                <v-flex sm5 offset-sm7 v-else id="chat">
                                    <div id="borders"></div>
                                    <p>{{ Chatmsg.chat }}</p>
                                    <v-chip color="orange" text-color="white">
                                        <v-avatar>
                                            <v-icon>account_circle</v-icon>
                                        </v-avatar>
                                        {{ Chatmsg.sender.name }}
                                    </v-chip>
                                    <v-divider></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-card>
                        <input type="text" class="form-control" placeholder="Enter the message" @keyup.enter="sendMsg" v-model="chatty_sms.chat">
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import Pusher from 'pusher-js';
export default {
    name: "Chatty",
    props: ["user"],
    components: {},
    data() {
        return {
            AllUsers: [],
            AllMessages: [],
            chat_id: null,
            chatty_sms: {
                chat: ''
            },
            timer: '',
            channel: null,
            pusher: null,
            message: null
        };
    },
    methods: {
        incomingChat(chatMessage) {
            // if (this.chat_id == chatMessage.message.sender_id) {
            console.log("chatMessage");
            // }else{
            // console.log('oopps')
            // }
        },
        getChatty(id) {
            this.chat_id = id;
            axios
                .post(`/getUserConvById/${id}`)
                .then(response => {
                    console.log(response);
                    // this.loader = false
                    this.AllMessages = response.data;
                })
                .catch(error => {
                    // this.loader = false
                    this.errors = error.response.data.errors;
                });
        },
        sendMsg() {
            axios
                .post(`/saveUserChat/${this.chat_id}`, this.$data.chatty_sms)
                .then(response => {
                    console.log(response);
                    this.chatty_sms.chat = ''
                    this.AllMessages.push(response.data);
                    // this.loader = false
                    //   this.AllMessages = response.data;
                })
                .catch(error => {
                    // this.loader = false
                    this.errors = error.response.data.errors;
                });
        }
    },
    // created() {
    //     this.pusher = new Pusher("c0a8d7b1122459cd74c9", {
    //         encrypted: true,
    //         cluster: "mt1"
    //     })
    //     let that = this;
    //     this.channel = this.pusher.subscribe("chat_channel");
    //     this.channel.bind("chat_save", function (data) {
    //         // alert('woo')
    //         that.$emit("incoming_chat", data);
    //     });
    //     this.$on("incoming_chat", function (chatMessage) {
    //         // alert('woo')
    //         this.incomingChat(chatMessage);
    //     });
    // },

    // created() {
    //     this.timer = window.setInterval(() => {
    //         this.getChatty()
    //     }, 1000)
    // },
    // beforeDestroy() {
    //     clearInterval(this.timer)
    // },
    mounted() {
        axios
            .get("/getUsers")
            .then(response => {
                // this.loader = false
                this.AllUsers = response.data;
            })
            .catch(error => {
                // this.loader = false
                this.errors = error.response.data.errors;
            });
    }
};
</script>

<style scoped>
#chatty {
    background: #f0f0f0 !important;
    padding: 10px !important;
    color: #000 !important;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px,
        rgba(0, 0, 0, 0.137255) 0px 1px 1px 0px,
        rgba(0, 0, 0, 0.117647) 0px 1px 3px 0px !important;
    margin-top: 10px;
}

#chat {
    background: rgba(39, 7, 223, 0.568) !important;
    padding: 10px !important;
    color: #fff !important;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px,
        rgba(0, 0, 0, 0.137255) 0px 1px 1px 0px,
        rgba(0, 0, 0, 0.117647) 0px 1px 3px 0px !important;
    margin-top: 10px;
}

#borders {
    width: 20px !important;
    height: 20px !important;
    margin-left: -25px !important;
    margin-top: -10px !important;
    background: #7662e6 !important;
    border-top-left-radius: 0px !important;
    border-bottom-left-radius: 100% !important;
}

#bordersR {
    width: 20px !important;
    height: 20px !important;
    margin-left: 268px !important;
    margin-top: -10px !important;
    background: #f0f0f0 !important;
    border-top-right-radius: 0px !important;
    border-bottom-right-radius: 100% !important;
}

#back {
    background: #dfddef !important;
}
</style>
