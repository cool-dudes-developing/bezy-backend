<template>
  <button
    @click="toggleDropdown()"
    class="gap-[5px] flex items-center font-sidebarButton font-medium"
  >
    <svg-icon :name="iconName" class="h-4 w-4" />
    {{ innerText }}
  </button>
  <div
    :class="[
      'flex',
      'flex-col',
      'gap-[5px]',
      'bg-[#1f1e1]',
      'dropdown',
      'items-start',
      'justify-start',
      { active: dropdownOpen }
    ]"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  iconName: {
    type: String,
    required: true
  },
  innerText: {
    type: String,
    required: true
  }
})

const dropdownOpen = ref(false)

function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value
}
</script>

<style>
.dropdown > * {
  @apply gap-[5px] flex items-center font-sidebarButton font-medium;
}

.dropdown {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.6s ease-out;
}
.dropdown.active {
  max-height: 100px;
}
</style>
