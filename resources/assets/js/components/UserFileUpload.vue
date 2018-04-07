<template>
    <label class="text-blue text-xs">
        <input type="file" name="file" @change="onChange"/>
    </label>
</template>

<script>
    export default {
        methods: {
            onChange(e) {
                if (! e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;

                    this.$emit('loaded', { src, file });
                };
            }
        }
    }
</script>

<style scoped>
  input {
    position: absolute;
    border: none;
  }
</style>
