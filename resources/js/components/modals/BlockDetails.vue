<template>
    <modal-layout title="Block Details">
        <div
            v-if="block"
            class="flex h-full grow flex-col gap-3 overflow-y-auto overflow-x-hidden p-3"
        >
            <div class="flex justify-between">
                <div class="flex w-full justify-between">
                    <div>
                        <h1 class="text-xl">{{ block.title }}</h1>
                        <div class="flex gap-1">
                            <span
                                v-for="tag in [
                                    block.type,
                                    block.categoryGroup,
                                    block.superCategory,
                                ].reduce(
                                    (acc, cur) =>
                                        cur
                                            ? acc.includes(cur)
                                                ? acc
                                                : [...acc, cur]
                                            : acc,
                                    []
                                )"
                                class="rounded-full bg-accent px-1 text-xs font-bold text-black"
                            >
                                {{ tag }}
                            </span>
                        </div>
                    </div>
                    <button
                        v-if="block?.type === 'method'"
                        @click="
                            () => {
                                if (
                                    props.block?.project_id ===
                                    props.method?.project_id
                                ) {
                                    console.log(props.block)
                                    router.push({
                                        name: 'method',
                                        params: {
                                            project: props.method?.project_id,
                                            method: props.block?.block_id,
                                        },
                                    })
                                } else {
                                    router.push({
                                        name: 'blockPreview',
                                        params: {
                                            block: props.block
                                                ?.block_id as string,
                                        },
                                    })
                                }
                                emit('close')
                            }
                        "
                    >
                        <svg-icon
                            class="h-5 w-5"
                            name="external"
                        />
                    </button>
                </div>
            </div>
            <p>
                {{ block.description }}
            </p>

            <div
                v-if="block.type === 'variable'"
                class="flex w-full"
            >
                <input
                    v-model="block.constant"
                    class="grow rounded-xl bg-purple text-white"
                    placeholder="Constant value"
                />
                <button
                    class="rounded-xl bg-petronas px-3 py-1 text-dark"
                    @click="
                        () => {
                            Block.save(block)
                            emit('constantChanged')
                        }
                    "
                >
                    Set
                </button>
            </div>

            <div
                v-if="block?.inPorts?.length"
                class="divide-y divide-accent"
            >
                <small>Input</small>
                <div
                    v-for="port in block?.inPorts"
                    :key="port.id"
                >
                    <div class="flex flex-row justify-between">
                        <h2>{{ port.name }}</h2>
                        <div class="flex gap-3">
                            <h2>{{ port.type }}</h2>
                            <button
                                v-if="
                                    (block.name === 'start' ||
                                        block.name === 'end') &&
                                    port.type !== 'flow'
                                "
                                @click="Method.removePort(method.id, port.id)"
                            >
                                <svg-icon
                                    class="h-5 w-5 text-white"
                                    name="trashcan"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="method && block?.name === 'end'">
                    Add method output
                    <div class="flex flex-row justify-between">
                        <input
                            v-model="newPortName"
                            class="bg-background"
                            placeholder="name"
                            type="text"
                        />
                        <select
                            v-model="newPortType"
                            class="bg-background"
                        >
                            <option
                                v-for="type in [
                                    'string',
                                    'number',
                                    'boolean',
                                    'object',
                                    'array',
                                ]"
                                :key="type"
                                :value="type"
                            >
                                {{ type }}
                            </option>
                        </select>
                        <button
                            class="bg-background px-2"
                            @click="addPort"
                        >
                            add
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="block?.outPorts?.length"
                class="divide-y divide-accent"
            >
                <small>Output</small>
                <div
                    v-for="port in block?.outPorts"
                    :key="port.id"
                >
                    <div class="flex flex-row justify-between">
                        <h2>{{ port.name }}</h2>
                        <div class="flex gap-3">
                            <h2>{{ port.type }}</h2>
                            <button
                                v-if="
                                    (block.name === 'start' ||
                                        block.name === 'end') &&
                                    port.type !== 'flow'
                                "
                                @click="Method.removePort(method.id, port.id)"
                            >
                                <svg-icon
                                    class="h-5 w-5 text-white"
                                    name="trashcan"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="method && block?.name === 'start'">
                    Add method input
                    <div class="flex flex-row justify-between">
                        <input
                            v-model="newPortName"
                            class="bg-background"
                            placeholder="name"
                            type="text"
                        />
                        <select
                            v-model="newPortType"
                            class="bg-background"
                        >
                            <option
                                v-for="type in [
                                    'string',
                                    'number',
                                    'boolean',
                                    'object',
                                    'array',
                                ]"
                                :key="type"
                                :value="type"
                            >
                                {{ type }}
                            </option>
                        </select>
                        <button
                            class="bg-background px-2"
                            @click="addPort"
                        >
                            add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </modal-layout>
</template>

<script lang="ts" setup>
import { computed, type PropType, ref } from 'vue'
import { useRepo } from 'pinia-orm'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import ModalLayout from '@/components/modals/ModalLayout.vue'
import Method from '@/models/Method'
import Block from '@/models/Block'
import { useRouter } from 'vue-router'
import { method } from 'lodash'

const emit = defineEmits(['close', 'constantChanged'])
const router = useRouter()
const assetRepo = computed(() => useRepo(MarketplaceAsset))
const templateAssets = computed(() => assetRepo.value.all())
const newPortName = ref('')
const newPortType = ref('string')

const props = defineProps({
    method: {
        type: Object as PropType<Method>,
        required: false,
    },
    block: {
        type: Object as PropType<Block>,
        required: true,
    },
})

function addPort() {
    Method.addPort(
        props.method.id,
        newPortName.value,
        newPortType.value,
        props.block.name !== 'start'
    ).then((port) => {
        console.log(port)
        // graph.addCell(blocks.value.find((b) => b.id === block.id)?.buildingShape)
        if (props.block) {
            if (props.block.name === 'start')
                props.block.outPorts.push(port)
            else props.block.inPorts.push(port)
        }
    })
}
</script>

<style scoped></style>
