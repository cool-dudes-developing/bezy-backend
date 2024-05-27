<template>
    <input
        v-if="edit"
        :value="modelValue"
        class="w-full bg-[#1f1e1e] text-inherit focus:outline-0"
        type="text"
        @blur="emit('blur')"
        @input="emit('update:modelValue', $event.target!.value)"
        @keydown.enter.prevent.exact="emit('nextRow')"
        @keydown.tab.prevent.exact="emit('nextCell')"
        @keydown.tab.shift.prevent="emit('prevCell')"
        @keydown.enter.shift.prevent="emit('prevRow')"
        @keydown.up.prevent="emit('prevRow')"
        @keydown.down.prevent="emit('nextRow')"
    />
    <template v-else>
        <select
            v-if="type === 'type'"
            :disabled="!isEditable"
            :value="modelValue"
            class="w-full bg-transparent"
            @change="emit('update:modelValue', $event.target!.value)"
        >
            <option value="uuid">UUID</option>
            <option value="timestamp">Timestamp</option>
            <option value="string">String</option>
            <option value="text">Text</option>
            <option value="integer">Integer</option>
            <option value="boolean">Boolean</option>
        </select>
        <select
            v-else-if="type === 'is_nullable'"
            :value="!!modelValue"
            class="w-full bg-transparent"
            @change="emit('update:modelValue', !!$event.target!.value)"
            :disabled="!isEditable"
        >
            <option disabled>NULL</option>
            <option value="true">Yes</option>
            <option value="false">No</option>
        </select>
        <span
            v-else
            :class="{
                'font-light opacity-25':
                    modelValue === undefined ||
                    modelValue === null ||
                    modelValue === '',
            }"
            class="select-none overflow-ellipsis whitespace-nowrap"
        >
            {{
                modelValue === null
                    ? 'NULL'
                    : modelValue === undefined || modelValue === ''
                        ? 'Empty'
                        : modelValue
            }}
        </span>
    </template>
</template>

<script lang="ts" setup>
const emit = defineEmits([
    'update:modelValue',
    'blur',
    'nextRow',
    'nextCell',
    'prevRow',
    'prevCell',
])
const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    modelValue: {
        type: [String, Number, Boolean],
        default: () => undefined,
    },
    isStructure: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'text',
    },
    isEditable: {
        type: Boolean,
        default: true,
    },
})
</script>

<style scoped></style>
