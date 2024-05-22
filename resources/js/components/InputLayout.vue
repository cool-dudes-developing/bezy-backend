<template>
    <div class="flex flex-col gap-1">
        <label
            v-if="label"
            :for="uid"
        >
            {{ label }}
        </label>
        <slot></slot>
        <p
            v-if="error"
            class=""
        >
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
import { provide } from 'vue'

withDefaults(
    defineProps<{
        label?: string
        error?: string
    }>(),
    {}
)

const uid = 'form-input-' + Math.random().toString(36)

provide('layoutId', uid)
</script>

<style scoped></style>
