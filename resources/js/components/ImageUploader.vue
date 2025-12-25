<template>
    <div class="image-uploader text-center">
        <div id="img_container" style="position: relative; display:inline-block;">
            <img :src="previewSrc" class="img-thumbnail" :class="{ 'rounded-circle': shape === 'circle' }"
                :style="imgStyle">
            <i v-if="showRemove" class="image-remove-btn fas fa-trash text-danger" @click="removeImage"></i>
        </div>

        <div class="form-group mt-3">
            <label :for="inputId">{{ label }}</label>
            <input type="file" :id="inputId" class="form-control" :name="name" :accept="accept" @change="onChange">
            <div v-if="error" class="invalid-feedback d-block"><strong>{{ error }}</strong></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ImageUploader',
    props: {
        initialSrc: { type: String, default: '' },
        name: { type: String, default: 'profile_picture' },
        accept: { type: String, default: 'image/png,image/jpeg,image/gif' },
        maxSize: { type: Number, default: 1024 * 1024 },
        size: { type: String, default: '15vw' },
        label: { type: String, default: 'Select Image' },
        inputId: { type: String, default: '_pp' },
        shape: { type: String, default: 'square' } // 'square' or 'circle'
    },
    data() {
        const defaultSrc = this.initialSrc || (window._asset ? window._asset + 'assets/uploads/no_image.png' : '');
        return {
            previewSrc: defaultSrc,
            originalSrc: defaultSrc,
            showRemove: false,
            error: null
        }
    },
    computed: {
        imgStyle() {
            return {
                width: this.size,
                height: this.size,
                objectFit: 'contain'
            }
        }
    },
    methods: {
        onChange(event) {
            this.error = null;
            const file = event.target.files && event.target.files[0];
            if (!file) return;

            const accepted = this.accept.split(',').map(s => s.trim());
            if (!accepted.includes(file.type)) {
                this.error = 'Unsupported filetype!';
                event.target.value = '';
                return;
            }

            if (file.size > this.maxSize) {
                this.error = 'Maximum filesize is ' + (this.maxSize / 1024 / 1024) + ' MB!';
                event.target.value = '';
                return;
            }

            this.previewSrc = URL.createObjectURL(file);
            this.showRemove = true;
            this.$emit('changed', file);
        },
        removeImage() {
            const input = document.getElementById(this.inputId);
            if (input) input.value = '';
            this.previewSrc = this.originalSrc;
            this.showRemove = false;
            this.error = null;
            this.$emit('removed');
        }
    }
}
</script>

<style scoped>
.image-remove-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    cursor: pointer;
    padding: 6px;
    border-radius: 50%;
    background-color: #ddd;
    display: inline-block;
}

.image-uploader img {
    display: block;
    margin: 0 auto;
    background: #fff;
}
</style>
