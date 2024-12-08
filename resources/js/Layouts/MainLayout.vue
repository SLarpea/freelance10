<script setup>
import { ref } from "vue";
import { inject } from "@vue/runtime-core";
import { Head, router } from "@inertiajs/vue3";
import PageLoader from "@/Components/PageLoader.vue";
import NavBar from "@/Components/Navbar.vue";
import SideBar from "@/Components/Sidebar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Footer from "@/Components/Footer.vue";

defineProps({
  module: String,
  headerModule: String,
});

const global = inject("globalVar");
</script>

<template>
  <div class="wrapper">
    <PageLoader :loading="global.isLoading" />
    <Head :title="module" />
    <NavBar :module="module" />
    <SideBar :module="module" />
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <Breadcrumb :module="module" :header="headerModule" />
          <main>
            <slot />
          </main>
        </div>
      </section>
      <Footer />
    </div>
  </div>
</template>