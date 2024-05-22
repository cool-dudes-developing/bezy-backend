<template>
    <div class="relative h-full w-full">
        <div
            v-if="
                method &&
                method.blocks.length > 0 &&
                !pageSpinner?.visible.value
            "
            class="h-full w-full"
        >
            <!-- <method-editor-component :blocks="method.blocks" /> -->
            <method-editor :method-id="method.id" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import MethodEditor from '@/components/MethodEditor.vue'
import { useRepo } from 'pinia-orm'
import { computed, inject, watch } from 'vue'
import Method from '@/models/Method'
import { useRoute } from 'vue-router'
import { PageSpinnerKey } from '@/symbols'
import Block from '@/models/Block'

const route = computed(() => useRoute())
const pageSpinner = inject(PageSpinnerKey)

const method = computed(() =>
    useRepo(Method)
        .with('blocks', (q) => q.with('outPorts').with('inPorts'))
        .find(route.value.params.method as string)
)
watch(
    () => route.value.params.method,
    () => {
        pageSpinner?.show()
        Promise.all([
            Method.fetch(
                route.value.params.project as string,
                route.value.params.method as string
            ),
            Block.fetchAllTemplates(),
        ]).finally(() => pageSpinner?.hide())
    },
    { immediate: true }
)
</script>
