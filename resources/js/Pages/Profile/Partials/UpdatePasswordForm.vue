<script setup>
import { inject, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const swal = inject("$swal");
const global = inject("globalVar");
const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const updatePassword = () => {
  form.put(route("user-password.update"), {
    errorBag: "updatePassword",
    preserveScroll: true,
    onStart: () => (global.isLoading = true),
    onSuccess: () => {
      form.reset();
      global.isLoading = false;
      swal({
        title: "Password Updated!",
        text: "Your password has been updated.",
        icon: "success",
        showConfirmButton: false,
      });
    },
    onError: () => {
      global.isLoading = false;
      if (form.errors.password) {
        form.reset("password", "password_confirmation");
        passwordInput.value.focus();
      }

      if (form.errors.current_password) {
        form.reset("current_password");
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <div>
    <div class="row p-3">
      <div class="col-sm-12 col-md-4">
        <h5>Update Password</h5>
        <p class="text-sm">
          Ensure your account is using a long, random password to stay secure.
        </p>
      </div>
      <div class="col-sm-12 col-md-8">
        <form @submit.prevent="updatePassword">
          <div class="form-group floating">
            <input
              v-model="form.current_password"
              type="password"
              class="form-control floating"
              id="current_password"
              placeholder="Current Password"
            />
            <label for="current_password">Current Password</label>
            <InputError :message="form.errors.current_password" />
          </div>
          <div class="form-group floating">
            <input
              v-model="form.password"
              type="password"
              class="form-control floating"
              id="password"
              placeholder="Password"
            />
            <label for="password">Password</label>
            <InputError :message="form.errors.password" />
          </div>
          <div class="form-group floating">
            <input
              v-model="form.password_confirmation"
              type="password"
              class="form-control floating"
              id="password_confirmation"
              placeholder="Password Confirmation"
            />
            <label for="password_confirmation">Password Confirmation</label>
            <InputError :message="form.errors.password_confirmation" />
          </div>
          <button type="submit" class="btn btn-sm btn-submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</template>
