<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import InputError from "@/Components/InputError.vue";
import PageLoader from "@/Components/PageLoader.vue";
import { ref } from "vue";
import { toast } from "vue3-toastify";

const isLoading = ref(false);

defineProps({
  status: String,
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.clearErrors();
  form.post(route("password.email"), {
    onStart: () => isLoading.value = true,
    onError: () => isLoading.value = false,
    onSuccess: () => {
      isLoading.value = false;
      toast(
        "A new password reset link has been sent to the email address you provided in your profile settings.",
        {
          type: "success",
          dangerouslyHTMLString: true,
        }
      );
    }
  });
};
</script>

<template>
  <PageLoader :loading="isLoading" />
  <Head title="Forgot Password" />

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
          </div>

          <!-- Form Box -->
          <div class="col-sm-6 form">
            <!-- Login Form -->
            <div class="login form-peice">
              <form @submit="submit">
                <p class="text-sm text-justify">
                  Forgot your password? No problem. Just let us know your email
                  address and we will email you a password reset link that will
                  allow you to choose a new one.
                </p>
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
                <div class="btn-footer">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm btn-submit"
                    @click.prevent="submit"
                  >
                    Email Password Reset Link
                  </button>
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
