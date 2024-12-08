<script setup>
import { computed, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import PageLoader from "@/Components/PageLoader.vue";

const isLoading = ref(false);

const props = defineProps({
  status: String,
});

const form = useForm({});

const submit = () => {
  form.post(route("verification.send"), {
    onStart: () => isLoading.value = true,
    onError: () => isLoading.value = false,
    onSuccess: () => {
      isLoading.value = false;
      toast(
        "A new verification link has been sent to the email address you provided in your profile settings.",
        {
          type: "success",
          dangerouslyHTMLString: true,
        }
      );
    },
  });
};

const verificationLinkSent = computed(
  () => props.status === "verification-link-sent"
);
</script>

<template>
  <PageLoader :loading="isLoading" />
  <Head title="Verify Email" />

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
                <p class="text-sm text-justify">
                  Before continuing, could you verify your email address by
                  clicking on the link we just emailed to you? If you didn't
                  receive the email, we will gladly send you another.
                </p>
                <div class="btn-footer">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm btn-submit"
                    @click.prevent="submit"
                  >
                    Resend Verification Email
                  </button>
                  <Link :href="route('logout')" method="post" as="a">
                    Log Out
                  </Link>
                </div>
              </form>
            </div>
            <!-- End Login Form -->

            <!-- Signup Form -->
            <div class="signup form-peice switched">
              <form class="signup-form" action="#" method="post">
                <div class="CTA">
                  <input type="submit" value="Signup Now" id="submit" />
                  <a href="#" class="switch">I have an account</a>
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
