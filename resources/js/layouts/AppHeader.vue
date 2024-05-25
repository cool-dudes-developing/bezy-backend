<template>
    <header class="z-10 flex justify-between bg-[#1f1e1e] px-[20px] py-[10px]">
        <figure
            class="flex h-full cursor-pointer select-none items-center gap-3 text-white"
            @click="router.push({ name: 'platform' })"
        >
            <svg-icon
                class="h-10 w-10"
                name="logo-light"
            />
            <h1 class="font-header text-[2rem] text-white">Bezy</h1>
        </figure>
        <div
            class="relative flex aspect-square h-full select-none items-center justify-center rounded-full bg-purplePizzaz font-header text-3xl"
            @click="dropdown = !dropdown"
        >
            {{ User.currentUser?.name.substring(0, 1).toUpperCase() }}
            <div
                v-if="dropdown"
                class="absolute right-0 top-full w-32 bg-purple text-base text-white rounded-lg"
            >
                <router-link
                    :to="{ name: 'profile' }"
                    class="block"
                >
                    Profile
                </router-link>
                <button @click="User.logout()">Logout</button>
            </div>
        </div>
    </header>
</template>
<script lang="ts" setup>
import User from '@/models/User'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const dropdown = ref(false)
</script>
