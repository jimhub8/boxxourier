<template>
<v-layout row justify-center>
    <v-dialog v-model="openLogoRequest" persistent max-width="400">
        <!-- <v-btn slot="activator" color="primary" dark>Open Dialog</v-btn> -->
        <v-card>
            <v-divider></v-divider>
            <h6 class="text-center" color="green">Admin</h6>
            <v-btn color="red" darken-1 raised @click="onPickFile" style="color: #fff;">Upload</v-btn>
            <input type="file" @change="Getimage" accept="image/*" style="display: none" ref="fileInput">
            <img v-show="imagePlaced" :src="avatar" style="width: 200px; height: 200px;">
            <v-btn @click="upload" flat v-show="imagePlaced" :loading="loading" :disabled="loading">Upload</v-btn>
            <v-btn @click="close" flat>Close</v-btn>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openLogoRequest', 'company'],
    components: {},
    data() {
        return {
            errors: {},
            loading: false,
            imagePlaced: false,
            avatar: '',
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        Getimage(e) {
            this.imagePlaced = true
            let image = e.target.files[0];
            // this.read(image);
            let reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                this.avatar = e.target.result
            }
            let form = new FormData();
            form.append('image', image);
            this.file = form
            console.log(e.target.files);
        },

        upload() {
            this.loading = true
            axios.post(`/logo/${this.company.id}`, this.file)
                .then((response) => {
                    this.loading = false
                    console.log(response);
                    this.$emit('alertRequest')
                    this.close()
                })
                .catch((error) => {
                    this.loading = false
                    this.Editloader = false
                    this.close()
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.color = 'red'
                    this.message = 'Something went wrong'
                    this.snackbar = true
                    this.errors = error.response.data.errors
                })
        },
        // Image Upload
        onPickFile() {
            this.$refs.fileInput.click()
        },
        onFilePicked(event) {
            const files = event.target.files
            let filename = files[0].name
            if (filename.lastIndexOf('.') <= 0) {
                return alert('please upload a valid image')
            }
            const fileReader = new FileReader()
            fileReader.addEventListener('load', () => {
                this.avatar = fileReader.result
            })
            fileReader.readAsDataURL(files[0])
            this.image = files[0]
        },

        cancle() {
            if (this.companyLogo.logo.length > 0) {
                this.avatar = this.companyLogo.logo;
            } else {
                this.imagePlaced = false
                this.avatar = ''
            }
        },
        close() {
            this.$emit('closeRequest')
        },

    },
    mounted() {

        axios.post('/getLogoOnly')
            .then((response) => {
                if (response.data.length > 0) {
                    this.imagePlaced = true
                    this.avatar = response.data
                } else {
                    this.avatar = ''
                }
                this.loader = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
            })
    }
}
</script>
