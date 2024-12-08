<script setup>
defineProps({
  module: String,
  header: String,
});
</script>
<template>
  <div class="breadcrumb-wrapper">
    <span class="crumb active">{{ module }}</span>
    <span class="crumb">{{ header }}</span>
  </div>
</template>