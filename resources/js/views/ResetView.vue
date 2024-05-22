<template>
  <div class="flex flex-col gap-[1.875rem] w-full">
    <header class="font-header text-[2rem] text-blue">Reset password</header>
    <form id="form" class="flex flex-col gap-2 w-[100%]">
      <input-component
        v-model="email"
        type="text"
        name="Email"
        placeholder="What's your email?"
      ></input-component>
    </form>
    <footer class="flex flex-col gap-2 w-[100%]">
      <button
        type="submit"
        form="form"
        class="flex justify-center items-center h-[2.5rem] button"
      >
        Reset
      </button>
      <router-link
        to="/auth/login"
        class="flex justify-center items-center h-[2.5rem] button-secondary"
      >
        I remember my password
      </router-link>
    </footer>
  </div>
</template>

<script lang="ts" setup>
import InputComponent from '@/components/InputComponent.vue'
import { ref } from 'vue'

const email = ref('')
</script>
