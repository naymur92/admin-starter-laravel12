<template>
    <div>
        <!-- Create Role Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" :class="{ show: showModal }"
            :style="{ display: showModal ? 'block' : 'none' }" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 40vw">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Role</h5>
                        <button type="button" class="close" @click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitRole">
                        <div class="modal-body">
                            <div class="form-group">
                                <form-input v-model="form.name" id="_name" name="name" label="Role Name"
                                    placeholder="Super Admin, Admin, User, etc." :disabled="loading" :required="true"
                                    :error="errors.name" />
                            </div>

                            <div class="form-group">
                                <label class="mb-3"><strong>Select Permissions</strong> <span class="text-danger"><i
                                            class="fas fa-xs fa-asterisk"></i></span></label>
                                <br>
                                <div class="permissions p-3 border rounded"
                                    :class="{ 'border-danger': errors.permission }">
                                    <div class="row">
                                        <label v-for="perm in availablePermissions" class="col-6 col-md-3"
                                            :key="perm.id">
                                            <input v-model="form.permissions" type="checkbox" :value="perm.name">
                                            {{ perm.name }}
                                        </label>
                                        <p v-if="availablePermissions.length === 0" class="text-muted">No permissions
                                            available</p>
                                    </div>
                                </div>
                                <div v-if="errors.permission" class="text-danger small mt-2">{{ errors.permission[0] }}
                                </div>
                            </div>

                            <ul v-if="Object.keys(errors).length > 0" id="errors" class="text-danger my-2">
                                <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                            <button type="reset" class="btn btn-danger mr-3" @click="resetForm">Reset Form</button>
                            <button type="submit" id="submit-btn" class="btn btn-success" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm mr-2"></span>
                                {{ loading ? 'Creating...' : 'Add Role' }}
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
    name: 'RoleCreateModal',
    props: {
        permissions: {
            type: Array,
            default: () => [],
        },
        createUrl: {
            type: String,
            default: '/admin/roles',
        },
    },
    emits: ['role-created'],
    setup(props, { emit }) {
        const showModal = ref(false);
        const loading = ref(false);
        const errors = ref({});
        const form = ref({
            name: '',
            permissions: [],
        });

        // Filter permissions with id > 8
        const availablePermissions = computed(() => {
            return props.permissions.filter(p => p.id > 8);
        });

        const openModal = () => {
            window.setSpinner();
            resetForm();
            showModal.value = true;
            // Simulate modal loading, then hide spinner
            setTimeout(() => {
                window.unsetSpinner();
            }, 300);
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
                const response = await fetch(props.createUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(form.value),
                });

                const data = await response.json();

                if (data.success) {
                    window.unsetSpinner();
                    closeModal();
                    resetForm();
                    // Reload page to show new role
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else if (data.errors) {
                    window.unsetSpinner();
                    errors.value = data.errors;
                    loading.value = false;
                }
            } catch (error) {
                console.error('Error:', error);
                window.unsetSpinner();
                errors.value = { general: ['An error occurred. Please try again.'] };
                loading.value = false;
            } finally {
                loading.value = false;
            }
        };

        // Expose methods to window for Blade event handlers
        window.openCreateRoleModal = openModal;
        window.closeRoleModal = closeModal;

        return {
            showModal,
            loading,
            errors,
            form,
            availablePermissions,
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
    padding: 10px;
    border-radius: 5px;
}

.permissions label {
    display: block;
    margin-bottom: 8px;
}

.modal.show {
    display: block !important;
}

.invalid-feedback {
    color: #dc3545;
}
</style>
