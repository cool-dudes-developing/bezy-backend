<template>
  <header class="flex justify-between px-[20px] py-[10px] bg-[#1f1e1e] z-10">
    <figure
      class="flex h-full gap-3 items-center cursor-pointer select-none text-white"
      @click="router.push({ name: 'platform' })"
    >
      <svg-icon name="logo-light" class="h-10 w-10" />
      <h1 class="font-header text-[2rem] text-white">Bezy</h1>
    </figure>
    <div
      @click="dropdown = !dropdown"
      class="h-full relative bg-purplePizzaz aspect-square flex items-center justify-center rounded-full font-header text-3xl select-none"
    >
      {{ User.currentUser?.name.substring(0, 1).toUpperCase() }}
      <div v-if="dropdown" class="absolute top-full right-0 w-32 text-base bg-purple text-white">
        <p>Profile</p>
        <p>Settings</p>
        <p>Logout</p>
      </div>
    </div>
  </header>
</template>
<script setup lang="ts">
import User from '@/models/User'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const dropdown = ref(false)
</script>
