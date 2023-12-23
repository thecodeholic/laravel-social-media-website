<script setup>
import {ChatBubbleLeftEllipsisIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import IndigoButton from "@/Components/app/IndigoButton.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import {usePage, Link} from "@inertiajs/vue3";
import {ref} from "vue";
import axiosClient from "@/axiosClient.js";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";

const authUser = usePage().props.auth.user;

const newCommentText = ref('')
const editingComment = ref(null);

const props = defineProps({
    post: Object,
    data: Object,
    parentComment: {
        type: [Object, null],
        default: null
    }
})
const emit = defineEmits(['commentCreate', 'commentDelete']);

function startCommentEdit(comment) {
    console.log(comment)
    editingComment.value = {
        id: comment.id,
        comment: comment.comment.replace(/<br\s*\/?>/gi, '\n') // <br />, <br > <br> <br/>, <br    />
    }
}

function createComment() {
    axiosClient.post(route('post.comment.create', props.post), {
        comment: newCommentText.value,
        parent_id: props.parentComment?.id || null
    })
        .then(({data}) => {
            newCommentText.value = ''
            props.data.comments.unshift(data)
            if (props.parentComment) {
                props.parentComment.num_of_comments++;
            }
            props.post.num_of_comments++;
            emit('commentCreate', data)
        })
}

function deleteComment(comment) {
    if (!window.confirm('Are you sure you want to delete this comment?')) {
        return false;
    }
    axiosClient.delete(route('comment.delete', comment.id))
        .then(({data}) => {

            const commentIndex = props.data.comments.findIndex(c => c.id === comment.id)
            props.data.comments.splice(commentIndex, 1)
            console.log(props.data.comments)
            if (props.parentComment) {
                props.parentComment.num_of_comments -= data.deleted;
            }
            props.post.num_of_comments-= data.deleted;
            emit('commentDelete', comment)
        })
}

function updateComment() {
    axiosClient.put(route('comment.update', editingComment.value.id), editingComment.value)
        .then(({data}) => {
            editingComment.value = null
            props.data.comments = props.data.comments.map((c) => {
                if (c.id === data.id) {
                    return data
                }
                return c;
            })
        })
}

function sendCommentReaction(comment) {
    axiosClient.post(route('comment.reaction', comment.id), {
        reaction: 'like'
    })
        .then(({data}) => {
            comment.current_user_has_reaction = data.current_user_has_reaction
            comment.num_of_reactions = data.num_of_reactions;
        })
}

function onCommentCreate(comment) {
    if (props.parentComment) {
        props.parentComment.num_of_comments++;
    }
    emit('commentCreate', comment)
}

function onCommentDelete(comment) {
    if (props.parentComment) {
        props.parentComment.num_of_comments--;
    }
    emit('commentDelete', comment)
}
</script>

<template>
    <div v-if="authUser" class="flex gap-2 mb-3">
        <Link :href="route('profile', authUser.username)">
            <img :src="authUser.avatar_url"
                 class="w-[40px] rounded-full border border-2 transition-all hover:border-blue-500"/>
        </Link>
        <div class="flex flex-1">
            <InputTextarea v-model="newCommentText" placeholder="Enter your comment here" rows="1"
                           class="w-full max-h-[160px] resize-none rounded-r-none"></InputTextarea>
            <IndigoButton @click="createComment" class="rounded-l-none w-[100px] ">Submit</IndigoButton>
        </div>
    </div>
    <div>
        <div v-for="comment of data.comments" :key="comment.id" class="mb-4">
            <div class="flex justify-between gap-2">
                <div class="flex gap-2">
                    <a href="javascript:void(0)">
                        <img :src="comment.user.avatar_url"
                             class="w-[40px] rounded-full border border-2 transition-all hover:border-blue-500"/>
                    </a>
                    <div>
                        <h4 class="font-bold">
                            <a href="javascript:void(0)" class="hover:underline">
                                {{ comment.user.name }}
                            </a>
                        </h4>
                        <small class="text-xs text-gray-400">{{ comment.updated_at }}</small>
                    </div>
                </div>
                <EditDeleteDropdown :user="comment.user"
                                    :post="post"
                                    :comment="comment"
                                    @edit="startCommentEdit(comment)"
                                    @delete="deleteComment(comment)"/>
            </div>
            <div class="pl-12">
                <div v-if="editingComment && editingComment.id === comment.id">
                    <InputTextarea v-model="editingComment.comment" placeholder="Enter your comment here"
                                   rows="1" class="w-full max-h-[160px] resize-none"></InputTextarea>

                    <div class="flex gap-2 justify-end">
                        <button @click="editingComment = null" class="rounded-r-none text-indigo-500">cancel
                        </button>
                        <IndigoButton @click="updateComment" class="w-[100px]">update
                        </IndigoButton>
                    </div>
                </div>
                <ReadMoreReadLess v-else :content="comment.comment" content-class="text-sm flex flex-1"/>
                <Disclosure>
                    <div class="mt-1 flex gap-2">
                        <button @click="sendCommentReaction(comment)"
                                class="flex items-center text-xs text-indigo-500 py-0.5 px-1  rounded-lg"
                                :class="[
                                            comment.current_user_has_reaction ?
                                             'bg-indigo-50 hover:bg-indigo-100' :
                                             'hover:bg-indigo-50'
                                        ]">
                            <HandThumbUpIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">{{ comment.num_of_reactions }}</span>
                            {{ comment.current_user_has_reaction ? 'unlike' : 'like' }}
                        </button>
                        <DisclosureButton
                            class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100 rounded-lg">
                            <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">{{ comment.num_of_comments }}</span>
                            comments
                        </DisclosureButton>
                    </div>
                    <DisclosurePanel class="mt-3">
                        <CommentList :post="post"
                                     :data="{comments: comment.comments}"
                                     :parent-comment="comment"
                                     @comment-create="onCommentCreate"
                                     @comment-delete="onCommentDelete"/>
                    </DisclosurePanel>
                </Disclosure>
            </div>
        </div>
        <div v-if="!data.comments.length" class="py-4 text-center dark:text-gray-100">
            There are no comments.
        </div>
    </div>
</template>

<style scoped>

</style>
