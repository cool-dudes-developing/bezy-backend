<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
})

const url = computed(() => {
    return `./../assets/svg/${props.name}.svg`
})

const icon = computed(() => {
    // just some reactivity to trigger the import
    const u = url.value
    return defineAsyncComponent(
        () => import(u)
    )
})
</script>

<template>
    <component
        :is="icon"
    />
</template>
