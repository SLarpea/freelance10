<script setup>
import { ref, computed, watch, inject } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import RequiresPasswordConfirmation from "@/Components/RequiresPasswordConfirmation.vue";
import InputError from "@/Components/InputError.vue";

const global = inject("globalVar");
const props = defineProps({
  requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
  code: "",
});

const twoFactorEnabled = computed(
  () => !enabling.value && page.props.auth.user?.two_factor_enabled
);

watch(twoFactorEnabled, () => {
  if (!twoFactorEnabled.value) {
    confirmationForm.reset();
    confirmationForm.clearErrors();
  }
});

const enableTwoFactorAuthentication = () => {
  global.isLoading = true;
  enabling.value = true;

  router.post(
    route("two-factor.enable"),
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        global.isLoading = false;
        Promise.all([showQrCode(), showSetupKey(), showRecoveryCodes()]);
      },
      onFinish: () => {
        enabling.value = false;
        confirming.value = props.requiresConfirmation;
      },
    }
  );
};

const showQrCode = () => {
  return axios.get(route("two-factor.qr-code")).then((response) => {
    qrCode.value = response.data.svg;
  });
};

const showSetupKey = () => {
  return axios.get(route("two-factor.secret-key")).then((response) => {
    setupKey.value = response.data.secretKey;
  });
};

const showRecoveryCodes = () => {
  return axios.get(route("two-factor.recovery-codes")).then((response) => {
    recoveryCodes.value = response.data;
  });
};

const confirmTwoFactorAuthentication = () => {
  confirmationForm.post(route("two-factor.confirm"), {
    errorBag: "confirmTwoFactorAuthentication",
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      confirming.value = false;
      qrCode.value = null;
      setupKey.value = null;
    },
  });
};

const regenerateRecoveryCodes = () => {
  axios
    .post(route("two-factor.recovery-codes"))
    .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
  disabling.value = true;

  router.delete(route("two-factor.disable"), {
    preserveScroll: true,
    onSuccess: () => {
      disabling.value = false;
      confirming.value = false;
    },
  });
};
</script>

<template>
  <div>
    <div class="row p-3">
      <div class="col-sm-12 col-md-4">
        <h5>Two Factor Authentication</h5>
        <p class="text-sm">
          Add additional security to your account using two factor
          authentication.
        </p>
      </div>
      <div class="col-sm-12 col-md-8">
        <h6
          v-if="twoFactorEnabled && !confirming"
          class="font-medium text-gray-900"
        >
          You have enabled two factor authentication.
        </h6>

        <h6
          v-else-if="twoFactorEnabled && confirming"
          class="font-medium text-gray-900"
        >
          Finish enabling two factor authentication.
        </h6>

        <h6 v-else class="font-medium text-gray-900">
          You have not enabled two factor authentication.
        </h6>

        <p class="text-sm text-justify">
          When two factor authentication is enabled, you will be prompted for a
          secure, random token during authentication. You may retrieve this
          token from your phone's Google Authenticator application.
        </p>

        <div v-if="twoFactorEnabled">
          <div v-if="qrCode">
            <div class="mt-3 max-w-xl text-sm text-gray-600">
              <p v-if="confirming" class="font-semibold">
                To finish enabling two factor authentication, scan the following
                QR code using your phone's authenticator application or enter
                the setup key and provide the generated OTP code.
              </p>

              <p v-else>
                Two factor authentication is now enabled. Scan the following QR
                code using your phone's authenticator application or enter the
                setup key.
              </p>
            </div>

            <div class="mt-3 p-2 inline-block" v-html="qrCode" />

            <div v-if="setupKey" class="mt-3 max-w-xl text-sm text-gray-600">
              <p class="font-semibold">
                Setup Key: <span v-html="setupKey"></span>
              </p>
            </div>

            <div v-if="confirming" class="mt-3">
              <div class="form-group floating">
                <input
                  id="code"
                  v-model="confirmationForm.code"
                  type="text"
                  name="code"
                  class="form-control floating"
                  inputmode="numeric"
                  autofocus
                  autocomplete="one-time-code"
                  placeholder="Confirmation Code"
                  @keyup.enter="confirmTwoFactorAuthentication"
                />
                <label for="code">Confirmation Code</label>
                <InputError :message="confirmationForm.errors.code" />
              </div>
            </div>
          </div>

          <div v-if="recoveryCodes.length > 0 && !confirming">
            <div class="mt-3 max-w-xl text-sm text-gray-600">
              <p class="font-semibold">
                Store these recovery codes in a secure password manager. They
                can be used to recover access to your account if your two factor
                authentication device is lost.
              </p>
            </div>

            <div
              class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg"
            >
              <div v-for="code in recoveryCodes" :key="code">
                {{ code }}
              </div>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <div v-if="!twoFactorEnabled">
            <RequiresPasswordConfirmation
              @confirmed="enableTwoFactorAuthentication"
            >
              <button
                type="submit"
                class="btn btn-sm btn-submit"
                :class="{ 'opacity-25': enabling }"
                :disabled="enabling"
              >
                Enable
              </button>
            </RequiresPasswordConfirmation>
          </div>

          <div v-else>
            <RequiresPasswordConfirmation
              @confirmed="confirmTwoFactorAuthentication"
            >
              <button
                v-if="confirming"
                type="submit"
                class="btn btn-sm btn-submit"
                :class="{ 'opacity-25': enabling }"
                :disabled="enabling"
              >
                Enable
              </button>
            </RequiresPasswordConfirmation>

            <RequiresPasswordConfirmation @confirmed="regenerateRecoveryCodes">
              <button
                v-if="recoveryCodes.length > 0 && !confirming"
                type="button"
                class="btn btn-secondary btn-sm btn-close mx-1"
              >
                Regenerate Recovery Codes
              </button>
            </RequiresPasswordConfirmation>

            <RequiresPasswordConfirmation @confirmed="showRecoveryCodes">
              <button
                v-if="recoveryCodes.length === 0 && !confirming"
                type="button"
                class="btn btn-secondary btn-sm btn-submit mx-1"
              >
                Show Recovery Codes
              </button>
            </RequiresPasswordConfirmation>

            <RequiresPasswordConfirmation
              @confirmed="disableTwoFactorAuthentication"
            >
              <button
                v-if="confirming"
                type="button"
                class="btn btn-secondary btn-sm btn-close mx-1"
                :class="{ 'opacity-25': disabling }"
                :disabled="disabling"
              >
                Cancel
              </button>
            </RequiresPasswordConfirmation>

            <RequiresPasswordConfirmation
              @confirmed="disableTwoFactorAuthentication"
            >
              <button
                v-if="!confirming"
                type="submit"
                class="btn btn-sm btn-danger"
                :class="{ 'opacity-25': disabling }"
                :disabled="disabling"
              >
                Disable
              </button>
            </RequiresPasswordConfirmation>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
