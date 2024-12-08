<script setup>
import { inject, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
  sessions: Array,
});

const swal = inject("$swal");
const global = inject("globalVar");
const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: "",
});

const confirmLogout = () => {
  confirmingLogout.value = true;

  setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
  form.delete(route("other-browser-sessions.destroy"), {
    preserveScroll: true,
    onStart: () => global.isLoading = true,
    onSuccess: () => {
        closeModal();
        global.isLoading = false;
        swal({
            title: "Logout Sessions!",
            text: "All sessions has been logout.",
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
  confirmingLogout.value = false;

  form.reset();
};
</script>

<template>
  <div>
    <div class="row p-3">
      <div class="col-12">
        <h5>Browser Sessions</h5>
        <p class="text-sm text-justify">
          If necessary, you may log out of all of your other browser sessions
          across all of your devices. Some of your recent sessions are listed
          below; however, this list may not be exhaustive. If you feel your
          account has been compromised, you should also update your password.
        </p>

        <div v-if="sessions.length > 0">
          <div class="table-responsive">
            <table
              class="table table-sm table-striped table-bordered table-data"
            >
              <colgroup>
                <col width="50px" />
                <col width="30%" />
                <col width="30%" />
                <col width="*" />
              </colgroup>
              <thead>
                <tr>
                  <th class="text-center"></th>
                  <th class="text-center">Browser</th>
                  <th class="text-center">IP Address</th>
                  <th class="text-center">Device</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th class="text-center"></th>
                  <th class="text-center">Browser</th>
                  <th class="text-center">IP Address</th>
                  <th class="text-center">Device</th>
                </tr>
              </tfoot>
              <tbody>
                <tr v-for="(session, i) in sessions" :key="i">
                  <td class="text-center">
                    <svg
                      v-if="session.agent.is_desktop"
                      class="w-8 h-8 text-gray-500"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                      />
                    </svg>

                    <svg
                      v-else
                      class="w-8 h-8 text-gray-500"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
                      />
                    </svg>
                  </td>
                  <td class="text-center">
                    {{
                      session.agent.platform
                        ? session.agent.platform
                        : "Unknown"
                    }}
                    -
                    {{
                      session.agent.browser ? session.agent.browser : "Unknown"
                    }}
                  </td>
                  <td class="text-center">
                    {{ session.ip_address }}
                  </td>
                  <td class="text-center">
                    <span v-if="session.is_current_device" class="text-success"
                      ><strong>This device</strong></span
                    >
                    <span v-else>Last active {{ session.last_active }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div style="text-align: right">
          <button
            @click="confirmLogout"
            type="submit"
            class="btn btn-sm btn-submit"
          >
            <i class="bi bi-box-arrow-right"></i> Log Out Other Browser Sessions
          </button>
        </div>

        <transition name="modal-fade">
          <div v-if="confirmingLogout" class="modal custom-modal">
            <div
              class="modal-dialog modal-dialog-centered modal-lg"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title">
                    <i class="bi bi-tv"></i> Log Out Other Browser Sessions
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
                    Please enter your password to confirm you would like to log
                    out of your other browser sessions across all of your
                    devices.
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
                    >Logging out other sessions will sign out your account on those devices.</span
                  >
                  <div class="btn-footer">
                    <button @click="logoutOtherBrowserSessions" type="submit" class="btn btn-sm btn-submit">
                      Confirm
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
