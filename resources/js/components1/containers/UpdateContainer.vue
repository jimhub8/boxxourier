<template>
  <v-layout row justify-center>
    <v-dialog v-model="UpdateShipment" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="close">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Edit Shipment</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-container grid-list-md>
          <v-layout row wrap>
            <v-flex xs8>
              <v-jumbotron color="grey lighten-2">
                <v-container fill-height>
                  <v-layout align-center>
                    <v-flex>
                      <h3 class="display-3">Welcome to the site</h3>
                      <span class="subheading">Lorem ipsum dolor sit amet, pri veniam forensibus id. Vis maluisset molestiae id, ad semper lobortis cum. At impetus detraxit incorrupte usu, repudiare assueverit ex eum, ne nam essent vocent admodum.</span>
                      <v-divider class="my-3"></v-divider>
                      <div class="title mb-3">Check out our newest features!</div>
                      <v-btn large color="primary" class="mx-0">See more</v-btn>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-jumbotron>
            </v-flex>
            <v-flex xs8>
              
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider></v-divider>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
export default {
  props: ['UpdateShipment'],
  data () {

    return {
      emailRules: [
      v => {
        return !!v || 'E-mail is required'
      },
      v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
      ],
      rules: {
        name: [val => (val || '').length > 0 || 'This field is required']
      },
    }
  },  
}
</script>