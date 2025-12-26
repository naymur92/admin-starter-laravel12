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
import SubmitButton from './components/form/SubmitButton.vue';
import ResetButton from './components/form/ResetButton.vue';
import CheckboxGroup from './components/form/CheckboxGroup.vue';
import Select2 from './components/form/Select2.vue';
import RoleCreateModal from './components/pages/role/RoleCreateModal.vue';
import RoleEditModal from './components/pages/role/RoleEditModal.vue';
import PermissionCreateModal from './components/pages/permission/PermissionCreateModal.vue';
import SettingsPage from './components/pages/settings/SettingsPage.vue';

// components
app.component('image-uploader', ImageUploader);
app.component('form-input', FormInput);
app.component('submit-button', SubmitButton);
app.component('reset-button', ResetButton);
app.component('checkbox-group', CheckboxGroup);
app.component('select2', Select2);
app.component('role-create-modal', RoleCreateModal);
app.component('role-edit-modal', RoleEditModal);
app.component('permission-create-modal', PermissionCreateModal);
app.component('settings-page', SettingsPage);


app.use(pinia);
app.mount('#wrapper');