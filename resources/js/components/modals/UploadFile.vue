<template>
    <div class="pointer-events-none flex items-center justify-center">
        <div
            class="pointer-events-auto z-10 flex w-full max-w-md flex-col gap-5 rounded-2xl bg-sec/50 p-4 shadow outline outline-1 outline-acc/50 backdrop-blur"
        >
            <header class="text-center text-xl">Upload File</header>
            <main class="w-full text-sm">
                <template v-if="files">
                    <ul class="flex flex-col gap-5">
                        <li
                            v-for="file in files"
                            :key="file.name"
                            class="flex items-center justify-between"
                        >
                            <span>{{ file.name }}</span>
                            <span>{{ file.size }} bytes</span>
                        </li>
                    </ul>
                </template>
                <template v-else>
                    <input
                        ref="fileInput"
                        hidden
                        type="file"
                        @change="files = $event.target.files"
                    />
                    <div
                        :class="{
                            'bg-sec': dragOver,
                        }"
                        class="flex cursor-pointer flex-col rounded-md border border-petronas p-5 text-center"
                        @dragenter="dragOver = true"
                        @dragleave="dragOver = false"
                        @dragover.prevent
                        @drop.prevent="drop"
                        @click="openFileExplorer"
                    >
                        <span class="pointer-events-none text-acc">
                            Choose a file
                        </span>
                        <span class="pointer-events-none">or</span>
                        <span class="pointer-events-none">drag it here.</span>
                    </div>
                </template>
            </main>

            <footer class="flex justify-center gap-5">
                <button
                    class="w-0 max-w-40 grow rounded-lg bg-acc py-1 text-dark"
                    @click="emit('upload', files)"
                >
                    Upload
                </button>
            </footer>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const emit = defineEmits(['upload'])

const dragOver = ref(false)
const files = ref<FileList | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

function drop(event: DragEvent) {
    event.preventDefault()
    dragOver.value = false
    files.value = event.dataTransfer?.files
}

function openFileExplorer() {
    fileInput.value?.click()
}
</script>

<style scoped></style>
