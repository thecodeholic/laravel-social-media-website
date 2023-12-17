<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import GroupListItems from "@/Components/app/GroupListItems.vue";

defineProps({
    groups: Array
})

</script>

<template>
    <div class="px-3 bg-white dark:bg-slate-950 rounded border dark:border-slate-900 dark:text-gray-100 h-full py-3 overflow-hidden">
        <div class="block lg:hidden">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="w-full">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">My Groups</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6 transition-all"
                             :class="open ? 'rotate-90 transform' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </DisclosureButton>
                <DisclosurePanel>
                    <GroupListItems :groups="groups"/>
                </DisclosurePanel>
            </Disclosure>
        </div>
        <div class="h-full overflow-hidden flex-col hidden lg:flex">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold">My Groups</h2>
            </div>
            <GroupListItems :groups="groups"/>
        </div>
    </div>
</template>

<style scoped>

</style>
