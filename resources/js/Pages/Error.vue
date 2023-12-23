<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    title: String,
    body: String
})

</script>

<template>
    <AuthenticatedLayout>
        <div class="py-16 text-center dark:text-gray-100">
            <h1 class="text-2xl">{{ title }}</h1>
            <div>{{ body }}</div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
