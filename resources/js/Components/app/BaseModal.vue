<script setup>
import {computed} from 'vue'
import {XMarkIcon} from '@heroicons/vue/24/solid'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'

const props = defineProps({
    title: {
        type: String,
        required: false
    },
    modelValue: Boolean
})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const emit = defineEmits(['update:modelValue', 'hide'])

function closeModal() {
    show.value = false
    emit('hide')
}

</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25"/>
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-md transform overflow-hidden rounded bg-white dark:bg-slate-900 text-left align-middle shadow-xl transition-all"
                            >
                                <DialogTitle v-if="title" as="h3"
                                             class="flex items-center justify-between py-3 px-4 font-medium bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-gray-100"
                                >
                                    {{ title }}
                                    <button @click="closeModal"
                                            class="w-8 h-8 rounded-full hover:bg-black/5 dark:hover:bg-black/30 transition flex items-center justify-center">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>
                                <slot/>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>

