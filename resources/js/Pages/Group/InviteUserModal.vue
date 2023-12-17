<script setup>
import {computed, ref} from 'vue'
import {XMarkIcon, BookmarkIcon} from '@heroicons/vue/24/solid'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {useForm, usePage} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import axiosClient from "@/axiosClient.js";
import BaseModal from "@/Components/app/BaseModal.vue";

const props = defineProps({
    modelValue: Boolean
})

const page = usePage()

const formErrors = ref({});
const form = useForm({
    email: '',
})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})


const emit = defineEmits(['update:modelValue', 'hide', 'create'])


function closeModal() {
    show.value = false
    emit('hide')
    resetModal();
}

function resetModal() {
    form.reset()
    formErrors.value = {}
}

function submit() {

    form.post(route('group.inviteUsers', page.props.group.slug), {
        onSuccess(res) {
            console.log(res)
            closeModal()
        },
        onError(res) {
            console.log(res)
        }
    })
}

</script>

<template>
    <BaseModal title="Invite Users" v-model="show" @hide="closeModal">
        <div class="p-4 dark:text-gray-100">
            <div class="mb-3">
                <label>Username or email</label>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :class="page.props.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    v-model="form.email"
                    required
                    autofocus
                />
                <div class="text-red-500">{{ page.props.errors.email }}</div>
            </div>
        </div>

        <div class="flex justify-end gap-2 py-3 px-4">
            <button
                @click="show=false"
                class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4"
            >
                <XMarkIcon class="w-5 h-5"/>
                Cancel
            </button>
            <button
                type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                @click="submit"
            >
                <BookmarkIcon class="w-4 h-4 mr-2"/>
                Submit
            </button>
        </div>
    </BaseModal>
</template>

