<template>
    <div class="relative h-full w-full overflow-hidden">
        <div
            id="container"
            ref="container"
            class="relative h-full w-full"
            @mousemove="(e) => handleMouseMove(e, paper!)"
            @wheel.prevent="(e) => handleWheelMove(e, paper!)"
        ></div>
        <div
            v-if="runner"
            class="absolute inset-0 flex items-center justify-center p-10"
            @click.self="runner = null"
        >
            <div
                class="flex h-full max-h-[500px] max-w-lg grow flex-col overflow-y-auto overflow-x-hidden rounded-lg border bg-background-800"
            >
                <div class="sticky top-0 flex flex-col bg-background-800">
                    <input
                        :value="`/api/projects/${route.params.project}/methods/${props.methodId}/execute`"
                        class="rounded bg-background p-1"
                        disabled
                        type="text"
                    />
                    <div class="grid grid-cols-2 border-b">
                        <button
                            :class="{
                                'bg-background': runner === 'request',
                            }"
                            class="p-3"
                            @click="runner = 'request'"
                        >
                            Request
                        </button>
                        <button
                            :class="{
                                'bg-background': runner === 'response',
                            }"
                            class="p-3"
                            @click="runner = 'response'"
                        >
                            Response
                        </button>
                    </div>
                </div>
                <div class="flex grow flex-col gap-3 p-3">
                    <template v-if="runner === 'request'">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-lg">Params</h3>
                            <div
                                v-for="param in method?.blocks
                                    .find((b) => b.name === 'start')
                                    ?.outPorts.filter(
                                        (p) => p.type !== 'flow'
                                    ) ?? []"
                                class="grid grid-cols-2"
                            >
                                <h4>
                                    {{ param.name }}
                                    <span
                                        class="rounded-full bg-accent px-1 text-xs font-bold text-black"
                                    >
                                        {{ param.type }}
                                    </span>
                                </h4>
                                <input
                                    v-if="
                                        ['string', 'number'].includes(
                                            param.type
                                        )
                                    "
                                    v-model="runnerParams[param.name]"
                                    :placeholder="param.type"
                                    :type="
                                        param.type === 'string'
                                            ? 'text'
                                            : 'number'
                                    "
                                    class="bg-background"
                                />
                                <textarea
                                    v-else-if="
                                        ['object', 'array'].includes(param.type)
                                    "
                                    v-model="runnerParams[param.name]"
                                    :placeholder="'JSON ' + param.type"
                                    class="bg-background"
                                />
                                <input
                                    v-else-if="param.type === 'boolean'"
                                    v-model="runnerParams[param.name]"
                                    class="bg-background"
                                    type="checkbox"
                                />
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="text-sm">
                            Status: {{ runnerResponse?.status }}
                            {{ runnerResponse?.statusText }}
                        </div>
                        <h3 class="text-lg">Response data:</h3>
                        <pre class="bg-background">{{
                            runnerResponse?.data
                        }}</pre>
                    </template>
                </div>
                <button
                    :disabled="runnerLoading"
                    class="sticky bottom-0 bg-accent py-2 font-bold text-black active:bg-accent-400 disabled:bg-accent-500"
                    @click="sendRunner"
                >
                    Send
                </button>
            </div>
        </div>
        <editor-tools
            :disable-publish="method?.type === 'endpoint'"
            @publish="publish"
            @run="
                () => {
                    modal.show(MethodRunner, {
                        method,
                        projectId: route.params.project,
                    })
                }
            "
            @save="save"
        />
        <blocks-popup
            v-if="blocksPopupOpen"
            :filter="blocksPopupType"
            :style="{
                top: blocksPopupPosition?.y + 'px',
                left: blocksPopupPosition?.x + 'px',
            }"
            @add="addTemplateBlock(paper!, $event)"
            @close="closePopup"
        />
        <transition
            enter-active-class="duration-300"
            enter-from-class="translate-y-32"
            leave-active-class="duration-300"
            leave-to-class="translate-y-32"
        >
            <div
                v-if="showChat"
                ref="chatElement"
                class="absolute bottom-10 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full border border-petronas bg-dark/75 px-3 py-2 outline-1 backdrop-blur duration-300 focus-within:outline"
            >
                <input
                    ref="chatInput"
                    v-model="chatMessage"
                    class="appearance-none bg-dark/75 text-white outline-0 ring-0"
                    placeholder="Your message"
                    type="text"
                    @input="onSendMessage"
                    @keydown.enter="
                        () => {
                            onSendMessage()
                            showChat = false
                            chatMessage = ''
                        }
                    "
                    @keydown.esc="showChat = false"
                />
                <button
                    class="bg-dark/75 text-white"
                    @click="
                        () => {
                            onSendMessage()
                            showChat = false
                            chatMessage = ''
                        }
                    "
                >
                    <svg-icon
                        class="h-6 w-6 text-petronas"
                        name="send"
                    />
                </button>
            </div>
            <button
                v-else-if="showFitButton"
                class="absolute bottom-10 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full border border-petronas bg-dark/75 px-3 py-2 backdrop-blur duration-300"
                tabindex="-1"
                @click="
                    () => {
                        paper?.transformToFitContent({
                            verticalAlign: 'middle',
                        })
                        showFitButton = false
                    }
                "
            >
                <svg-icon
                    class="h-8 w-8"
                    name="fit"
                />
                Fit to content
            </button>
        </transition>
    </div>
</template>
<script lang="ts" setup>
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
import 'jointjs/dist/joint.css'
import Block from '@/models/Block'
import * as joint from 'jointjs'
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Stack } from '@/utils/stack'
import * as api from '@/utils/api'
import type { AxiosError, AxiosResponse } from 'axios'
import useEditor from '@/composables/editor'
import BlocksPopup from '@/components/BlocksPopup.vue'
import EditorTools from '@/components/EditorTools.vue'
import useModal from '@/plugins/modal'
import MethodRunner from '@/components/modals/MethodRunner.vue'
import { useEcho } from '@/composables/echo'
import {
    onClickOutside,
    useMagicKeys,
    useThrottleFn,
    whenever,
} from '@vueuse/core'
import SvgIcon from '@/components/SvgIcon.vue'
import chroma from 'chroma-js'
import User from '@/models/User'

const router = useRouter()
const runner = ref<string | null>(null)
const runnerLoading = ref(false)
const runnerParams = ref({})
const runnerResponse = ref<AxiosResponse | AxiosError | null>(null)
const container = ref<HTMLElement | null>(null)
const route = useRoute()

const props = defineProps({
    methodId: {
        type: String,
        required: true,
    },
})

const {
    blocksPopupOpen,
    blocksPopupPosition,
    blocksPopupType,
    showFitButton,
    method,
    blocks,
    mouseMoveHook,
    graphEventHook,
    addCursor,
    handleEvent,
    createPaper,
    initializeElements,
    registerEvents,
    handleWheelMove,
    handleMouseMove,
    closePopup,
    addTemplateBlock,
} = useEditor(props.methodId as string)

const modal = useModal()

function sendRunner() {
    runnerLoading.value = true
    api.post(
        `/projects/${route.value.params.project}/methods/${props.methodId}/debug`,
        runnerParams.value
    )
        .then((r) => (runnerResponse.value = r))
        .catch((e) => (runnerResponse.value = e))
        .finally(() => (runnerLoading.value = false))
}

const undoStack = new Stack()
const redoStack = new Stack()

function logbus() {
    console.log('undoStack', undoStack)
    console.log('redoStack', redoStack)
}

function undo() {
    const action = undoStack.pop()
    if (!action) return
    redoStack.push(action)
    switch (action.type) {
        case 'elementMove':
            let el = graph.getElements().find((el) => el.id === action.id)
            if (el) {
                el.translate(-action.dx, -action.dy)
            }
    }
}

function redo() {
    const action = redoStack.pop()
    if (!action) return
    undoStack.push(action)
    switch (action.type) {
        case 'elementMove':
            let el = graph.getElements().find((el) => el.id === action.id)
            if (el) {
                el.translate(action.dx, action.dy)
            }
    }
}

function save() {
    blocks.value.forEach((block) => {
        Block.save(block)
    })
}

function publish() {
    api.post(`/methods/${method.value.id}/publish`).then((res) => {
        router.push({
            name: 'asset',
            params: {
                id: res.data.data.id,
            },
        })
    })
}

let paper: joint.dia.Paper | null = null

const { echo } = useEcho()

const activeUsers = ref([
    // {
    //     id: 'test',
    //     name: 'John Doe',
    //     color: '#ff0000',
    //     text: chroma('#ff0000').luminance() > 0.5 ? 'black' : 'white',
    //     position: {
    //         x: 290,
    //         y: 400,
    //     },
    // },
])

function addActiveUser(user) {
    // generate random color that is not used by any other user
    let color = chroma.scale('Spectral').colors(10)
    let usedColors = activeUsers.value.map((u) => u.color)
    let availableColors = color.filter((c) => !usedColors.includes(c))
    let randomColor =
        availableColors[Math.floor(Math.random() * availableColors.length)]
    activeUsers.value.push({
        id: user.id,
        name: user.name,
        color: randomColor,
        text: chroma(randomColor).luminance() > 0.5 ? 'black' : 'white',
        position: {
            x: 290 + Math.random() * 100,
            y: 400 + Math.random() * 100,
        },
    })
}

const magicKeys = useMagicKeys()
const showChat = ref(false)
const chatInput = ref<HTMLInputElement | null>(null)
const chatElement = ref<HTMLDivElement | null>(null)
const chatMessage = ref('')
onClickOutside(chatElement, () => {
    showChat.value = false
})
whenever(magicKeys.Slash, () => {
    showChat.value = true
    setTimeout(() => {
        chatInput.value?.focus()
    }, 200)
})

function updateCursor(params) {
    const user_index = activeUsers.value.findIndex((u) => u.id === params.id)
    console.log(params)
    if (user_index !== -1) {
        if (params.x && params.y)
            activeUsers.value[user_index].position = {
                x: params.x,
                y: params.y,
            }
        if (params.message) {
            activeUsers.value[user_index].message = params.message

            if (activeUsers.value[user_index].timeout)
                clearTimeout(activeUsers.value[user_index].timeout)

            activeUsers.value[user_index].timeout = setTimeout(() => {
                activeUsers.value[user_index].message = ''
                const u = activeUsers.value[user_index]
                addCursor(
                    u.id,
                    u.name,
                    u.message,
                    u.color,
                    u.position?.x ?? 0,
                    u.position?.y ?? 0
                )
            }, 5000)
        }
    } else {
        addActiveUser({
            id: params.id,
            name: 'Unknown',
        })
    }
    const u = activeUsers.value[user_index]
    if (u) {
        console.log(u.message)
        addCursor(
            u.id,
            u.name,
            u.message,
            u.color,
            u.position?.x ?? 0,
            u.position?.y ?? 0
        )
    }
}

const channel = echo
    .join(`methods.${props.methodId}`)
    .here((users) => {
        users.forEach((user) => addActiveUser(user))
    })
    .joining((user) => {
        addActiveUser(user)
    })
    .leaving((user) => {
        const index = activeUsers.value.findIndex((u) => u.id === user.id)
        if (index !== -1) {
            activeUsers.value.splice(index, 1)
        }
    })
    .listenForWhisper('mouseMove', (params) => {
        updateCursor(params)
    })
    .listenForWhisper('event', (params) => {
        handleEvent(params)
    })
    .listenForWhisper('message', (params) => {
        console.log('got message', params)
        updateCursor(params)
    })

function onSendMessage() {
    if (chatMessage.value.trim() === '') return
    console.log('Sending message', chatMessage.value.trim())
    channel.whisper('message', {
        id: User.currentUser?.id,
        message: chatMessage.value.trim(),
    })
}

onMounted(() => {
    console.log('Initializing graph')
    paper = createPaper()
    initializeElements()
    registerEvents(paper)
    const throttleMouse = useThrottleFn((params) => {
        channel.whisper('mouseMove', {
            id: User.currentUser?.id,
            ...params,
        })
    }, 100)
    const throttleEvent = useThrottleFn((params) => {
        channel.whisper('event', params)
    }, 200)

    addCursor(
        'test',
        'Nazar',
        'Really long text that should be wrapped in three lines. Or even more',
        'red',
        290,
        400
    )
    setTimeout(() => {
        addCursor('test', 'Nazar', 'Finish', 'green', 290, 450)
    }, 1000)
    setTimeout(() => {
        addCursor('test', 'Nazar', 'Finish', 'green', 600, 400)
    }, 2000)
    mouseMoveHook.on((e) => {
        throttleMouse(e)
    })
    graphEventHook.on((e) => {
        if (e.type === 'element-move') {
            throttleEvent(e)
        } else {
            channel.whisper('event', e)
        }
    })
})
</script>
<style scoped>
#container {
    border: 1px solid #000;
    user-select: none;
}

.available-magnet {
    fill: #5da271;
    stroke: yellow;
    stroke-width: 2px;
}

/* element styling */
.available-cell rect {
    stroke-dasharray: 5, 2;
}
</style>
