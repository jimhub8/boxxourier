<template>
  <v-layout row justify-center>
    <v-dialog v-model="addContainer" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="close">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Add Container</v-toolbar-title>
          <v-spacer></v-spacer>
          <!-- <v-toolbar-items>
            <v-btn dark flat @click="save">Save</v-btn>
          </v-toolbar-items> -->
        </v-toolbar>
        <v-container grid-list-md v-show="!loader">
          <v-layout wrap>
            <v-form ref="form" @submit.prevent="submit">
              <v-container grid-list-xl fluid>
                <v-layout wrap>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="form.bar_code"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Bar Code number"
                    required
                    ></v-text-field>
                  <small class="has-text-danger" v-if="errors.bar_code">{{ errors.bar_code[0] }}</small>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="form.address"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Address"
                    required
                    ></v-text-field>
                  <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="form.client_city"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="City"
                    required
                    ></v-text-field>
                  <small class="has-text-danger" v-if="errors.client_city">{{ errors.client_city[0] }}</small>
                  </v-flex>
                  <v-flex xs4 sm3>
                    <v-text-field
                    v-model="form.assign_staff"
                    :rules="rules.name"
                    color="blue darken-2"
                    label="Assigned Staff"
                    required
                    ></v-text-field>
                  <small class="has-text-danger" v-if="errors.assign_staff">{{ errors.assign_staff[0] }}</small>
                  </v-flex>

                  <!-- date picker -->
                <v-flex xs6 sm3 md3>
                  <v-dialog
                  ref="dialog2"
                  v-model="dmodal2"
                  :return-value.sync="form.derivery_date"
                  persistent
                  lazy
                  full-width
                  width="290px"
                  >
                  <v-text-field
                  slot="activator"
                  v-model="form.derivery_date"
                  label="Derivery Date"
                  prepend-icon="event"
                  readonly
                  ></v-text-field>
                  <small class="has-text-danger" v-if="errors.derivery_date">{{ errors.derivery_date[0] }}</small>
                  <v-date-picker v-model="form.derivery_date" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="dmodal2 = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.dialog2.save(form.derivery_date)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>
              <!-- date picker -->

              <v-flex xs4 sm3>
                <v-text-field
                v-model="form.derivery_time"
                :rules="rules.name"
                :type="'time'"
                color="blue darken-2"
                label="Derivery Time"
                required
                ></v-text-field>
                  <small class="has-text-danger" v-if="errors.derivery_time">{{ errors.derivery_time[0] }}</small>
              </v-flex>
              <barcode v-bind:value="form.bar_code" v-show="!null"></barcode>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-btn flat @click="resetForm">reset</v-btn>
            <v-btn flat @click="close">Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn
            flat
            color="primary"
            @click="save"
            >Add Container</v-btn>
          </v-card-actions>
        </v-form>
      </v-layout>
    </v-container>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
    </div>
  </v-card>
</v-dialog>


</v-layout>
</template>

<script>
import VueBarcode from 'vue-barcode';
export default {
  components: {
    'barcode': VueBarcode
  },
  props: ['addContainer'],
  data () {
    const defaultForm = Object.freeze({
      bar_code: '',
      address: '',
      assign_staff: '',
      airway_bill_no: '',
      derivery_date: null,
      derivery_time: null,
    })
    return {
      notifications: false,
      errors: {},
      list: {},
      loader: false,
      dmodal1: false,
      pdialog2: false,
      dmodal2: false,
      tmodal: false,
      sound: true,
      widgets: false,form: Object.assign({}, defaultForm),
      rules: {
        name: [val => (val || '').length > 0 || 'This field is required']
      },
    }
  },
  methods: {
    save() {
      this.loader = true
      axios.post('/container', this.$data.form)
      .then((response) => {
        this.loader = false
        console.log(response);
        this.$parent.AllContainers.push(response.data) 
        // this.$emit('closeRequest');
        // this.resetForm;
      })
      .catch((error) => {
        console.log(error);
        this.errors = error.response.data.errors
        this.loader=false
      })
    },
    close() {
      this.$emit('closeRequest');
    },
    resetForm () {
      this.form = Object.assign({}, this.defaultForm)
      this.$refs.form.reset()
    },
  },

  computed: {
    formIsValid () {
      return (
        this.form.name &&
        this.form.bar_code &&
        this.form.address &&
        this.form.assign_staff &&
        this.form.airway_bill_no &&
        this.form.derivery_date
        )
    },
  },
}
</script>

