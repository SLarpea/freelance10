<script setup>
import { computed, inject, onMounted, reactive, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { getRouteName } from "@/helpers";
import { swalConfirmMsg, swalSuccessMsg } from "@/defaults";
import MainLayout from "@/Layouts/MainLayout.vue";
import SearchLayout from "@/Layouts/SearchLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputError from "@/Components/InputError.vue";

const swal = inject("$swal");
const global = inject("globalVar");

const props = defineProps({
  shops: Object,
});

let module = "shops";
let initialState = {
  id: null,
  name: "",
  description: "",
  status: 0,
};
const form = useForm({ ...initialState });
const originalForm = reactive({ ...initialState });
const formShow = ref(false);
const formAction = ref("");
const formURL = ref("");
const shop = reactive({});
const shopStatus = ref([]);
const checkAll = ref(false);
const checkAllIndeterminate = ref(false);
const checkAllData = ref([]);

const selectAction = (method, item = {}) => {
  formAction.value = method;
  formURL.value = getRouteName(module, method);
  switch (method) {
    case "view":
      Object.assign(shop, { ...item });
      formShow.value = true;
      break;

    case "add":
      resetForm();
      formShow.value = true;
      break;

    case "delete":
      let ids = checkAllData.value;
      form.transform((data) => ({
        ...data,
        ids,
      }));
      submit();
      break;

    case "status":
      Object.assign(shop, { ...item });
      shopStatus.value[shop.id] = shop.status === 0 ? 1 : 0;
      fillForm(form);
      submit();
      break;

    default:
      Object.assign(shop, { ...item });
      resetForm();
      formShow.value = true;
      break;
  }
};

const closeForm = () => {
  formShow.value = false;
  resetForm();
};

const resetForm = () => {
  form.clearErrors();
  switch (formAction.value) {
    case "add":
      Object.assign(form, { ...initialState });
      break;

    case "delete":
      checkAllData.value = [];
      break;

    default:
      fillForm(form);
      fillForm(originalForm);
      break;
  }
};

const fillForm = (form) => {
  form.id = shop.id ?? null;
  form.name = shop.name ?? "";
  form.description = shop.description ?? "";
  form.status = shop.status ?? 0;
};

const formUnchanged = () => {
  return (
    JSON.stringify({
      id: form.id,
      name: form.name,
      description: form.description,
      status: form.status,
    }) === JSON.stringify(originalForm)
  );
};

const submit = () => {
  if (formAction.value == "edit" && formUnchanged()) {
    toast("The form hasn't been altered", {
      type: "warning",
      dangerouslyHTMLString: true,
    });
  } else {
    swal(swalConfirmMsg[formAction.value](checkAllData.value)).then(
      (result) => {
        if (result.isConfirmed) {
          form.post(route(formURL.value), {
            onStart: () => (global.isLoading = true),
            onSuccess: () => {
              swal(swalSuccessMsg[formAction.value](checkAllData.value));
              closeForm();
            },
            onFinish: () => (global.isLoading = false),
          });
        } else {
          if (formAction.value == 'status') shopStatus.value[shop.id] = shop.status;
          else closeForm();
        }
      }
    );
  }
};

const handleUpdateQueryParams = (val) => {
  router.get(route("shops.index"), val, {
    preserveState: true,
    replace: true,
    onStart: () => (global.isSearchLoading = true),
    onFinish: () => (global.isSearchLoading = false),
  });
};

const allItemsChecked = computed(
  () => checkAllData.value.length === props.shops.data.length
);
const someItemsChecked = computed(
  () => checkAllData.value.length > 0 && !allItemsChecked.value
);

watch(checkAllData, () => {
  checkAll.value = allItemsChecked.value;
  checkAllIndeterminate.value = someItemsChecked.value;
});

watch(
  () => props.shops.data,
  (newValue, oldValue) => {
    newValue.forEach((item) => {
      shopStatus.value[item.id] = item.status;
    });
  },
  { immediate: true, deep: true }
);

watch(checkAll, (val) => {
  if (val) {
    checkAllData.value = props.shops.data.map((i) => i.id);
  } else {
    checkAllData.value = [];
  }
});
</script>

<template>
  <MainLayout module="Shops" header-module="Modules">
    <div class="page-card shadow">
      <div class="page-card-header">
        <span>
          <i class="bi bi-list-ol"></i>
          item list
        </span>
        <div class="btn-header">
          <button
            v-if="checkAllData.length > 0"
            type="button"
            class="btn btn-primary btn-sm btn-delete"
            @click.prevent="selectAction('delete')"
          >
            <i class="bi bi-trash"></i> Delete
          </button>
          <button
            type="button"
            class="btn btn-secondary btn-sm btn-add"
            @click.prevent="selectAction('add')"
          >
            <i class="bi bi-plus-lg"></i> Add
          </button>
        </div>
      </div>
      <SearchLayout @update-query-params="handleUpdateQueryParams" />
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-data">
          <colgroup>
            <col style="width: 1%" />
            <col style="width: 1%" />
            <col style="width: 20%" />
            <col style="width: 66%" />
            <col style="width: 1%" />
            <col style="width: 1%" />
          </colgroup>
          <thead>
            <tr>
              <th>
                <input
                  type="checkbox"
                  :indeterminate="checkAllIndeterminate"
                  @click="checkAll = !checkAll"
                  :checked="checkAll"
                />
              </th>
              <th>#</th>
              <th>Name</th>
              <th>Description</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in shops.data" :key="idx">
              <td>
                <input
                  v-model="checkAllData"
                  :value="item.id"
                  class="checkbox"
                  :id="'checkbox_' + item.id"
                  type="checkbox"
                />
                <label :for="'checkbox_' + item.id" class="checkbox"></label>
              </td>
              <td>{{ idx + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.description }}</td>
              <td class="text-center">
                <div class="toggle">
                  <input
                    type="checkbox"
                    :id="'switch_' + item.id"
                    :checked="shopStatus[item.id] === 1"
                    @click="selectAction('status', item)"
                    v-tippy="(item.status === 1) ? 'Active' : 'Inactive'"
                  />
                  <label :for="'switch_' + item.id"></label>
                </div>
              </td>
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
                        @click.prevent="selectAction('edit', item)"
                      >
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="dropdown-item py-1.5"
                        @click.prevent="selectAction('view', item)"
                      >
                        <i class="bi bi-eye mr-2"></i>View
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
        <Pagination :data="shops" />
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
              <h6 class="text-capitalize">{{ formAction }} Shop</h6>
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
              <template v-if="formAction != 'view'">
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
                    v-model="form.description"
                    type="textarea"
                    class="form-control floating"
                    id="description"
                    placeholder="Description"
                  />
                  <label for="description">Description</label>
                </div>
                <div class="custom-form-input">
                  <span class="custom-input-label">Status</span>
                  <div
                    class="status-wrapper"
                    @click="form.status = form.status === 1 ? 0 : 1"
                  >
                    <div class="toggle">
                      <input type="checkbox" :checked="form.status === 1" />
                      <label></label>
                    </div>
                    <span class="status-label">{{
                      form.status === 1 ? "Active" : "Inactive"
                    }}</span>
                  </div>
                </div>
              </template>
              <template v-else>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-view">
                    <colgroup>
                      <col width="25%" />
                      <col width="*" />
                    </colgroup>
                    <tbody>
                      <tr>
                        <td>
                          <strong>Name</strong>
                        </td>
                        <td>
                          <span>{{ shop.name }}</span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>Description</strong>
                        </td>
                        <td>
                          <span>{{ shop.description }}</span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>Status</strong>
                        </td>
                        <td>
                          <span>{{ shop.status == 1 ? "Active" : "Inactive" }}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </template>
            </div>
            <div class="modal-footer">
              <span v-if="formAction != 'view'" class="text-xs"
                >Ensure the shop name reflects your brand identity.</span
              >
              <div class="btn-footer">
                <button v-if="formAction != 'view'" type="submit" class="btn btn-primary btn-sm btn-submit">
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