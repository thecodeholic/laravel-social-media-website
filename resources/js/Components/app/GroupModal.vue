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
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import axiosClient from "@/axiosClient.js";
import GroupForm from "@/Components/app/GroupForm.vue";
import BaseModal from "@/Components/app/BaseModal.vue";

const props = defineProps({
    modelValue: Boolean
})

const formErrors = ref({});
const form = useForm({
    name: '',
    auto_approval: true,
    about: '',
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
    axiosClient.post(route('group.create'), form)
        .then(({data}) => {
            closeModal()
            emit('create', data)
        })
}

</script>

<template>
    <BaseModal title="Create new Group" v-model="show" @hide="closeModal()" >
        <div class="p-4 dark:text-gray-100">
            <GroupForm :form="form"/>
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

