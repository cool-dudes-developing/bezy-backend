<template>
    <input
        v-if="edit"
        v-model="_modelValue"
        class="appearance-none bg-transparent"
        @blur="
            () => {
                edit = false
                emit('save', _modelValue)
                emit('update:modelValue', _modelValue)
            }
        "
    />
    <p
        v-else
        class="line-clamp-1 w-full"
        @dblclick="edit = true"
    >
        {{ _modelValue }}
    </p>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const emit = defineEmits(['update:modelValue', 'save'])

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
})

const _modelValue = ref(props.modelValue)

watch(
    () => props.modelValue,
    (value) => {
        _modelValue.value = value
    }
)

const throttle = useDebounceFn(() => {
    emit('save', _modelValue.value)
    emit('update:modelValue', _modelValue.value)
}, 1000)

watch(
    () => _modelValue.value,
    () => {
        throttle()
    }
)

const edit = ref(false)
</script>

<style scoped></style>
