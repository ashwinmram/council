<template>
    <div>
        <form method="POST" enctype="multipart/form-data">
            <user-file-upload name="attachment" class="mr-1" @loaded="onLoad"></user-file-upload>
        </form>
    </div>
</template>

<script>
    import UserFileUpload from './UserFileUpload.vue';

    export default {
        props: ['thread'],

        components: { UserFileUpload },

        data() {
            return {
                upload: ''
            };
        },

        methods: {
            onLoad(upload) {
                this.upload = upload.src;

                this.persist(upload.file);
            },

            persist(file) {
                let data = new FormData();

                data.append('attachment', file);

                axios.post(`/api/thread/${this.thread.id}/upload`, data)
                    .then(() => {
                        flash('File uploaded!')
                    });
            }
        }
    }
</script>
