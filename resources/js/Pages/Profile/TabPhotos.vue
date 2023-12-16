<script setup>
import {isImage} from "@/helpers.js";
import {ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import {PaperClipIcon} from "@heroicons/vue/24/solid/index.js";
import {ref} from "vue";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";

defineProps({
    photos: Array
})

const currentPhotoIndex = ref(0)
const showModal = ref(false)

function openPhoto(index) {
    currentPhotoIndex.value = index
    showModal.value = true;
}

</script>

<template>
    <div class="grid gap-2 grid-cols-2 sm:grid-cols-3">
        <template v-for="(attachment, ind) of photos">
            <div @click="openPhoto(ind)"
                 class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">

                <!-- Download-->
                <a @click.stop :href="route('post.download', attachment)"
                   class="z-20 opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center text-gray-100 bg-gray-700 rounded absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
                    <ArrowDownTrayIcon class="w-4 h-4"/>
                </a>
                <!--/ Download-->

                <img v-if="isImage(attachment)"
                     :src="attachment.url"
                     class="object-contain aspect-square"/>
                <div v-else class="flex flex-col justify-center items-center">
                    <PaperClipIcon class="w-10 h-10 mb-3"/>

                    <small>{{ attachment.name }}</small>
                </div>
            </div>
        </template>
    </div>

    <AttachmentPreviewModal :attachments="photos || []"
                            v-model:index="currentPhotoIndex"
                            v-model="showModal"/>
</template>

<style scoped>

</style>
