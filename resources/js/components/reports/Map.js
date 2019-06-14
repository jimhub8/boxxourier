
// CommitChart.js
import { Line  } from 'vue-chartjs'


export default {
  extends: Line ,
  // props: ['data']
    props: ['datacollection', 'options'],

  data() {
    return{
      rows: [],
      lables: [],
      datacollection: [],
    }
  },
  mounted () {
    axios.post('/getChartData')
    .then((response) => {
      console.log(response);
      this.datacollection = response.data
      this.lables = response.data.lables
      this.rows = response.data.rows
      this.setGraph()
    })
    .catch((error) => {
      this.errors = error.response.data.errors
    })

  },
  methods: {
    setGraph() {
      // Overwriting base render method with actual data.
      this.renderChart({
        lables: this.lables,
        datasets: [
          {
            label: 'Shipments',
            backgroundColor: '#f00',
            data: this.rows
          }
        ]
      }, {responsive: true, maintainAspectRatio: false})
    }
  },
}