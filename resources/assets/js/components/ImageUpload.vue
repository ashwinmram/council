<template>
    <div>
      <button class="bg-transparent hover:bg-blue text-blue-dark font-semibold hover:text-white py-2 px-4 border border-blue hover:border-transparent rounded">
        <input class="hover:bg-blue hover:text-white" type="file" name="file" accept="image/*" @change="onChange"/>
        Upload Avatar
      </button>

    </div>
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
    opacity: 0;
  }
</style>
