<template>
    <input
        :id="injectedLayoutId ?? undefined"
        :placeholder="placeholder"
        :type="type"
        :value="modelValue"
        class="rounded-2xl border border-petronas bg-transparent px-3 py-2 font-input text-light drop-shadow-sm backdrop-brightness-75 transition placeholder:text-white focus:backdrop-brightness-50 active:backdrop-brightness-50"
        @input="$emit('update:modelValue', $event?.target?.value)"
    />
</template>

<script lang="ts" setup>
import { inject } from 'vue'

withDefaults(
    defineProps<{
        type?: 'text' | 'password' | 'email'
        placeholder?: string
        modelValue?: string
    }>(),
    {
        type: 'text',
    }
)

const injectedLayoutId = inject('layoutId') as string
</script>
