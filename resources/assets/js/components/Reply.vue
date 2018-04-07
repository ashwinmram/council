<template>
    <div :id="'reply-'+id" class="border-b border-grey-lighter py-8" :class="isBest ? 'panel-success': 'panel-default'">
        <div class="flex">
            <div>
                <img src="/images/avatars/default.svg"
                     :alt="reply.owner.name"
                     width="36"
                     height="36"
                     class="mr-4 bg-blue-darker rounded-full p-1">

                <div v-if="signedIn" class="text-xs pl-2" style="padding-top: 15px">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>

            <div class="flex-1 ml-1">
                <div class="flex items-center mb-6 mt-2">
                    <div class="flex flex-1">
                        <h5 class="font-normal">
                            <a class="text-blue font-bold link" :href="'/profiles/' + reply.owner.username" v-text="reply.owner.name"></a>
                        </h5>

                        <a v-if="! editing && (authorize('owns', reply) || authorize('owns', reply.thread))"
                           href="#"
                           @click.prevent="editing = true"
                           class="text-blue text-xs link ml-2 pl-2 border-l"
                        >
                            Edit
                        </a>
                    </div>

                    <div class="text-2xs flex items-center">
                        <a v-if="authorize('owns', reply.thread) || isBest" href="#" class="mr-2 font-bold flex items-center" :class="bestReplyClasses" @click.prevent="markBestReply">
                            <span v-text="isBest ? 'Best Answer!' : 'Best Answer?'" class="mr-2"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" class="fill-current" :class="isBest ? 'text-green' : 'text-grey-light'">
                                <path fill-rule="evenodd" d="M9.99 0C4.47 0 0 4.48 0 10s4.47 10 9.99 10C15.52 20 20 15.52 20 10S15.52 0 9.99 0zm4.24 16L10 13.45 5.77 16l1.12-4.81-3.73-3.23 4.92-.42L10 3l1.92 4.53 4.92.42-3.73 3.23L14.23 16z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <div v-if="editing">
                        <form @submit.prevent="update">
                            <div class="mb-4">
                                <wysiwyg v-model="body"></wysiwyg>
                            </div>

                            <div class="flex justify-between">
                                <button class="btn bg-red" @click="destroy">Delete</button>

                                <div>
                                    <button class="btn mr-2" @click="cancel" type="button">Cancel</button>
                                    <button type="submit" class="btn bg-blue">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div v-else>
                        <div class="bg-green-lightest border-l-4 border-green text-green-dark p-4">
                            <p class="text-blue font-bold underline text-xs">Attachments:</p>

                            <ul v-if="attachments" v-for="attachment in attachments" key="attachment.id">
                                <div class="flex items-center">
                                    <a :href="attachment.path" class="flex text-blue text-xs link">{{ attachment.name }}</a>

                                    <span class="flex">
                                        <svg class="fill-current h-4 w-4 text-red" role="button" @click="deleteAttachment(attachment)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                                    </span>
                                </div>
                            </ul>

                            <reply-upload-form class="pt-4" :reply="reply"></reply-upload-form>
                        </div>



                        <highlight :content="body" class="pb-4 pt-4"></highlight>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Favorite from "./Favorite.vue";
import Highlight from "./Highlight.vue";
import moment from "moment";

export default {
    props: ["reply"],

    components: { Favorite, Highlight },

    data() {
        return {
            editing: false,
            id: this.reply.id,
            body: this.reply.body,
            isBest: this.reply.isBest,
            attachments: ''
        };
    },

    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow() + "...";
        },

        bestReplyClasses() {
            let classes = [this.isBest ? "text-green" : "text-grey-light"];

            if (!this.authorize("owns", this.reply.thread)) {
                classes.push("cursor-auto");
            }

            return classes;
        }
    },

    created() {
        window.events.$on("best-reply-selected", id => {
            this.isBest = id === this.id;
        });

        this.attachments = this.reply.attachments
    },

    methods: {
        update() {
            axios
                .patch("/replies/" + this.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.data, "danger");
                });

            this.editing = false;

            flash("Updated!");
        },

        cancel() {
            this.editing = false;

            this.body = this.reply.body;
        },

        destroy() {
            axios.delete("/replies/" + this.id);

            this.$emit("deleted", this.id);
        },

        markBestReply() {
            if (!this.authorize("owns", this.reply.thread)) {
                return;
            }

            axios.post("/replies/" + this.id + "/best");

            window.events.$emit("best-reply-selected", this.id);
        },

        deleteAttachment(attachment) {
            let index = this.attachments.indexOf(attachment);
            this.attachments.splice(index, 1);
            axios.delete("/api/attachment/" + attachment.id);
        }
    }
};
</script>

<style scoped>

</style>
