<script setup>
import {Link} from '@inertiajs/vue3';
import {ChevronRightIcon} from "@heroicons/vue/24/solid/index.js";

defineProps({
    post: {
        type: Object
    },
    showTime: {
        type: Boolean,
        default: true
    }
})

</script>

<template>
    <div class="flex items-center gap-2 ">
        <Link :href="route('profile', post.user.username)">
            <img :src="post.user.avatar_url"
                 class="w-[40px] rounded-full border border-2 transition-all hover:border-blue-500"/>
        </Link>
        <div>
            <h4 class="flex items-center font-bold">
                <Link :href="route('profile', post.user.username)" class="hover:underline">
                    {{ post.user.name }}
                </Link>
                <template v-if="post.group">
                    <ChevronRightIcon class="w-4"/>
                    <Link :href="route('group.profile', post.group.slug)" class="hover:underline">
                        {{ post.group.name }}
                    </Link>
                </template>
            </h4>
            <small v-if="showTime" class="text-gray-400">{{ post.updated_at }}</small>
        </div>
    </div>
</template>

<style scoped>

</style>
