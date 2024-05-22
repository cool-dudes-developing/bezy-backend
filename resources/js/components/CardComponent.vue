<template>
  <router-link
    :to="link"
    class="flex flex-col justify-between p-2.5 gap-2.5 bg-transparent backdrop-brightness-75 border border-blue drop-shadow-sm hover:backdrop-brightness-50 rounded-md w-[250px] h-48 duration-300 ease-in-out"
  >
    <svg-icon class="h-8 w-8" :name="iconName"></svg-icon>
    <div class="flex flex-col items-start truncate">
      <p class="font-paragraph text-base text-white w-full truncate">{{ secondaryText }}</p>
      <p class="font-paragraph text-2xl text-white w-full truncate">{{ primaryText }}</p>
    </div>
  </router-link>
</template>

<script lang="ts" setup>
const props = defineProps({
  primaryText: {
    type: String,
    required: true
  },
  secondaryText: {
    type: String
  },
  link: {
    type: String,
    required: true
  },
  iconName: {
    type: String,
    required: true
  }
})
</script>
