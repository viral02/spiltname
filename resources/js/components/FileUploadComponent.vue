<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Home owner</div>
                    <div class="card-body">
                        <div v-if="success != ''" class="alert alert-success">
                            {{success}}
                        </div>
                        <form v-if="jsonData == null" @submit="formSubmit" enctype="multipart/form-data">
                            <input type="file" class="form-control" v-on:change="onChange">
                            <button class="btn btn-primary btn-block">Upload</button>
                        </form>

                        <div v-if="jsonData != ''" v-for="(array, index) in jsonData" :key="index">
                            <ul>
                                <li v-for="(item, itemIndex) in array" :key="itemIndex">
                                    <div><b>Title:</b> {{ item.title }}</div>
                                    <div><b>First Name:</b> {{ item.first_name }}</div>
                                    <div><b>Initial:</b> {{ item.initial }}</div>
                                    <div><b>Last Name:</b> {{ item.last_name }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                name: '',
                file: '',
                success: '',
                jsonData: null
            };
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            formSubmit(e) {
                e.preventDefault();
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                let data = new FormData();
                data.append('file', this.file);
                axios.post('upload', data, config)
                    .then(function (res) {
                        existingObj.success = res.data.success;
                        existingObj.jsonData = res.data.owner;
                        console.log(existingObj.jsonData);
                    })
                    .catch(function (err) {
                        existingObj.output = err;
                    });
            }
        }
    }
</script>