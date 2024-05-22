<template>
    <div class="flex h-full w-full flex-col gap-[1.875rem]">
        <header class="font-header text-[2rem] font-bold text-petronas">
            Sign in
        </header>
        <form
            id="form"
            class="flex w-[100%] flex-col gap-2"
            @submit.prevent="login"
        >
            <input-layout label="Email" :error="validation?.email?.[0]">
                <v-input
                    v-model="email"
                    placeholder="What's your email?"
                    type="email"
                ></v-input>
            </input-layout>
            <input-layout label="Password" :error="validation?.password?.[0]">
                <v-input
                    v-model="password"
                    placeholder="Your password"
                    type="password"
                ></v-input>
            </input-layout>
            <v-button
                to="/auth/reset"
                type="button"
                variant="underline"
            >
                Forgot password?
            </v-button>
        </form>
        <footer class="flex w-[100%] flex-col gap-2">
            <v-button
                type="submit"
                variant="primary"
                @click="login"
            >
                Continue
            </v-button>
            <v-button
                to="/auth/register"
                variant="secondary"
            >
                New to Bezy?
            </v-button>
        </footer>
    </div>
</template>

<script lang="ts" setup>
import User from '@/models/User'
import { SpinnerKey } from '@/symbols'
import { inject, ref } from 'vue'
import { useRouter } from 'vue-router'
import VButton from '@/components/VButton.vue'
import InputLayout from '@/components/InputLayout.vue'
import VInput from '@/components/VInput.vue'

const email = ref('')
const password = ref('')
const spinner = inject(SpinnerKey)
const validation = ref({})

const router = useRouter()

function login() {
    spinner?.show()
    User.login(email.value, password.value)
        .then(() => {
            router.push({ name: 'platform' })
        })
        .catch((error) => {
            if (error.response?.status === 422) {
                validation.value = error.response.data.errors
            }
        })
        .finally(() => {
            setTimeout(() => {
                spinner?.hide()
            }, 300)
        })
}
</script>
