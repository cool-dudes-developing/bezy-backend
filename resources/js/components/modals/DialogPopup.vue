<template>
    <div class="pointer-events-none flex items-center justify-center">
        <div
            class="pointer-events-auto z-10 flex w-full max-w-md flex-col gap-5 rounded-2xl bg-sec/50 p-4 shadow outline outline-1 outline-acc/50 backdrop-blur"
        >
            <header class="text-center text-xl">
                {{ title }}
            </header>
            <main class="text-sm">
                {{ message }}
            </main>
            <footer
                :class="{
                    'flex-row-reverse': variant === 'danger',
                }"
                class="flex justify-center gap-5"
            >
                <button
                    v-if="secondary"
                    :class="{
                        'bg-sec text-white': variant === 'success',
                        'bg-acc text-dark': variant === 'danger',
                    }"
                    class="w-0 max-w-40 grow rounded-lg py-1"
                    @click="$emit('secondary')"
                >
                    {{ secondary }}
                </button>
                <button
                    v-if="primary"
                    :class="{
                        'bg-acc text-dark': variant === 'success',
                        'bg-red-700 text-white': variant === 'danger',
                    }"
                    class="w-0 max-w-40 grow rounded-lg py-1"
                    @click="$emit('primary')"
                >
                    {{ primary }}
                </button>
            </footer>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { PropType } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    primary: {
        type: String,
        default: 'OK',
    },
    secondary: {
        type: String,
        default: 'Cancel',
    },
    variant: {
        type: String as PropType<'success' | 'danger' | 'warning'>,
        default: 'success',
    },
})
</script>

<style scoped></style>
