import './bootstrap';
import './helpers/spinner';

import { createApp } from 'vue';
import { createPinia } from 'pinia'

const pinia = createPinia();
const app = createApp({});

app.config.globalProperties.appURL = import.meta.env.VITE_APP_URL;
app.config.globalProperties.assetPath = window._asset;

app.config.globalProperties = {
    ...app.config.globalProperties,
};

// components
import ImageUploader from './components/ImageUploader.vue';
import FormInput from './components/form/FormInput.vue';
import RoleCreateModal from './components/pages/role/RoleCreateModal.vue';
import RoleEditModal from './components/pages/role/RoleEditModal.vue';
import PermissionCreateModal from './components/pages/permission/PermissionCreateModal.vue';

// components
app.component('image-uploader', ImageUploader);
app.component('form-input', FormInput);
app.component('role-create-modal', RoleCreateModal);
app.component('role-edit-modal', RoleEditModal);
app.component('permission-create-modal', PermissionCreateModal);


app.use(pinia);
app.mount('#wrapper');