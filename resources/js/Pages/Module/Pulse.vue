<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
</script>
<template>
  <MainLayout module="Pulse" header-module="Web App Tracker">
    <section class="section my-4">
      <iframe
        :src="$page.props.appURL + 'pulse'"
        frameborder="0"
        width="100%"
        height="100%"
        style="height: calc(100vh - 150px)"
      ></iframe>
    </section>
  </MainLayout>
</template>