<template>
    <div class="flex flex-col gap-2 rounded-2xl bg-sec p-3 text-light">
        <header>
            <div class="flex items-start justify-between">
                <div class="flex gap-2">
                    <h1
                        v-if="$slots.title"
                        class="leading-none text-acc"
                    >
                        <slot name="title" />
                    </h1>
                    <button
                        class="flex items-center justify-center rounded-full border"
                    >
                        <svg-icon
                            class="h-3.5 w-3.5"
                            name="question"
                        />
                    </button>
                </div>
                <slot name="actions">
                    <button
                        class="flex items-center gap-2 p-1 text-xs"
                        @click="emit('create')"
                    >
                        <svg-icon
                            class="h-3 w-3"
                            name="plus"
                        />
                    </button>
                </slot>
            </div>
            <small
                v-if="$slots.subtitle"
                class="leading-none"
            >
                <slot name="subtitle" />
            </small>
        </header>
        <slot />
    </div>
</template>

<script lang="ts" setup>
import SvgIcon from '@/components/SvgIcon.vue'

const emit = defineEmits(['create'])
</script>

<style scoped></style>
