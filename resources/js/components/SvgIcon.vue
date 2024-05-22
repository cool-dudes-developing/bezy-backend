<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
})

const icon = computed(() => {
    return defineAsyncComponent(
        () => import(`./../assets/svg/${props.name}.svg`)
    )
})
</script>

<template>
    <component
        :is="icon"
    />
</template>
