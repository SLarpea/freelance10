<script setup>
import { inject, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const swal = inject("$swal");
const global = inject("globalVar");
const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: "",
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
  form.delete(route("current-user.destroy"), {
    preserveScroll: true,
    onStart: () => global.isLoading = true,
    onSuccess: () => {
      closeModal();
      global.isLoading = false;
      swal({
          title: "Deleted Account",
          text: "This account has been deleted.",
          icon: "success",
          showConfirmButton: false,
      });
    },
    onError: () => {
      global.isLoading = false;
      passwordInput.value.focus();
    },
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <div>
    <div class="row p-3">
      <div class="col-12">
        <h5>Delete Account</h5>
        <p class="text-sm">Permanently delete your account.</p>
        <p class="text-sm text-justify">
          Once your account is deleted, all of its resources and data will be
          permanently deleted. Before deleting your account, please download any
          data or information that you wish to retain.
        </p>

        <button
          type="submit"
          @click="confirmUserDeletion"
          class="btn btn-sm btn-danger"
        >
          <i class="bi bi-trash"></i> Delete Account
        </button>

        <transition name="modal-fade">
          <div v-if="confirmingUserDeletion" class="modal custom-modal">
            <div
              class="modal-dialog modal-dialog-centered modal-lg"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title">
                    <i class="bi bi-trash"></i> Delete Account
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
                    Are you sure you want to delete your account? Once your
                    account is deleted, all of its resources and data will be
                    permanently deleted. Please enter your password to confirm
                    you would like to permanently delete your account.
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
                    >This action is irreversible—back up your data first.</span
                  >
                  <div class="btn-footer">
                    <button
                      @click="deleteUser"
                      type="submit"
                      class="btn btn-sm btn-danger"
                    >
                      Delete Account
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
      </div>
    </div>
  </div>
</template>
