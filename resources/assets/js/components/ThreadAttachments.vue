<template>
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

        <thread-upload-form class="pt-4" :thread="this.thread"></thread-upload-form>
    </div>
</template>

<script>
    export default {
        props: ['thread'],

        data() {
            return {
                attachments: ''
            };
        },

        mounted() {
            this.attachments = this.thread.attachments
        },

        methods: {
            deleteAttachment(attachment) {
                let index = this.attachments.indexOf(attachment);
                this.attachments.splice(index, 1);
                axios.delete("/api/attachment/" + attachment.id);
            }
        }
    }
</script>
