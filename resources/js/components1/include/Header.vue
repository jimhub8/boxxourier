<template>
<div>
    <v-app id="inspire">
        <!-- temporary -->
        <vue-topprogress ref="topProgress"></vue-topprogress>
        <v-navigation-drawer right temporary v-model="right" fixed></v-navigation-drawer>
        <!-- temporary -->
        <v-navigation-drawer fixed :color="color" :clipped="$vuetify.breakpoint.lgAndUp" app v-model="drawer">
            <v-list dense id="navigation">
                <v-img :aspect-ratio="16/9" src="/storage/ps/landS.jpg">
                    <v-layout pa-2 column fill-height class="lightbox white--text">
                        <v-spacer></v-spacer>
                        <v-flex shrink>
                            <div class="subheading">{{ user.name }}</div>
                            <div class="body-1">{{ user.email }}</div>
                        </v-flex>
                    </v-layout>
                </v-img>
                <template>
                    <v-card>
                        <!-- <v-card style="background: url('storage/ps/landS.jpg')"> -->
                        <router-link to="/" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">dashboard</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Dashboard</div>
                            </div>
                        </router-link>

                        <!-- <router-link to="/customers" class="v-list__tile v-list__tile--link" v-for="roleC in user.roles" :key="roleC.id" v-if="roleC.name === 'Client'">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">local_shipping</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    My Shipments
                                </div>
                            </div>
              </router-link>-->
                        <router-link to="/rinders" class="v-list__tile v-list__tile--link" v-for="roleR in user.roles" :key="roleR.id" v-if="roleR.name === 'Rider'">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">local_shipping</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">My Shipments</div>
                            </div>
                        </router-link>
                        <router-link to="/Shipments" class="v-list__tile v-list__tile--link" v-if="user.can['view shipments']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">local_shipping</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Manage Shipments</div>
                            </div>
                        </router-link>
                        <router-link to="/charges" class="v-list__tile v-list__tile--link" v-if="user.can['view charges']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">attach_money</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Manage Charges</div>
                            </div>
                        </router-link>

                        <!-- <router-link to="/subscribers" class="v-list__tile v-list__tile--link" v-if="user.can['view subscribers']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">email</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Subscribers</div>
                            </div>
                        </router-link> -->

                        <!-- <router-link to="/scanner" class="v-list__tile v-list__tile--link" v-if="user.can['outscan', 'inscan']">
                            <div class="v-list__tile__action"><i class="fa fa-barcode nav_icon"></i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    Scan Shipments
                                </div>
                            </div>
              </router-link>-->
                        <router-link to="/profile" class="v-list__tile v-list__tile--link">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">account_circle</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">My Profile</div>
                            </div>
                        </router-link>
                        <!--  -->
                        <!--  -->
                        <!--  -->
                        <router-link to="/reports" class="v-list__tile v-list__tile--link" v-if="user.can['view reports']">
                            <div class="v-list__tile__action">
                                <i aria-hidden="true" class="icon material-icons">book</i>
                            </div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">Reports</div>
                            </div>
                        </router-link>

                        <!-- <router-link to="/print" class="v-list__tile v-list__tile--link" v-if="user.can['print waybill']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">print</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    print
                                </div>
                            </div>
              </router-link>-->
                        <v-list-group prepend-icon="print" v-if="user.can['print waybill']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Print</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/print" class="v-list__tile v-list__tile--link" v-if="user.can['print waybill']">
                                <div class="v-list__tile__action">
                                    <i aria-hidden="true" class="icon material-icons">print</i>
                                </div>
                                <div class="v-list__tile__content">
                                    <div class="v-list__tile__title">Print Waybills</div>
                                </div>
                            </router-link>
                            <!-- <router-link to="/sticker" class="v-list__tile v-list__tile--link">
                                <div class="v-list__tile__action">
                                    <i aria-hidden="true" class="icon material-icons">print</i>
                                </div>
                                <div class="v-list__tile__content">
                                    <div class="v-list__tile__title">Print Stickers</div>
                                </div>
                            </router-link> -->
                        </v-list-group>

                        <!--  -->
                        <!--  -->
                        <!--  -->
                        <v-list-group prepend-icon="edit_location" v-if="user.can['outscan', 'inscan']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Shipments</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/scanner" class="v-list__tile theme--light" style="text-decoration: none">
                                <div class="v-list__tile__action">
                                    <i class="fa fa-barcode nav_icon"></i>
                                </div>
                                <div class="v-list__tile__content">
                                    <div class="v-list__tile__title">Dispatch Shipments</div>
                                </div>
                            </router-link>
                            <router-link to="/filter" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>business</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Delivery Report</v-list-tile-title>
                            </router-link>


                            <router-link to="/status" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>question_answer</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Shipment Followups</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="people_outline" v-if="user.can['view users']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Users & Roles</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/users" class="v-list__tile theme--light" style="text-decoration: none" id="link-router">
                                <v-list-tile-action>
                                    <v-icon>people_outline</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Manage Users</v-list-tile-title>
                            </router-link>
                            <router-link to="/roles" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>gavel</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Manage Roles</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="insert_drive_file" v-if="user.can['view branches']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Branches & Countries</v-list-tile-title>
                            </v-list-tile>

                            <router-link to="/branches" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>book</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Branches</v-list-tile-title>
                            </router-link>
                            <router-link to="/country" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>map</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Countries</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="settings" v-for="roleQ in user.roles" :key="roleQ.id" v-if="roleQ.name === 'Admin'">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>App Settings</v-list-tile-title>
                            </v-list-tile>
                            <router-link to="/towns" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>map</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Towns</v-list-tile-title>
                            </router-link>
                            <router-link to="/statuses" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>dialpad</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Status</v-list-tile-title>
                            </router-link>
                            <router-link to="/deliverystatus" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>dialpad</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Delivery status</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="attach_money" v-if="user.can['view finance']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Finance</v-list-tile-title>
                            </v-list-tile>
                            <router-link to="/finance" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>business</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Finace</v-list-tile-title>
                            </router-link>
                            <router-link to="/charges" class="v-list__tile theme--light" style="text-decoration: none" v-if="user.can['update charges']">
                                <v-list-tile-action>
                                    <v-icon>attach_money</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Charges</v-list-tile-title>
                            </router-link>
                        </v-list-group>

                        <v-list-group prepend-icon="book" v-if="user.can['view logs']">
                            <v-list-tile slot="activator">
                                <v-list-tile-title>Logs</v-list-tile-title>
                            </v-list-tile>
                            <router-link to="/logs" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>business</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Logs</v-list-tile-title>
                            </router-link>
                            <!-- <router-link to="/schedulelogs" class="v-list__tile theme--light" style="text-decoration: none">
                                <v-list-tile-action>
                                    <v-icon>book</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-title>Schedule Logs</v-list-tile-title>
                            </router-link> -->
                        </v-list-group>

                        <!-- <router-link to="/schedulelogs" class="v-list__tile v-list__tile--link" v-if="user.can['view logs']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">book</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    User Logs
                                </div>
                            </div>
                        </router-link>
                        <router-link to="/schedulelogs" class="v-list__tile v-list__tile--link" v-if="user.can['view logs']">
                            <div class="v-list__tile__action"><i aria-hidden="true" class="icon material-icons">book</i></div>
                            <div class="v-list__tile__content">
                                <div class="v-list__tile__title">
                                    User Logs
                                </div>
                            </div>
              </router-link>-->
                    </v-card>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar dark app :color="color" :clipped-left="$vuetify.breakpoint.lgAndUp" fixed>
            <v-toolbar-title style="width: 600px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <img
            src="/storage/Boxleo.png"
            alt
            style="width: 25%;"
          >
        </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom style="margin-right: 10px;">
                    <v-btn icon class="mx-0" @click="openShipment" slot="activator">
                        <v-icon color="white darken-2" large>add</v-icon>
                    </v-btn>
                    <span>Add Shipment</span>
                </v-tooltip>
                <v-divider vertical></v-divider>
                <Notifications :user="user"></Notifications>
                <v-divider vertical></v-divider>
                <!-- <chattyNoty :user="user"></chattyNoty> -->
                <!-- <v-icon @click.stop="right = !right" style="cursor: pointer">apps</v-icon> -->
                <!-- <form action="/logout" method="post">
                    <v-btn flat color="white" type="submit">Logout</v-btn>
                </form> -->
            <Logout :user="user"></Logout>

        </v-toolbar>
    </v-app>

    <v-snackbar :timeout="timeout" bottom="bottom" :color="Snackcolor" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
    <AddShipment :addShipment="dialog" @closeRequest="close" @alertRequest="showalert" :Allcustomer="Allcustomers" :user="user" :role="role" :AllBranches="AllBranches" :AllDrivers="AllDrivers"></AddShipment>
</div>
</template>

<script>
import Notifications from "../notification/Notification";
import AddShipment from"../shipments/Addshipment";
import { vueTopprogress } from "vue-top-progress";
import Logout from "./Logout";
// import chattyNoty from '../notification/chattyNoty'
export default {
  components: {
    Notifications,
    AddShipment,
    vueTopprogress,
    Logout
    //  chattyNoty
  },
  props: ["user"],
  data() {
    return {
      role: "",
      Snackcolor: '',
      color: "#132f51",
      dialog: false,
      drawer: true,
      drawerRight: false,
      right: null,
      mode: "",
      notifications: [],
      company: {},
      AllBranches: [],
      Allcustomers: [],
      AllDrivers: [],
      snackbar: false,
      timeout: 5000,
      message: "Success",
    };
  },
  methods: {
    openShipment() {
      this.dialog = true;
      this.getBranch();
      this.getCustomer();
      this.getDrivers();
    },

    getCustomer() {
      axios
        .get("/getCustomer")
        .then(response => {
          this.Allcustomers = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors;
        });
    },
    getDrivers() {
      axios
        .get("/getDrivers")
        .then(response => {
          this.AllDrivers = response.data;
        })
        .catch(error => {
          console.log(error);
          this.errors = error.response.data.errors;
        });
    },
    getBranch() {
      axios
        .get("/getBranchEger")
        .then(response => {
          this.AllBranches = response.data;
        })
        .catch(error => {
          console.log(error);
          this.errors = error.response.data.errors;
        });
    },
    close() {
      this.dialog = false;
    },

    showalert(data) {
      this.message = data;
      this.Snackcolor = "indigo";
      this.snackbar = true;
    },
    showerror(data) {
        this.message = data;
        this.Snackcolor = "red";
        this.snackbar = true;
    }
  },
  created() {
    eventBus.$on("progressEvent", data => {
      this.$refs.topProgress.start();
    });
    eventBus.$on("StoprogEvent", data => {
      this.$refs.topProgress.done();
    });
    eventBus.$on("alertRequest", data => {
      this.showalert(data)
    });
    eventBus.$on("reloadRequest", data => {
      this.showalert(data)
      window.location.reload()

    });

        eventBus.$on("errorEvent", data => {
            this.showerror(data);
        });
  },
  mounted() {
    // axios.post('/getLogo')
    //     .then((response) => {
    //         this.company = response.data
    //     })
    //     .catch((error) => {
    //         this.errors = error.response.data.errors
    //     })
  }
};
</script>

<style scoped>
.v-expansion-panel__container:hover {
  border-radius: 10px !important;
  width: 90% !important;
  margin-left: 15px !important;
  background: #e3edfe !important;
  color: #1a73e8 !important;
}

.theme--light {
  background-color: #212120 !important;
  /* background: url('storage/logo1.jpg') !important; */
  color: #fff !important;
}
</style>
