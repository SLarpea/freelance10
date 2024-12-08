<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <span class="text-error">
            {{ message }}
        </span>
    </div>
</template>
