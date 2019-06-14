<template>
  <v-layout row justify-center>
    <v-dialog v-model="EditShipment" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="close">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Edit Shipment</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-form ref="form" @submit.prevent="submit">
              <v-container grid-list-xl fluid>
                <v-layout wrap>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.client_name"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Client name"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.client_email"
                    :rules="emailRules"
                    color="blue darken-2"
                    label="Client Email"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.client_address"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Client Address"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.client_city"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Client City"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.client_phone"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Client Phone"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.assign_staff"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Assigned Staff"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.airway_bill_no"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Zip Code"
                    required
                    ></v-text-field>
                  </v-flex>
                  <v-divider></v-divider>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="list.shipment_type"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Shipment Type"
                    required
                    ></v-text-field>
                  </v-flex>

                  <v-spacer></v-spacer>
                  <!-- date picker -->
                  <v-flex xs6 sm3 md3>
                    <v-dialog
                    ref="dialog1"
                    v-model="dmodal1"
                    :return-value.sync="list.booking_date"
                    persistent
                    lazy
                    full-width
                    width="290px"
                    >
                    <v-text-field
                    slot="activator"
                    v-model="list.booking_date"
                    label="Booking Date"
                    prepend-icon="event"
                    readonly
                    ></v-text-field>
                    <v-date-picker v-model="list.booking_date" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="dmodal1 = false">Cancel</v-btn>
                      <v-btn flat color="primary" @click="$refs.dialog1.save(list.booking_date)">OK</v-btn>
                    </v-date-picker>
                  </v-dialog>
                </v-flex>

                <v-flex xs6 sm3 md3>
                  <v-dialog
                  ref="dialog2"
                  v-model="dmodal2"
                  :return-value.sync="list.derivery_date"
                  persistent
                  lazy
                  full-width
                  width="290px"
                  >
                  <v-text-field
                  slot="activator"
                  v-model="list.derivery_date"
                  label="Derivery Date"
                  prepend-icon="event"
                  readonly
                  ></v-text-field>
                  <v-date-picker v-model="list.derivery_date" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="dmodal2 = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.dialog2.save(list.derivery_date)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>
              <!-- date picker -->

              <v-flex xs4 sm3>
                <v-text-field
                v-model="list.derivery_time"
                :rules="rules.name"
                :type="'time'"
                color="blue darken-2"
                label="Derivery Time"
                required
                ></v-text-field>
              </v-flex>
              <select class="custom-select custom-select-md col-md-3" v-model="list.insuarance_status">
                <option selected>Insurance Status</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              <select class="custom-select custom-select-md col-md-3" v-model="list.payment">
                <option selected>Paid</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              <v-divider></v-divider>
              <v-btn color="success" dark @click="pdialog2 = true">Add products</v-btn>
              <v-divider></v-divider>
            </v-layout>
          </v-container>
          <v-card-actions>
            <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
            <v-btn flat @click="close">Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn
            flat
            color="primary"
            @click="save"
            >Add Shipment</v-btn>
          </v-card-actions>
        </v-form>
      </v-layout>
    </v-container>
    <v-divider></v-divider>
  </v-card>
</v-dialog>
</v-layout>
</template>

<script>
export default {
  props: ['EditShipment'],
  data () {
    
    return {
      notifications: false,
      dmodal1: false,
      pdialog2: false,
      dmodal2: false,
      tmodal: false,
      sound: true,
      list: {},
      form: {},
      emailRules: [
      v => {
        return !!v || 'E-mail is required'
      },
      v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
      ],
      rules: {
        name: [val => (val || '').length > 0 || 'This field is required']
      },
      items: [
      { state: 'Yes', abbr: 'yes' },
      { state: 'No', abbr: 'no' },
      ], 
    }
  },
  methods: {
    save() {
      axios.patch('/shipment/${id}', this.$data.form)
      .then((response) => {
        console.log(response);
        // this.$parent.Allusers.push(response.data) 
        this.$emit('closeRequest');
        // this.resetForm;
      })
    },
    close() {
      this.$emit('closeRequest');
    },
    /*resetForm () {
      this.form = Object.assign({}, this.defaultForm)
      this.$refs.form.reset()
    },*/
  },

  computed: {
    formIsValid () {
      return (
        this.form.client_name &&
        this.form.client_phone &&
        this.form.client_email &&
        this.form.client_address &&
        this.form.client_city &&
        this.form.assign_staff &&
        this.form.airway_bill_no &&
        this.form.total_weight &&
        this.form.shipment_type &&
        this.form.payment &&
        this.form.total_freight &&
        this.form.insuarance_status &&
        this.form.booking_date &&
        this.form.derivery_date
        )
    },
  },
}
</script>