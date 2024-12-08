<script setup>
import { ref, reactive, nextTick, inject } from 'vue';
import DialogModal from './DialogModal.vue';
import InputError from './InputError.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';

const swal = inject("$swal");
const global = inject("globalVar");
const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: 'Confirm Password',
    },
    content: {
        type: String,
        default: 'For your security, please confirm your password to continue.',
    },
    button: {
        type: String,
        default: 'Confirm',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    global.isLoading = true;
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            global.isLoading = false;
            emit('confirmed');
        } else {
            global.isLoading = false;
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    global.isLoading = true;
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        global.isLoading = false;
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('confirmed'));

    }).catch(error => {
        global.isLoading = false;
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        passwordInput.value.focus();
    });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <transition name="modal-fade">
          <div v-if="confirmingPassword" class="modal custom-modal">
            <div
              class="modal-dialog modal-dialog-centered modal-lg"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title">
                    Password Confirmation
                  </h6>
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                    @click.prevent="closeModal"
                  >
                    <span aria-hidden="true" v-tippy="'Close'">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="text-sm">
                    For your security, please confirm your password to continue.
                  </p>
                  <div>
                    <div class="form-group floating">
                      <input
                        v-model="form.password"
                        type="password"
                        class="form-control floating"
                        id="password"
                        placeholder="Password"
                      />
                      <label for="password">Password</label>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <span class="text-xs"
                    >Ensure you enter the correct password to proceed.</span
                  >
                  <div class="btn-footer">
                    <button @click="confirmPassword" type="submit" class="btn btn-sm btn-submit">
                      Submit
                    </button>
                    <button
                      type="button"
                      class="btn btn-secondary btn-sm btn-close"
                      @click.prevent="closeModal"
                    >
                      Close
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition>
    </span>
</template>
