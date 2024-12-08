<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
});

const page = usePage();
const currentTab = ref(window.localStorage.getItem("currentProfileTab") ?? 0);

const tabs = [
  {
    label: "Profile Information",
    component: UpdateProfileInformationForm,
    show: page.props.jetstream.canUpdateProfileInformation,
  },
  {
    label: "Update Password",
    component: UpdatePasswordForm,
    show: page.props.jetstream.canUpdatePassword,
  },
  {
    label: "Two Factor Authentication",
    component: TwoFactorAuthenticationForm,
    show: page.props.jetstream.canManageTwoFactorAuthentication,
  },
  {
    label: "Browser Sessions",
    component: LogoutOtherBrowserSessionsForm,
    show: true,
  },
  {
    label: "Delete Account",
    component: DeleteUserForm,
    show: page.props.jetstream.hasAccountDeletionFeatures,
  },
];

const updateCurrentTab = (idx) => {
  window.localStorage.setItem("currentProfileTab", idx);
  currentTab.value = idx;
};

const featuredTabs = computed(() => tabs.filter((i) => i.show));
</script>

<template>
  <MainLayout module="User Profile" header-module="User">
    <div class="my-4">
      <button
        v-for="(tab, idx) in featuredTabs"
        :key="idx"
        :class="['tabs-button', { active: currentTab == idx }]"
        @click="updateCurrentTab(idx)"
      >
        {{ featuredTabs[idx].label }}
      </button>
      <component
        :is="featuredTabs[currentTab].component"
        :user="$page.props.auth.user"
        :requires-confirmation="confirmsTwoFactorAuthentication"
        :sessions="sessions"
        class="tab"
      ></component>
    </div>
  </MainLayout>
</template>

<style scoped>
.tabs-button {
  padding: 6px 10px;
  cursor: pointer;
  background-color: #908b85;
  color: #f7f8f8;
  margin-bottom: -1px;
  margin-right: -1px;
  border: none;
  font-size: 0.9rem;
}
.tabs-button:hover {
  border-top: 1px solid #908b85;
  background-color: #f7f8f8;
  color: #33333a;
}
.tabs-button.active {
  border-top: 1px solid #908b85;
  background-color: #f7f8f8;
  color: #33333a;
}
.tab {
  padding: 1rem;
  background-color: #f7f8f8;
}
</style>
