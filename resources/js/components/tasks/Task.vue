<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-layout row wrap>
                    <v-card>
                        <v-card-title>
                            Tasks Schedule
                            <v-btn @click="openAdd" flat color="primary">Add Task</v-btn>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="AllTasks" class="elevation-1" :search="search" :loading="loading">
                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.schedule }}</td>
                                <td class="text-xs-right">{{ props.item.description }}</td>
                                <td class="text-xs-right">{{ props.item.created_at }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon class="mx-0" @click="taskEdit(props.item)">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                        <span>Edit</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-card>
                </v-layout>
            </v-layout>
        </v-container>
        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">
            {{ message }}
            <v-btn>close</v-btn>
        </v-snackbar>
    </v-content>
    <AddTask @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></AddTask>
    <EditTask @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :task="edittask"></EditTask>
    <!-- <ShowTask @closeRequest="close" :openAddRequest="dispShow" @alertRequest="showAlert" :task="edittask"></ShowTask> -->

</div>
</template>

<script>
let AddTask = require('./AddTask');
let EditTask = require('./EditTask');
// let ShowTask = require('./ShowTask');

export default {

    components: {
        AddTask,
        EditTask,
        // ShowTask,

    },

    data() {

        return {
            search: '',
            loading: false,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            AllTasks: [],
            edittask: [],
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: '',
            message: '',
            headers: [
                {
                    text: 'Shedule Time',
                    align: 'left',
                    sortable: false,
                    value: 'schedule'
                },
                {
                    text: 'Description',
                    value: 'description'
                },
                {
                    text: 'Created On',
                    value: 'created_at'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],

        }

    },

    methods: {
        openAdd() {
            this.dispAdd = true
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = this.dispMail = false
        },
        showAlert() {
            this.message = 'Successifully Added';
            this.snackbar = true;
            this.color = 'indigo';
        },

        taskEdit(task) {
            this.edittask = Object.assign({}, task)
            this.editedIndex = this.AllTasks.indexOf(task)
            this.dispEdit = true

        },

    },
    mounted() {
        this.loader = true
        axios.get('/getTasks')
            .then((response) => {
                this.loader = false
                this.AllTasks = response.data
            })
            .catch((error) => {
                this.loader = false
                this.errors = error.response.data.errors
            })
    }
}
</script>
