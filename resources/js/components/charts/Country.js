  //Importing Line class from the vue-chartjs wrapper
  import {Bar} from 'vue-chartjs'
  //Exporting this so it can be used in other components
  export default { 
    // extend: Line,
    extends: Bar,
    data () {
      return {
        label: [],
        rows: []
      }
    },
    mounted () {
      axios.get('/getChartCountry')
              .then((response) => {
                  console.log(response);
                  this.label = response.data.data.lables
                  this.rows = response.data.data.rows
                  this.setGraph()
              })
              .catch((error) => {
                  this.errors = error.response.data.errors
              })

    },
    methods: {
      setGraph() {
      this.renderChart({
        labels: this.label,
        datasets: [
          {
            label: 'Country Shipments',
            backgroundColor: '#0076c0',
            data: this.rows
          }
        ]
      }, {responsive: true, maintainAspectRatio: false})
    },
      ref() {
          axios.get('/getChartCountry')
              .then((response) => {
                  console.log(response);
                  this.label = response.data.lables
                  this.data = response.data.rows
              })
              .catch((error) => {
                  this.errors = error.response.data.errors
              })
      }
  },
  created() {
    eventBus.$on('chartEvent', data => {
        this.label = data.lables
        this.data = data.rows
    });
},
  }