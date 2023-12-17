<script setup>
defineProps({
    selected: Boolean,
    text: String
})
</script>

<template>
    <button
        :class="[
          'px-3 py-2.5 outline-none focus:outline-none focus:ring-none text-sm',
          selected
            ? 'bg-white dark:bg-slate-900 text-blue-500 border-b-2 border-blue-500'
            : 'text-gray-700 dark:text-gray-200',
        ]"
    >
        {{ text }}
    </button>
</template>

<style scoped>

</style>
