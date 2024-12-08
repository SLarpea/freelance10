<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import PageLoader from "@/Components/PageLoader.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
  email: "",
  password: "",
});

const isLoading = ref(false);

const submit = () => {
  form.clearErrors();
  form.post(route("login"), {
    onStart: () => isLoading.value = true,
    onSuccess: () => isLoading.value = false,
    onError: () => isLoading.value = false,
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <PageLoader :loading="isLoading" />
  <Head title="Log in" />

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
                    v-model="form.email"
                    type="text"
                    class="form-control floating"
                    id="email"
                    placeholder="Email"
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
                    autocomplete="off"
                  />
                  <label for="password">Password</label>
                  <InputError :message="form.errors.password" />
                </div>
                <div class="btn-footer">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm btn-submit"
                    @click.prevent="submit"
                  >
                    Login
                  </button>
                  <span class="switch">I'm new</span>
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
  @import '../../../css/authentication.css';
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
