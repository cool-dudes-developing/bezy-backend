<template>
  <aside class="flex flex-col gap-[10px] px-[20px] py-[30px] bg-[#1f1e1e]">
    <button>
      <svg-icon name="plus-small-blue" class="h-4 w-4" />
      Add div
    </button>
  </aside>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { ref } from 'vue'

// const object = {
//   type: 'div',
//   style: {
//     width: '100',
//     height: '100',
//     'background-color': 'white'
//   }
// }

// const divs = ref([{}])

const router = useRouter()

// function addObject() {
//   if (object.type == 'div') {
//     divs.value.push(object)
//   }
// }
</script>
<style scoped>
aside > * {
  @apply gap-[5px] flex items-center font-sidebarButton font-medium;
}
.router-link-active {
  @apply text-purplePizzaz;
}
</style>
