<template>
    <div>
        <!-- Edit Role Modal -->
        <div class="modal fade" tabindex="-1" :class="{ show: showModal }"
            :style="{ display: showModal ? 'block' : 'none' }" aria-labelledby="editRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 40vw">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                        <button type="button" class="close" @click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitRole">
                        <div class="modal-body">
                            <!-- Loading indicator -->
                            <div v-if="loading" class="text-center mb-3">
                                <span class="spinner-border spinner-border-sm mr-2"></span>
                                Loading role data...
                            </div>
                            <!-- Role Name Input -->
                            <div class="form-group">
                                <form-input v-model="form.name" id="editRoleName" name="name" label="Role Name"
                                    placeholder="Admin, Editor, Viewer" :disabled="loading" :required="true"
                                    :error="errors.name" />
                            </div>

                            <!-- Permissions Checkboxes -->
                            <div class="form-group">
                                <label><strong>Select Permissions</strong> <span class="text-danger">*</span></label>
                                <div class="permissions p-3 border rounded"
                                    :class="{ 'border-danger': errors.permissions }">
                                    <div class="row">
                                        <label v-for="perm in availablePermissions" :key="perm.id"
                                            class="col-6 col-md-3">
                                            <input v-model="form.permissions" type="checkbox" :value="perm.name"
                                                :disabled="loading">
                                            {{ perm.name }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="errors.permissions" class="text-danger small mt-2">{{ errors.permissions[0]
                                    }}</div>
                            </div>

                            <!-- General Error Messages -->
                            <ul v-if="otherErrors.length > 0" class="text-danger list-unstyled">
                                <li v-for="(msg, index) in otherErrors" :key="index">
                                    {{ msg }}
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal"
                                :disabled="loading">Close</button>
                            <button type="reset" class="btn btn-danger" @click="resetForm"
                                :disabled="loading">Reset</button>
                            <button type="submit" class="btn btn-success" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm mr-2"></span>
                                {{ loading ? 'Updating...' : 'Update Role' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div v-if="showModal" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
    name: 'RoleEditModal',
    props: {
        permissions: {
            type: Array,
            default: () => [],
        },
        updateUrl: {
            type: String,
            default: '/admin/roles/0',
        },
    },
    emits: ['role-updated'],
    setup(props, { emit }) {
        const showModal = ref(false);
        const loading = ref(false);
        const errors = ref({});
        const form = ref({
            name: '',
            permissions: [],
        });
        const currentRoleId = ref(null);

        // Filter permissions with id > 8
        const availablePermissions = computed(() => {
            return props.permissions.filter(p => p.id > 8);
        });

        // Filter other errors (excluding field-specific errors)
        const otherErrors = computed(() => {
            return Object.entries(errors.value)
                .filter(([field]) => field !== 'name' && field !== 'permissions')
                .map(([, msgs]) => msgs[0]);
        });

        const buildUpdateUrl = () => {
            // If the URL contains a {role} placeholder, use that
            if (props.updateUrl.includes('{role}')) {
                return props.updateUrl.replace('{role}', currentRoleId.value);
            }
            // Otherwise replace the trailing numeric segment (e.g. /admin/roles/0) with the current id
            return props.updateUrl.replace(/\/\d+$/, '/' + currentRoleId.value);
        };

        const openModal = async (roleId) => {
            loading.value = true;
            window.setSpinner(document.body);
            currentRoleId.value = roleId;
            resetForm();
            // Open modal immediately; fields are disabled while loading
            showModal.value = true;

            try {
                const response = await fetch(`/admin/roles/${roleId}/json`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                });
                const data = await response.json();

                if (data.success) {
                    form.value.name = data.role.name;
                    // normalize permissions to an array of names if necessary
                    form.value.permissions = Array.isArray(data.permissions)
                        ? data.permissions.map(p => (typeof p === 'object' ? p.name : p))
                        : [];
                } else {
                    errors.value = { general: ['Failed to load role data'] };
                }
            } catch (error) {
                console.error('Error fetching role:', error);
                errors.value = { general: ['Failed to load role data'] };
            } finally {
                loading.value = false;
                window.unsetSpinner();
            }
        };

        const closeModal = () => {
            showModal.value = false;
        };

        const resetForm = () => {
            form.value = { name: '', permissions: [] };
            errors.value = {};
        };

        const submitRole = async () => {
            loading.value = true;
            errors.value = {};
            window.setSpinner();

            try {
                const url = buildUpdateUrl();

                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify(form.value),
                });

                const data = await response.json();

                if (data.success) {
                    window.unsetSpinner();
                    closeModal();
                    resetForm();
                    emit('role-updated', currentRoleId.value);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else if (data.errors) {
                    window.unsetSpinner();
                    errors.value = data.errors;
                    loading.value = false;
                } else {
                    window.unsetSpinner();
                    errors.value = { general: ['An unknown error occurred.'] };
                    loading.value = false;
                }
            } catch (error) {
                console.error('Error:', error);
                window.unsetSpinner();
                errors.value = { general: ['An error occurred. Please try again.'] };
                loading.value = false;
            }
        };

        // Expose methods to window for inline handlers
        window.openEditRoleModal = openModal;
        window.closeEditRoleModal = closeModal;

        return {
            showModal,
            loading,
            errors,
            form,
            availablePermissions,
            otherErrors,
            currentRoleId,
            openModal,
            closeModal,
            resetForm,
            submitRole,
        };
    },
};
</script>

<style scoped>
.permissions {
    max-height: 300px;
    overflow-y: auto;
}

.permissions label {
    margin-bottom: 0.5rem;
    cursor: pointer;
}

.modal.show {
    display: block !important;
}

.invalid-feedback {
    color: #dc3545;
}
</style>
