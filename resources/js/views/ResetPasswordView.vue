<template>
    <auth-view>
        <div class="flex flex-col gap-[1.875rem] w-full">
            <header class="font-header text-[2rem] text-blue">Change Your Password</header>
            <form @submit.prevent.stop="sendRequest" id="form" class="flex flex-col gap-2 w-[100%]">
                <input-component
                    v-model="email"
                    type="email"
                    name="Email"
                    placeholder="What's your email?"
                ></input-component>
                <input-component
                    v-model="password"
                    type="password"
                    name="Password"
                    placeholder="Enter your new password"
                ></input-component>
                <input-component
                    v-model="passwordConfirmation"
                    type="password"
                    name="Password"
                    placeholder="Repeat your new password"
                ></input-component>
            </form>
            <footer class="flex flex-col gap-2 w-[100%]">
                <button
                    type="submit"
                    form="form"
                    class="flex justify-center items-center h-[2.5rem] button"
                >
                    Save
                </button>
                <router-link
                    to="/auth/login"
                    class="flex justify-center items-center h-[2.5rem] button-secondary"
                >
                    I remember my password
                </router-link>
            </footer>
        </div>
    </auth-view>
</template>

<script setup lang="ts">

import AuthView from '@/views/AuthView.vue'
import InputComponent from '@/components/InputComponent.vue'
import { ref } from 'vue'
import { post } from '@/utils/api'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')

function sendRequest() {
    post('/auth/reset', {
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
        token: route.params.token as string,
    }).then(() => {
        router.push('/auth/login')
    })
}

</script>

<style scoped>

</style>
