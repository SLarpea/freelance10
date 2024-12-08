<script setup>
import { computed, inject, reactive, ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { getRouteName } from "@/helpers";
import { swalConfirmMsg, swalSuccessMsg } from "@/defaults";
import VOnClickOutside from "../../Directives/clickOutside";
import MainLayout from "@/Layouts/MainLayout.vue";
import SearchLayout from "@/Layouts/SearchLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputError from "@/Components/InputError.vue";

const swal = inject("$swal");
const global = inject("globalVar");

const props = defineProps({
  users: Object,
  roles: Object
});

let module = "user-roles";
let initialState = {
  user_id: null,
  role_name: null
}
const form = useForm({ ...initialState });
const originalForm = reactive({ ...initialState });
const formShow = ref(false);
const formAction = ref("");
const formURL = ref("");
const user = reactive({});
const showRoleOptions = ref(false);

const selectAction = (method, item = {}) => {
  formAction.value = method;
  Object.assign(user, { ...item });
  formURL.value = getRouteName(module, method);
  switch (method) {
    case "add":
    case "edit":
    case "assign":
      formShow.value = true;
      resetForm();
      break;

    case "status":
      roleStatus.value[role.id] = role.status === 0 ? 1 : 0;
      resetForm();
      submit();
      break;

    case "delete":
      resetForm();
      submit();
      break;
  }
};

const closeForm = () => {
  formShow.value = false;
  resetForm();
};

const resetForm = () => {
  if (formAction.value == "add") {
    // RESETTING FORM TO ITS ORIGINAL STATE
    Object.assign(form, { ...initialState });
  } else {
    fillForm(form);
    fillForm(originalForm);
  }
};

const fillForm = (form) => {
  form.user_id = user.id ?? null;
  form.role_name = user.roles.length > 0 ? user.roles[0].name : null;
}

const formUnchanged = () => {
  return (
    JSON.stringify({
      user_id: form.user_id,
      role_name: form.role_name,
    }) === JSON.stringify(originalForm)
  );
};

const submit = () => {
  if (formAction.value == "assign" && formUnchanged() || !form.role_name) {
    toast("The form hasn't been altered", {
      type: "warning",
      dangerouslyHTMLString: true,
    });
  } else {
    swal(swalConfirmMsg[formAction.value]()).then((result) => {
      if (result.isConfirmed) {
        form.clearErrors();
        form.post(route(formURL.value), {
          onStart: () => (global.isLoading = true),
          onSuccess: () => {
            swal(swalSuccessMsg[formAction.value]());
            formShow.value = false;
          },
          onFinish: () => (global.isLoading = false),
        });
      } else {
        if (formAction.value == 'status') roleStatus.value[role.id] = role.status;
        else resetForm();
      }
    });
  }
};

const handleUpdateQueryParams = (val) => {
  router.get(route("user-roles.index"), val, {
    preserveState: true,
    replace: true,
    onStart: () => (global.isSearchLoading = true),
    onFinish: () => (global.isSearchLoading = false),
  });
};

const closeOption = () => {
    showRoleOptions.value = false;
};

const choseRole = (role) => {
    form.role_name = role;
};

const userName = computed({
    get() {
        if (!form.user_id) return null;
        return props.users.data.find(i => form.user_id === i.id).name;
    },
    set(newValue) {
        form.user_id = newValue;
    }
});
</script>

<template>
  <MainLayout module="User Roles" header-module="Settings">
    <div class="page-card shadow">
      <div class="page-card-header">
        <span>
          <i class="bi bi-list-ol"></i>
          item list
        </span>
        <div class="btn-header">
        </div>
      </div>
      <SearchLayout @update-query-params="handleUpdateQueryParams" />
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-data">
          <colgroup>
            <col style="width: '*''" />
            <col style="width: 49%" />
            <col style="width: 49%" />
            <col style="width: '*'" />
          </colgroup>
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Role</th>
              <th class="text-center">Handle</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in users.data" :key="idx">
              <td>{{ idx + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.roles.length > 0 ? item.roles[0].name : "N/A" }}</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button
                        type="button"
                        class="dropdown-item py-1.5"
                        @click.prevent="selectAction('assign', item)"
                      >
                        <i class="bi bi-person-bounding-box mr-2"></i>Assign
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div>
        <Pagination :data="users" />
      </div>
    </div>
  </MainLayout>
  <form @submit.prevent="submit">
    <transition name="modal-fade">
      <div v-if="formShow" class="modal custom-modal">
        <div
          class="modal-dialog modal-dialog-centered modal-lg"
          role="document"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h6>Assign User Role</h6>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
                @click.prevent="closeForm"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group floating">
                <input
                  v-model="userName"
                  type="text"
                  class="form-control floating"
                  id="name"
                  placeholder="Name"
                  :disabled="formAction == 'assign'"
                />
                <label for="name">Name</label>
                <InputError :message="form.errors.name" />
              </div>
              
              <div class="custom-form-input" v-on-click-outside:div="closeOption" @click.prevent="showRoleOptions = !showRoleOptions">
                <span class="custom-input-label">Role</span>
                <span class="custom-input-text" :class="{ 'open-options': showRoleOptions }">{{ form.role_name ?? "Select Role" }}</span>
                <div class="custom-select-input" :class="{ 'is-open': showRoleOptions }">
                    <span v-for="item in roles" :key="item.id" @click.prevent="choseRole(item.name)">
                        {{ item.name }}
                    </span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <span class="text-xs">Make sure each role aligns with the user's responsibilities before submitting.</span>
              <div class="btn-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-submit">
                  Submit
                </button>
                <button
                  type="button"
                  class="btn btn-secondary btn-sm btn-close"
                  @click.prevent="closeForm"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </form>
</template>