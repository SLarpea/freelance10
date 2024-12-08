<script setup>
import { computed, inject, reactive, ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { formatDate } from "@/helpers";
import { swalConfirmMsg, swalSuccessMsg } from "@/defaults";
import MainLayout from "@/Layouts/MainLayout.vue";
import SearchLayout from "@/Layouts/SearchLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputError from "@/Components/InputError.vue";

const swal = inject("$swal");
const global = inject("globalVar");

const props = defineProps({
  logs: Object,
});

const handleUpdateQueryParams = (val) => {
  router.get(route("logs.index"), val, {
    preserveState: true,
    replace: true,
    onStart: () => (global.isSearchLoading = true),
    onFinish: () => (global.isSearchLoading = false),
  });
};
</script>

<template>
  <MainLayout module="Activity Logs" header-module="Web App Tracker">
    <div class="page-card shadow">
      <!-- <SearchLayout @update-query-params="handleUpdateQueryParams" /> -->
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-data">
          <colgroup>
            <col style="width: 1%" />
            <col style="width: 7%" />
            <col style="width: 15%" />
            <col style="width: 5%" />
            <col style="width: '*'" />
            <col style="width: 5%" />
            <col style="width: 5%" />
            <col style="width: 9%" />
          </colgroup>
          <thead>
            <tr>
              <th>#</th>
              <th>Causer</th>
              <th>Causer Email</th>
              <th class="text-center">Event</th>
              <th>Properties</th>
              <th class="text-center">Table</th>
              <th class="text-center">IP</th>
              <th class="text-center">Created At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in logs.data" :key="idx">
              <td>{{ idx + 1 }}</td>
              <td>{{ item.role }}</td>
              <td>{{ item.email }}</td>
              <td class="text-center">{{ item.event }}</td>
              <td>
                <pre style="margin: 0">{{ JSON.stringify(item.properties, null, 2) }}</pre>
              </td>
              <td class="text-center">{{ item.subject_type }}</td>
              <td class="text-center">{{ item.properties.executor.ip }}</td>
              <td class="text-center">{{ formatDate(item.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div>
        <Pagination :data="logs" />
      </div>
    </div>
  </MainLayout>
</template>