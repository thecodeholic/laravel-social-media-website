<script setup>
import {computed, ref} from 'vue'
import {XMarkIcon, CheckCircleIcon, CameraIcon} from '@heroicons/vue/24/solid'
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import {usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import {useForm} from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InviteUserModal from "@/Pages/Group/InviteUserModal.vue";

const imagesForm = useForm({
    thumbnail: null,
    cover: null,
})

const showNotification = ref(true)
const coverImageSrc = ref('')
const thumbnailImageSrc = ref('')
const showInviteUserModal = ref(false);

const authUser = usePage().props.auth.user;

const isCurrentUserAdmin = computed(() => props.group.role === 'admin')

const props = defineProps({
    errors: Object,
    success: {
        type: String,
    },
    group: {
        type: Object
    }
});

function onCoverChange(event) {
    imagesForm.cover = event.target.files[0]
    if (imagesForm.cover) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover)
    }
}

function onThumbnailChange(event) {
    imagesForm.thumbnail = event.target.files[0]
    if (imagesForm.thumbnail) {
        const reader = new FileReader()
        reader.onload = () => {
            thumbnailImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.thumbnail)
    }
}

function resetCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value = null
}

function resetThurmbnailImage() {
    imagesForm.thumbnail = null;
    thumbnailImageSrc.value = null
}

function submitCoverImage() {
    imagesForm.post(route('group.updateImages', props.group.slug), {
        onSuccess: () => {
            showNotification.value = true;
            resetCoverImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

function submitThurmbnailImage() {
    imagesForm.post(route('group.updateImages', props.group.slug), {
        onSuccess: () => {
            showNotification.value = true;
            resetThurmbnailImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div
                v-show="showNotification && success"
                class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white"
            >
                {{ success }}
            </div>
            <div
                v-if="errors.cover"
                class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white"
            >
                {{ errors.cover }}
            </div>
            <div class="group relative bg-white">
                <img :src="coverImageSrc || group.cover_url || '/img/default_cover.jpg'"
                     class="w-full h-[200px] object-cover">
                <div v-if="isCurrentUserAdmin" class="absolute top-2 right-2 ">
                    <button
                        v-if="!coverImageSrc"
                        class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-3 h-3 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                        </svg>

                        Update Cover Image
                        <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                               @change="onCoverChange"/>
                    </button>
                    <div v-else class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                        <button
                            @click="resetCoverImage"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center">
                            <XMarkIcon class="h-3 w-3 mr-2"/>
                            Cancel
                        </button>
                        <button
                            @click="submitCoverImage"
                            class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center">
                            <CheckCircleIcon class="h-3 w-3 mr-2"/>
                            Submit
                        </button>
                    </div>
                </div>

                <div class="flex">
                    <div class="flex items-center justify-center relative group/thumbnail -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                        <img :src="thumbnailImageSrc || group.thumbnail_url || '/img/default_avatar.webp'"
                             class="w-full h-full object-cover rounded-full">
                        <button
                            v-if="isCurrentUserAdmin && !thumbnailImageSrc"
                            class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/thumbnail:opacity-100">
                            <CameraIcon class="w-8 h-8"/>

                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                   @change="onThumbnailChange"/>
                        </button>

                        <div v-else-if="isCurrentUserAdmin" class="absolute top-1 right-0 flex flex-col gap-2">
                            <button
                                @click="resetThurmbnailImage"
                                class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                <XMarkIcon class="h-5 w-5"/>
                            </button>
                            <button
                                @click="submitThurmbnailImage"
                                class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                <CheckCircleIcon class="h-5 w-5"/>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center flex-1 p-4">
                        <h2 class="font-bold text-lg">{{ group.name }}</h2>

                        <PrimaryButton @click="showInviteUserModal = true"
                                       v-if="isCurrentUserAdmin">Invite Users</PrimaryButton>
                        <PrimaryButton v-if="!group.role && group.auto_approval">Join to Group</PrimaryButton>
                        <PrimaryButton v-if="!group.role && !group.auto_approval">Request to join</PrimaryButton>
                    </div>
                </div>
            </div>
            <div class="border-t">
                <TabGroup>
                    <TabList class="flex bg-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Followers" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Followings" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photos" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel class="bg-white p-3 shadow">
                            Posts
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            Followers
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            Followings
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            Photos
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
    <InviteUserModal v-model="showInviteUserModal" />
</template>

<style scoped>

</style>
