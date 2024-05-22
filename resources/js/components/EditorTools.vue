<template>
    <div
        class="absolute top-0 flex items-center divide-x divide-petronas border-b border-r border-petronas bg-dark/75 text-petronas backdrop-blur"
    >
        <button @click="emit('undo')">
            <svg-icon name="undo" />
        </button>
        <button @click="emit('redo')">
            <svg-icon name="redo" />
        </button>
        <button @click="emit('save')">
            <svg-icon name="save" />
        </button>
        <button @click="emit('publish')">
            <svg-icon name="upload" />
        </button>
        <button @click="emit('run')">
            <svg-icon name="play" />
        </button>
    </div>
</template>
<script lang="ts" setup>
import useModal from '@/plugins/modal'

const emit = defineEmits(['save', 'publish', 'undo', 'redo', 'run', 'debug'])
</script>
<style scoped>
button {
    @apply p-2;
}

button > * {
    @apply h-5;
}
</style>
