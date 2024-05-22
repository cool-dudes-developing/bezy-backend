<template>
    <div 
        v-if="inputType != 'select'" 
        class="w-[100px] h-8 px-1 gap-3 items-center flex flex-row hover:border hover:rounded"
    >
        <p>{{ props.label }}</p>
        <input 
            class="bg-transparent focus:outline-none overflow-scroll"
            :type="inputType"
            :value="modelValue"
            @change ="$emit('update:modelValue', $event?.target?.value)"
            :disabled="isDisabled"
        />
    </div>
    <div
        v-else
        class="w-[100px] h-8 px-1 gap-3 items-center flex flex-row hover:border hover:rounded"
    >
        <p>{{ props.label }}</p>
        <select
            class="bg-transparent overflow-hidden"
            :value="modelValue"
            @change="$emit('update:modelValue', $event?.target?.value)"
        >
            <option
                v-for="option in selectOptions"
                class="bg-transparent text-black"
                :value="option.value"
            >
            {{ option.text }}
            </option>
        </select>
    </div>
</template>

<script setup lang="ts">
const props = defineProps({
    label: {
        type: String,
        required: true
    },
    // property: {
    //     type: String,
    //     required: true
    // },
    modelValue: {
        type: String,
        required: true
    },
    inputType: {
        type: String,
        default: 'text'
    },
    selectOptions: {
        type: Array,
        required: false
    },
    isDisabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])
</script>