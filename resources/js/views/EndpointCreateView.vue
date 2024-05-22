<template>
    <div
        class="flex h-full w-full flex-col items-center justify-center gap-2.5"
    >
        <spinner-loader />
    </div>
</template>

<script lang="ts" setup>
import Method from '@/models/Method'
import { PageSpinnerKey } from '@/symbols'
import { inject, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import SpinnerLoader from '@/components/SpinnerLoader.vue'

const route = useRoute()
const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

onMounted(() => {
    pageSpinner?.show()
    Method.store(route.params.project as string, {
        name: 'New Endpoint',
        title: 'New Endpoint',
        description: 'New endpoint description.',
        type: 'endpoint',
    })
        .then((res) => {
            console.log('res', res.id)
            router.push({
                name: 'endpoint',
                params: {
                    project: route.params.project,
                    endpoint: res.id,
                },
            })
        })
        .finally(() => pageSpinner?.hide())
})
</script>
