<template>
    <modal-layout :title="method?.type + ' Runner'">
        <div class="flex flex-col divide-y px-3">
            <div class="my-3">
                <h5 class="text-base">Public URL</h5>
                <template v-if="method?.type === 'method'">
                    <div class="flex rounded bg-background">
                        <p
                            class="w-full select-all overflow-auto whitespace-nowrap bg-transparent p-1"
                        >
                            /api/projects/{{ projectId }}/methods/{{
                                method.id
                            }}/execute
                        </p>
                        <button
                            class="px-1"
                            @click="
                                navigator?.clipboard.writeText(
                                    `/api/projects/${projectId}/methods/${method.id}/execute`
                                )
                            "
                        >
                            <svg-icon
                                class="h-6"
                                name="copy"
                            />
                        </button>
                    </div>
                    <small class="text-xs">
                        This URL can be used to execute this method from an
                        external source. You can also create your own API
                        endpoints
                        <router-link
                            :to="{
                                name: 'projectBackend',
                                params: { project: projectId },
                            }"
                            class="text-petronas underline"
                        >
                            here
                        </router-link>
                    </small>
                </template>
                <template v-else>
                    <small class="text-xs">
                        This is a public endpoint for your project. You can
                        customize here the request method and URL to execute
                        this method.
                    </small>

                    <div
                        v-if="method"
                        class="flex gap-3"
                    >
                        <select
                            v-model="method.http_method"
                            class="w-1/4 bg-background"
                        >
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="PATCH">PATCH</option>
                            <option value="DELETE">DELETE</option>
                        </select>
                        <div class="w-2/4 whitespace-nowrap bg-background">
                            <input
                                v-model="method.uri"
                                class="w-full bg-transparent"
                                placeholder="URL"
                            />
                        </div>
                        <button
                            class="w-1/4 bg-petronas font-bold text-dark"
                            @click="Method.update(projectId, method.id, method)"
                        >
                            Save
                        </button>
                    </div>
                    <small>
                        You can set url parameters that will be passed to start
                        block by using the following format:
                        <code class="bg-sec p-1">/path/:param</code>
                        . Request body will be automatically passed as
                        <code class="bg-sec p-1">Body</code>
                        parameter. You can also set query parameters by adding
                        them to your body block.
                    </small>
                    <small class="mt-3 flex flex-col">
                        <span>Your endpoint will be accessible at</span>
                        <code class="overflow-x-auto whitespace-nowrap bg-sec">
                            {{ method.http_method?.toUpperCase() }}
                            https://api.bezy.mutado.dev/a/p/{{ projectId }}/m/{{
                                method.uri
                            }}
                        </code>
                    </small>
                </template>
            </div>
            <div class="my-3 flex flex-col gap-3">
                <h2 class="text-xl">Request Data</h2>
                <small class="text-sm">
                    This data will be sent to the method as input parameters.
                </small>
                <div
                    v-for="param in method?.blocks
                        .find((b) => b.name === 'start')
                        ?.outPorts.filter((p) => p.type !== 'flow') ?? []"
                    class="grid grid-cols-[200px,1fr]"
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
                        v-if="['string', 'number'].includes(param.type)"
                        v-model="runner.params[param.name]"
                        :placeholder="param.type"
                        :type="param.type === 'string' ? 'text' : 'number'"
                        class="bg-background"
                    />
                    <textarea
                        v-else-if="['object', 'array'].includes(param.type)"
                        v-model="runner.params[param.name]"
                        :placeholder="'JSON ' + param.type"
                        class="bg-background"
                    />
                    <input
                        v-else-if="param.type === 'boolean'"
                        v-model="runner.params[param.name]"
                        class="bg-background"
                        type="checkbox"
                    />
                </div>
            </div>
            <div class="my-3">
                <h2 class="text-xl">Response</h2>
                <small class="text-sm">
                    This is the response data from the method execution.
                </small>
                <template v-if="loading">
                    <spinner-loader/>
                </template>
                <template v-else-if="runner.response.status">
                    <div
                        :class="{
                            'text-green-500': runner.response.status < 400,
                            'text-red-500': runner.response.status >= 400,
                        }"
                        class="text-sm"
                    >
                        Status: {{ runner.response?.status }}
                        {{ runner.response?.statusText }}
                    </div>
                    <h3 class="text-lg">Response data:</h3>
                    <template v-if="runner.response.status < 400">
                        <pre class="overflow-x-auto bg-dark">{{
                            runner.response?.data['result']
                        }}</pre>
                        <h3 class="text-lg">Call Stack:</h3>
                        <items-list
                            :delete-enabled="false"
                            :items="runner.response?.data['stack'] ?? []"
                            @select="
                                expandStack =
                                    expandStack === $event.id ? null : $event.id
                            "
                        >
                            <template #item="{ item }">
                                <div class="flex w-full flex-col">
                                    <div
                                        class="grid w-full grid-cols-2 items-end"
                                    >
                                        <span>{{ item.block }}</span>
                                        <small class="text-end">
                                            {{ formatDuration(item.duration) }}
                                        </small>
                                    </div>
                                    <div
                                        v-if="
                                            expandStack &&
                                            expandStack === item.id
                                        "
                                        class="grid grid-cols-2"
                                    >
                                        <div>
                                            <h4 class="text-sm">Input:</h4>
                                            <pre
                                                class="overflow-x-auto border-l-2 border-petronas pl-1"
                                                >{{ item.parameters }}</pre
                                            >
                                        </div>
                                        <div>
                                            <h4 class="text-sm">Output:</h4>
                                            <pre
                                                class="overflow-x-auto border-l-2 border-petronas pl-1"
                                                >{{ item.result }}</pre
                                            >
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </items-list>
                        <h3 class="text-lg">Cache:</h3>
                        <items-list
                            :delete-enabled="false"
                            :items="
                                Object.values(
                                    runner.response?.data['cache'] ?? {}
                                )
                            "
                        >
                            <template #item="{ item }">
                                <div class="grid w-full grid-cols-3">
                                    <span>
                                        {{ item.from }} => {{ item.to }}
                                    </span>
                                    <span class="text-center">
                                        {{ item.type }}
                                    </span>
                                    <small class="text-end">
                                        {{ item.value }}
                                    </small>
                                </div>
                            </template>
                        </items-list>
                    </template>
                    <pre
                        v-else
                        class="overflow-x-auto bg-dark"
                        >{{ runner.response?.data }}</pre
                    >
                </template>
                <p v-else>Send a request first to see the response.</p>
            </div>
        </div>
        <div class="grow"></div>
        <button
            :disabled="loading"
            class="sticky bottom-0 w-full bg-petronas py-3 font-bold text-dark disabled:brightness-75"
            @click="send"
        >
            Execute
        </button>
    </modal-layout>
</template>

<script lang="ts" setup>
import ModalLayout from '@/components/modals/ModalLayout.vue'
import { type PropType, ref } from 'vue'
import Method from '@/models/Method'
import SvgIcon from '@/components/SvgIcon.vue'
import * as api from '@/utils/api'
import ItemsList from '@/components/ItemsList.vue'
import { useLocalStorage } from '@vueuse/core'
import SpinnerLoader from '@/components/SpinnerLoader.vue'

const props = defineProps({
    projectId: {
        type: String,
        required: true,
    },
    method: {
        type: Object as PropType<Method>,
    },
})

const runner = useLocalStorage(
    'runner.' + props.method?.id ?? props.projectId,
    {
        params: {
            items: '["item1", "item2", "item3"]',
            Body: '{"name": "John Doe", "age": 30}',
        },
        response: {},
    }
)

// const runnerParams = ref<Record<string, string | number | boolean>>({
//     items: '["item1", "item2", "item3"]',
//     Body: '{"name": "John Doe", "age": 30}',
// })
// const runnerResponse = ref<{
//     status: number
//     statusText: string
//     data: any
// }>({})
const loading = ref(false)
const expandStack = ref(null)

function send() {
    loading.value = true
    api.post(
        `/projects/${props.projectId}/methods/${props.method?.id}/debug`,
        runner.value.params
    )
        .then((r) => (runner.value.response = r))
        .catch((e) => {
            console.error(e)
            runner.value.response = {
                status: e.response?.status ?? 500,
                statusText: e.response?.statusText ?? 'Internal Server Error',
                data: e.response?.data ?? {},
            }
        })
        .finally(() => (loading.value = false))
}

/**
 * Show the duration in a human-readable format.
 * us -> ms -> s -> m
 * @param ms
 */
function formatDuration(ms: number) {
    if (!ms) return '0'
    if (ms < 1) return `${(ms * 1000).toFixed(2)}μs`
    if (ms < 1000) return `${ms.toFixed(2)}ms`
    if (ms < 60000) return `${(ms / 1000).toFixed(2)}s`
    return `${(ms / 60000).toFixed(2)}m`
}
</script>

<style scoped></style>
