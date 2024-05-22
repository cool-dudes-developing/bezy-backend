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
        <button
            :class="{
                'translate-y-32': !showFitButton,
            }"
            class="absolute bottom-10 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full border border-petronas bg-dark/75 px-3 py-2 backdrop-blur duration-300"
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
    </div>
</template>
<script lang="ts" setup>
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
import 'jointjs/dist/joint.css'
import Block from '@/models/Block'
import * as joint from 'jointjs'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Stack } from '@/utils/stack'
import * as api from '@/utils/api'
import type { AxiosError, AxiosResponse } from 'axios'
import useEditor from '@/composables/editor'
import BlocksPopup from '@/components/BlocksPopup.vue'
import EditorTools from '@/components/EditorTools.vue'
import useModal from '@/plugins/modal'
import MethodRunner from '@/components/modals/MethodRunner.vue'

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

const namespace = joint.shapes

let paper: joint.dia.Paper | null = null

onMounted(() => {
    console.log('Initializing graph')
    paper = createPaper()
    initializeElements()
    registerEvents(paper)
})
</script>
<style>
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
