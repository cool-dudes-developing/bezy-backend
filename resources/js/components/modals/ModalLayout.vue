<template>
    <div
        class="relative mx-auto flex h-full w-full max-w-3xl flex-col overflow-y-auto overflow-x-hidden rounded-t-lg bg-dark/75 text-light backdrop-blur-xl"
    >
        <header class="flex justify-between bg-sec px-1 py-2">
            <h1 class="text-xl capitalize">{{ title }}</h1>
            <button @click="emit('close')">
                <svg-icon
                    class="h-6 w-6 text-white"
                    name="close"
                />
            </button>
        </header>
        <div class="grow flex flex-col relative">
            <slot />
        </div>
    </div>
</template>

<script lang="ts" setup>
import SvgIcon from '@/components/SvgIcon.vue'

const emit = defineEmits(['close'])

defineProps({
    title: {
        type: String,
        required: true,
    },
})
</script>

<style scoped></style>
