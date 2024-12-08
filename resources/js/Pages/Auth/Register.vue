<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import InputError from "@/Components/InputError.vue";
import PageLoader from "@/Components/PageLoader.vue";

const isLoading = ref(false);

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

const submit = () => {
  form.clearErrors();
  form.post(route("register"), {
    onStart: () => isLoading.value = true,
    onError: () => isLoading.value = false,
    onFinish: () => {
        isLoading.value = false;
        form.reset("password", "password_confirmation");
    }
  });
};
</script>

<template>
  <PageLoader :loading="isLoading" />
  <Head title="Register" />

  <AuthenticationCard>
    <div class="container">
      <section id="formHolder">
        <div class="row">
          <!-- Brand Box -->
          <div class="col-sm-6 brand">
            <a href="#" class="logo">FreeTaste10 <span>.</span></a>

            <div class="heading">
              <h2>Marina</h2>
              <p class="typing-demo">Your Right Choice</p>
            </div>

            <div class="success-msg">
              <p>Great! You are one of our members now</p>
              <a href="#" class="profile">Your Profile</a>
            </div>
          </div>

          <!-- Form Box -->
          <div class="col-sm-6 form">
            <!-- Login Form -->
            <div class="login form-peice">
              <form @submit="submit">
                <div class="form-group floating">
                  <input
                    v-model="form.name"
                    type="text"
                    class="form-control floating"
                    id="name"
                    placeholder="Name"
                    autocomplete="on"
                  />
                  <label for="name">Name</label>
                  <InputError :message="form.errors.name" />
                </div>
                <div class="form-group floating">
                  <input
                    v-model="form.email"
                    type="text"
                    class="form-control floating"
                    id="email"
                    placeholder="Email"
                    autocomplete="on"
                  />
                  <label for="email">Email</label>
                  <InputError :message="form.errors.email" />
                </div>
                <div class="form-group floating">
                  <input
                    v-model="form.password"
                    type="password"
                    class="form-control floating"
                    id="password"
                    placeholder="Password"
                    autocomplete="new-password"
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
                    placeholder="Confirm Password"
                    autocomplete="new-password"
                  />
                  <label for="password_confirmation">Confirm Password</label>
                  <InputError :message="form.errors.password_confirmation" />
                </div>
                <div class="btn-footer">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm btn-submit"
                    @click.prevent="submit"
                  >
                    Register
                  </button>
                  <span class="switch">I'm New</span>
                </div>
              </form>
            </div>
            <!-- End Login Form -->

            <!-- Signup Form -->
            <div class="signup form-peice switched">
              <form class="signup-form" action="#" method="post">
                <div class="CTA">
                  <input type="submit" value="Signup Now" id="submit" />
                  <span class="switch">I have an account</span>
                </div>
              </form>
            </div>
            <!-- End Signup Form -->
          </div>
        </div>
      </section>

      <footer>
        <p>Developed by: <a href="#" target="_blank">ArSu&#8482;</a></p>
      </footer>
    </div>
  </AuthenticationCard>
</template>

<style scoped>
@import "../../../css/authentication.css";
</style>

<script>
$(document).ready(function () {
  $(".switch").click(function (e) {
    e.preventDefault();
    var $formPiece = $(this).parents(".form-peice");
    $formPiece.toggleClass("switched");
    $formPiece
      .siblings(".form-peice")
      .toggleClass("switched", !$formPiece.hasClass("switched"));
    $(this).toggleClass("active");
  });
});
</script>
