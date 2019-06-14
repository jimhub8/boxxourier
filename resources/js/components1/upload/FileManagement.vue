<template>
<div class="file-upload">
    <v-snackbar :timeout="timeout" :color="color" :multi-line="mode === 'multi-line'" :vertical="mode === 'vertical'" v-model="snackbar">
        {{ text }}
        <v-btn dark flat @click.native="snackbar = false">Close</v-btn>
    </v-snackbar>
    <v-card>
        <div class="form-group">
            <v-card-title>
                <h4>Choose Documents</h4>
            </v-card-title>
            <v-card-text>
                <div class="form-group">
                    <input type="file" multiple="multiple" id="attachments" @change="uploadFieldChange">
                    <hr>
                    <div class="form-group files">
                        <div class="attachment-holder animated fadeIn" v-cloak v-for="(attachment, index) in attachments">
                            <div class="form-group">
                                <span class="label label-primary">{{ attachment.name + ' (' + Number((attachment.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span>
                                <span style="background: red; cursor: pointer;" @click="removeAttachment(attachment)"><button class="btn btn-xs btn-danger">Remove</button></span>
                            </div>
                            <div class="form-group">
                                <label for="logo" class="control-label">Select a category for this file:</label>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="category in categories" v-bind:class="{'selected': attachment.category_id == category.id}" @click="selectCategory(attachment, category.id)">{{category.name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn flat color="primary" @click="submit">Upload</v-btn>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" :loading="loading" :disabled="loading" @click="close">Close</v-btn>
            </v-card-actions>
        </div>
    </v-card>
</div>
</template>

<script>
export default {

    props: [
        'settings'
    ],

    data() {

        return {
            snackbar: false,
            loading: false,
            mode: '',
            timeout: 3000,
            color: '',
            text: '',

            // You can store all your files here
            attachments: [],

            attachment_labels: [], // List of old uploaded files coming from the server

            categories: [],

            // Each file will need to be sent as FormData element
            data: new FormData(),

            percentCompleted: 0,

        }

    },

    watch: {

    },

    computed: {

    },

    methods: {

        selectCategory(attachment, category_id) {
            attachment.category_id = category_id;
            console.log(attachment);
            this.$forceUpdate();
        },

        validate() {

            if (!this.attachments.length) {
                toastr.warning('Please add files', 'Warning');
                return false;
            }

            return true;
        },

        pullCategories() {

            // Make HTTP request to store announcement
            axios.post(this.settings.file_management.pull_categories)
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        this.categories = response.data.data;
                        console.log('Categories: ', this.categories);
                        toastr.success('We just pulled all the categories', 'Background Task: Success');
                    } else {
                        console.log(response.data.errors);
                        toastr.warning('Cannot pull categories. User has to be logged in', 'Background Task: Warning');
                    }
                }.bind(this)) // Make sure we bind Vue Component object to this funtion so we get a handle of it in order to call its other methods
                .catch(function (error) {
                    console.log(error);
                });

        },

        getAttachmentSize() {

            this.upload_size = 0; // Reset to beginningƒ

            this.attachments.map((item) => {
                this.upload_size += parseInt(item.size);
            });

            this.upload_size = Number((this.upload_size).toFixed(1));

            this.$forceUpdate();

        },

        prepareFields() {

            for (var i = this.attachments.length - 1; i >= 0; i--) {
                // console.log(this.attachments[i].category_id);
                this.data.append("attachments[][0]", this.attachments[i]);
                this.data.append("attachments[][1]", this.attachments[i].category_id);
            }

            for (var i = this.attachment_labels.length - 1; i >= 0; i--) {
                this.data.append("attachment_labels[]", JSON.stringify(this.attachment_labels[i]));
            }

        },

        removeAttachment(attachment) {

            this.attachments.splice(this.attachments.indexOf(attachment), 1);

            this.getAttachmentSize();

        },

        // This function will be called every time you add a file
        uploadFieldChange(e) {

            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;

            for (var i = files.length - 1; i >= 0; i--) {
                this.attachments.push(files[i]);
            }

            // Reset the form to avoid copying these files multiple times into this.attachments
            document.getElementById("attachments").value = [];
        },

        submit() {
            this.loading = true

            this.prepareFields();

            if (!this.validate()) {
                return false;
            }

            // window.Event.fire('loading_on');

            var config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function (progressEvent) {
                    this.percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    // window.Event.fire('percent', this.percentCompleted);
                    console.log(this.percentCompleted);
                    this.$forceUpdate();
                }.bind(this)
            };

            // Make HTTP request to store announcement
            axios.post(this.settings.file_management.upload_files, this.data, config)
                .then(function (response) {
                    this.loading = false

                    console.log(response);
                    if (response.data.success) {
                        console.log('Successfull upload');
                        this.resetData();
                        // toastr.success('Documents uploaded!', 'Success');
                        // this.attachments = [];
                        // window.Event.fire('reload_files'); // Tell AttachmentList component to refresh its list
                    } else {
                        this.loading = false
                        toastr.error('Somethind went wrong', 'Error');
                        console.log('Unsuccessful Upload');
                        this.snackbar = true;
                        this.color = 'error';
                        this.text = 'Upload failed';
                    }
                    // window.Event.fire('loading_off');
                }.bind(this)) // Make sure we bind Vue Component object to this funtion so we get a handle of it in order to call its other methods
                .catch(function (error) {
                    this.loading = false
                    console.log(error);
                    this.snackbar = true;
                    this.color = 'error';
                    this.text = 'Upload failed';
                    // window.Event.fire('loading_off');
                });

        },

        // We want to clear the FormData object on every upload so we can re-calculate new files again.
        // Keep in mind that we can delete files as well so in the future we will need to keep track of that as well
        resetData() {
            this.data = new FormData(); // Reset it completely
            // console.log('formdata');
            this.snackbar = true;
            this.color = 'indigo';
            this.text = 'Upload success';
            this.attachments = [];
        },

        start() {
            console.log('Starting File Management Component');
            this.pullCategories();
        },

        close() {
            this.$emit('closeRequest')
        }

    },

    created() {
        this.start();
    },

    mounted() {
        axios.get('/categories')
        .then((response) => {
            this.categories = response.data
        })
        .catch(function (error) {
            console.log(error);
            // window.Event.fire('loading_off');
        });
    },

}
</script>

<style scoped>
.list-group {
    width: 30%;
    /*padding: 2% 0;*/
}

.list-group li:hover {
    background: #000;
    transition: 400ms ease-out;
    color: #fff;
}

ul .selected {
    background: #000;
    color: #fff;
    /*width: 17%;*/
}

/*ul{
    background: #f0f0f0;
    width: 30%;
    padding: 20px 0;
    text-align: center;
    margin: auto;
}*/

/*.form-group label{
    text-align: center;
}*/
</style>
