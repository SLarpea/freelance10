<script setup>
import { inject, ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const swal = inject("$swal");
const global = inject("globalVar");

const props = defineProps({
  user: Object,
});

const form = useForm({
  _method: "PUT",
  name: props.user.name,
  email: props.user.email,
  photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0];
  }

  form.post(route("user-profile-information.update"), {
    errorBag: "updateProfileInformation",
    preserveScroll: true,
    onStart: () => global.isLoading = true,
    onSuccess: () => {
        clearPhotoFileInput();
        global.isLoading = false;
        swal({
          title: "Profile Updated!",
          text: "Your profile has been updated.",
          icon: "success",
          showConfirmButton: false
        });
    },
  });
};

const sendEmailVerification = () => {
  verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
  photoInput.value.click();
};

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    photoPreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
};

const deletePhoto = () => {
  router.delete(route("current-user-photo.destroy"), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null;
      clearPhotoFileInput();
    },
  });
};

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
};
</script>

<template>
  <div>
    <div class="row p-3">
      <div class="col-sm-12 col-md-4">
        <h5>Profile Information</h5>
        <p class="text-sm">
          Update your account's profile information and email address.
        </p>
      </div>
      <div class="col-sm-12 col-md-8">
        <form @submit.prevent="updateProfileInformation">
          <div
            v-if="$page.props.jetstream.managesProfilePhotos"
            class="col-span-6 sm:col-span-4"
          >
            <!-- Profile Photo File Input -->
            <input
              id="photo"
              ref="photoInput"
              type="file"
              class="hidden"
              @change="updatePhotoPreview"
            />

            <InputLabel for="photo" value="Photo" />

            <!-- Current Profile Photo -->
            <div v-show="!photoPreview" class="mt-2">
              <img
                :src="user.profile_photo_url"
                :alt="user.name"
                class="rounded-full h-20 w-20 object-cover"
              />
            </div>

            <!-- New Profile Photo Preview -->
            <div v-show="photoPreview" class="mt-2">
              <span
                class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                :style="'background-image: url(\'' + photoPreview + '\');'"
              />
            </div>

            <SecondaryButton
              class="mt-2 me-2"
              type="button"
              @click.prevent="selectNewPhoto"
            >
              Select A New Photo
            </SecondaryButton>

            <SecondaryButton
              v-if="user.profile_photo_path"
              type="button"
              class="mt-2"
              @click.prevent="deletePhoto"
            >
              Remove Photo
            </SecondaryButton>

            <InputError :message="form.errors.photo" class="mt-2" />
          </div>
          <div class="form-group floating">
            <input
              v-model="form.name"
              type="text"
              class="form-control floating"
              id="name"
              placeholder="Name"
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
            />
            <label for="email">Email</label>
            <InputError :message="form.errors.email" />
            <div
              v-if="
                $page.props.jetstream.hasEmailVerification &&
                user.email_verified_at === null
              "
            >
              <p class="text-sm mt-2">
                Your email address is unverified.

                <Link
                  :href="route('verification.send')"
                  method="post"
                  as="button"
                  class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click.prevent="sendEmailVerification"
                >
                  Click here to re-send the verification email.
                </Link>
              </p>

              <div
                v-show="verificationLinkSent"
                class="mt-2 font-medium text-sm text-green-600"
              >
                A new verification link has been sent to your email address.
              </div>
            </div>
          </div>
          <button
            type="submit"
            class="btn btn-sm btn-submit"
          >
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
