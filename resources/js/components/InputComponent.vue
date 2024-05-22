<template>
  <div class="w-[100%] flex flex-col gap-[0.3125rem]">
    <label :for="name" class="h-[1.375rem] font-label flex items-center text-white">{{
      name
    }}</label>
    <input
      :id="name"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event?.target?.value)"
      :placeholder="placeholder"
      class="p-[0.625rem] w-[100%] h-[2.5rem] bg-transparent backdrop-brightness-75 drop-shadow-sm border border-blue rounded-[0.9375rem] text-white font-input placeholder:text-white active:backdrop-brightness-50 focus:backdrop-brightness-50 transition"
    />
    <p v-if="isError" class="h-[0.9375rem] font-error text-[0.875rem] text-blue flex items-center">
      {{ errorMessage }}
    </p>
  </div>
</template>

<script lang="ts" setup>
const props = defineProps({
  type: {
    type: String,
    default: 'text'
  },
  name: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    default: ''
  },
  modelValue: {
    type: String,
    required: false
  },
  errorMessage: {
    type: String,
    default: ''
  },
  isError: {
    type: Boolean,
    default: false
  }
})
</script>
