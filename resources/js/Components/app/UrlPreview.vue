<script setup>
defineProps({
    preview: Object,
    url: String
})
</script>

<template>
    <a :href="url" target="_blank"
       v-if="preview && preview.title"
       class="block mt-4 border border-indigo-200 bg-indigo-50">
        <img :src="preview.image"
             class="max-w-full"
             :alt="preview.title"/>
        <div class="p-2 dark:text-gray-900">
            <h3 class="font-semibold">{{ preview.title }}</h3>
            <p class="text-sm m-none">{{ preview.description }}</p>
        </div>
    </a>
    <a :href="url" target="_blank"
       v-else-if="url"
       class="block mt-4 border border-indigo-200 bg-indigo-50">
        <div class="p-2">
            {{url}}
        </div>
    </a>
</template>

<style scoped>

</style>
