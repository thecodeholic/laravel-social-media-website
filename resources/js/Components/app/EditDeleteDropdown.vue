<script setup>

import {EllipsisVerticalIcon, PencilIcon, TrashIcon, EyeIcon} from "@heroicons/vue/20/solid/index.js";
import {ClipboardIcon, MapPinIcon} from "@heroicons/vue/24/outline";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {usePage, Link} from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps({
    post: {
        type: Object,
        default: null
    },
    comment: {
        type: Object,
        default: null
    }
})
const authUser = usePage().props.auth.user || {};
const group = usePage().props.group

const user = computed(() => props.comment?.user || props.post?.user)

const editAllowed = computed(() => {
    return user.value.id === authUser.id
})

const pinAllowed = computed(() => {
    return user.value.id === authUser.id || props.post.group && props.post.group.role === 'admin'
})

const isPinned = computed(() => {
    if (group?.id) {
        return group?.pinned_post_id === props.post.id
    }

    return authUser?.pinned_post_id === props.post.id
})

const deleteAllowed = computed(() => {
    if (user.value.id === authUser.id) return true;

    if (props.post.user.id === authUser.id) return true;

    return !props.comment && props.post.group?.role === 'admin';
})

defineEmits(['edit', 'delete', 'pin'])

function copyToClipboard() {
    // Replace 'your-text-to-copy' with the actual text you want to copy
    const textToCopy = route('post.view', props.post.id);

    // Create a temporary element to copy the text
    const tempInput = document.createElement('input');
    tempInput.value = textToCopy;
    document.body.appendChild(tempInput);

    // Select the text in the temporary input
    tempInput.select();
    document.execCommand('copy');

    // Remove the temporary input from the DOM
    document.body.removeChild(tempInput);
}

</script>

<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="w-8 h-8 z-10 rounded-full hover:bg-black/5 transition flex items-center justify-center"
            >

                <EllipsisVerticalIcon
                    class="w-5 h-5"
                    aria-hidden="true"
                />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                v-if="authUser"
                class="absolute z-20 right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="px-1 py-1">

                    <MenuItem v-slot="{ active }">
                        <Link :href="route('post.view', post.id)"
                              :class="[
                              active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <EyeIcon
                                class="mr-2 h-5 w-5"
                                aria-hidden="true"
                            />
                            Open Post
                        </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <button
                            @click="copyToClipboard"
                            :class="[
                              active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <ClipboardIcon
                                class="mr-2 h-5 w-5"
                                aria-hidden="true"
                            />
                            Copy Post URL
                        </button>
                    </MenuItem>
                    <MenuItem v-if="pinAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('pin')"
                            :class="[
                              active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <MapPinIcon
                                class="mr-2 h-5 w-5"
                                aria-hidden="true" />
                            {{ isPinned ? 'Unpin' : 'Pin' }}
                        </button>
                    </MenuItem>
                    <MenuItem v-if="editAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('edit')"
                            :class="[
                  active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                        >
                            <PencilIcon
                                class="mr-2 h-5 w-5"
                                aria-hidden="true"
                            />
                            Edit
                        </button>
                    </MenuItem>
                    <MenuItem v-if="deleteAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('delete')"
                            :class="[
                  active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                        >
                            <TrashIcon
                                class="mr-2 h-5 w-5"
                                aria-hidden="true"
                            />
                            Delete
                        </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<style scoped>

</style>
