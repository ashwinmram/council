<template>
    <div>
        <div class="level flex items-center mb-4">
            <img :src="avatar" width="50" height="50" class="mr-1 rounded-full mt-4">

            <h1>
                <span class="inline mb-4 text-xl text-blue">{{ user.username }}</span>
                <small class="inline px-2 text-lg bg-green rounded font-semibold text-white" v-text="reputation"></small>
            </h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
        </form>

    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            },

            reputation() {
                return this.user.reputation + 'XP';
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.username}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            }
        }
    }
</script>
