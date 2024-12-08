<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
  appURL: String,
});
</script>
<template>
  <MainLayout module="Swagger" header-module="API Documentation">
    <section class="section my-4">
      <iframe
        :src="appURL + 'api/documentation'"
        frameborder="0"
        width="100%"
        height="100%"
        style="height: calc(100vh - 150px)"
      ></iframe>
    </section>
  </MainLayout>
</template>