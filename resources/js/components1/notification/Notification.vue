<template>
<div class="text-xs-center">
    <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y transition="slide-y-transition">
        <v-btn icon slot="activator" dark>
            <v-badge color="purple" left overlap>
                <!-- <v-icon slot="badge" dark small>done</v-icon> -->
                <!-- {{notifications.length}} -->
                <span slot="badge">{{ notifications.length }}</span>
                <v-icon color="grey lighten-1" large>
                    add_alert
                </v-icon>
            </v-badge>
        </v-btn>

        <v-card>
            <v-list>
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <avatar :username="user.name" style="font-size: 20px;margin: auto;padding: 0px;"></avatar>
                        <!-- <img :src="user.profile" alt="John"> -->
                    </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ user.name }}</v-list-tile-title>
                        </v-list-tile-content>

                        <v-list-tile-action v-if="notifications.length > 0">
                            <v-tooltip bottom>
                                <v-btn slot="activator" :class="fav ? 'red--text' : ''" icon @click="read()">
                                    <v-icon>check_circle</v-icon>
                                </v-btn>
                                <span>Mark as read</span>
                            </v-tooltip>
                        </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-divider></v-divider>

            <v-list>
                <v-list-tile v-for="notification in notifications" :key="notification.id" v-if="notifications.length > 0">
                    <v-list-tile-action>
                        <!-- <v-switch v-model="message" color="purple"></v-switch> -->
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon class="mx-0" @click="noty(notification.id)">
                                <v-icon small color="blue darken-2">visibility</v-icon>
                            </v-btn>
                            <span>View Shipment</span>
                        </v-tooltip>
                    </v-list-tile-action>
                    <v-list-tile-title>New Shipment added</v-list-tile-title>
                    <!-- <v-tooltip bottom>
                        <v-btn slot="activator" icon class="mx-0" @click="read(notification.id)">
                            <v-icon small color="blue darken-2">check_circle</v-icon>
                        </v-btn>
                        <span>Mark as read</span>
                    </v-tooltip> -->
                </v-list-tile>

                <v-list-tile v-else>
                    <v-list-tile-title>No Notifications</v-list-tile-title>
                </v-list-tile>
            </v-list>

            <v-card-actions>
                <v-btn flat @click="menu = false">close</v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>
    <NotyShipment @closeRequest="close" :shipment="seeShipment" :openRequest="notyShow" :user="user"></NotyShipment>
</div>
</template>

<script>
import NotyShipment from "./NotyShipment";
import Avatar from "vue-avatar";
export default {
  props: ["user"],
  components: {
    NotyShipment,
    Avatar
  },
  data: () => ({
    fav: true,
    menu: false,
    message: false,
    notyShow: false,
    hints: true,
    notifications: [],
    seeShipment: []
  }),
  methods: {
    noty(item) {
      // console.log(item)
      // this.editedIndex = this.notifications.indexOf(item)
      // this.seeShipment = item
      axios.post(`/Notyshpments/${item}`).then(response => {
        console.log(response.data);
        this.seeShipment = response.data;
      });
      this.notyShow = true;
    },
    read() {
      axios.post("/read").then(response => {
        this.fav = false;
        this.notifications = response.data;
        // this.Allusers.splice(index, 1)
        // this.notifications.splice(index, 1)
      });
    },

    getnotifications() {
      axios
        .get("/notifications")
        .then(response => {
          this.notifications = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors;
        });
    },
    close() {
      this.notyShow = false;
    }
  },
  created() {
    this.timer = window.setInterval(() => {
      this.getnotifications();
    }, 120000);

  },
  // beforeDestroy() {
  //   clearInterval(this.timer);
  // },
  mounted() {
    axios
      .get("/notifications")
      .then(response => {
        this.loader = false;
        this.notifications = response.data;
      })
      .catch(error => {
        this.loader = false;
        this.errors = error.response.data.errors;
      });
  }
};
</script>

<style scoped>
/* .v-menu--inline {
    margin-top: -60px;
    float: right;
} */
</style>
