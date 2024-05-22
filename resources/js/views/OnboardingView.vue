<template>
    <div class="flex items-center justify-center">
        <div
            class="flex w-full max-w-sm flex-col items-center justify-center gap-8"
        >
            <div>
                <h1 class="text-2xl">
                    {{ steps[currentStep].title }}
                </h1>
                <p class="text-sm">
                    {{ steps[currentStep].description }}
                </p>
            </div>
            <v-button
                v-if="steps[currentStep].type === 'next'"
                class="w-full"
                variant="primary"
                @click="next"
            >
                Get started
            </v-button>
            <template v-else-if="steps[currentStep].type === 'create-project'">
                <input-layout
                    class="w-full"
                    label="Project name"
                >
                    <v-input
                        v-model="projectName"
                        placeholder="What's your project name?"
                    ></v-input>
                </input-layout>
                <v-button
                    :disabled="!projectName.length"
                    class="w-full"
                    variant="primary"
                    @click="createProject"
                >
                    Create project
                </v-button>
            </template>
            <template v-else-if="steps[currentStep].type === 'invite-team'">
                <input-layout
                    class="w-full"
                    label="Team email"
                >
                    <div class="flex w-full gap-1">
                        <v-input
                            v-model="teamEmail"
                            class="grow"
                            placeholder="What's your team email?"
                            type="email"
                        ></v-input>
                        <v-button
                            :disabled="!teamEmail.length"
                            variant="secondary"
                            @click="
                                () => {
                                    inviteEmails.push(teamEmail)
                                    teamEmail = ''
                                }
                            "
                        >
                            Add
                        </v-button>
                    </div>
                </input-layout>

                <ul class="w-full">
                    <li
                        v-for="(email, index) in inviteEmails"
                        :key="index"
                        class="flex justify-between"
                    >
                        {{ email }}
                        <button
                            class="text-xs text-red-400"
                            @click="inviteEmails.splice(index, 1)"
                        >
                            Remove
                        </button>
                    </li>
                </ul>

                <div class="flex w-full flex-col gap-1">
                    <v-button
                        :disabled="!inviteEmails.length"
                        class="w-full"
                        variant="primary"
                        @click="next"
                    >
                        Invite
                    </v-button>
                    <v-button
                        class="w-full"
                        variant="secondary"
                        @click="next"
                    >
                        Skip
                    </v-button>
                </div>
            </template>

            <template
                v-else-if="steps[currentStep].type === 'explore-marketplace'"
            >
                <v-button
                    :to="{ name: 'marketplace' }"
                    class="w-full"
                    variant="primary"
                >
                    Explore
                </v-button>
            </template>
        </div>
    </div>
</template>

<script lang="ts" setup>
import VButton from '@/components/VButton.vue'
import { ref } from 'vue'
import InputLayout from '@/components/InputLayout.vue'
import VInput from '@/components/VInput.vue'
import Project from '@/models/Project'
import { useLocalStorage } from '@vueuse/core'

const steps = [
    {
        title: 'Welcome to Bezy!',
        description: 'Intuitive no-code platform to build your next project.',
        type: 'next',
    },
    {
        title: 'Create your first project',
        description: 'Start by creating your first project.',
        type: 'create-project',
    },
    {
        title: 'Invite your team',
        description: 'Invite your team to collaborate on your project.',
        type: 'invite-team',
    },
    {
        title: 'Explore Bezy',
        description:
            'Start exploring Bezy Marketplace and find the best tools for your project.',
        type: 'explore-marketplace',
    },
]
const currentStep = useLocalStorage('onboarding-step', 0)

const projectName = ref('')
const teamEmail = ref('')
const inviteEmails = ref([])

function next() {
    currentStep.value++
}

function createProject() {
    Project.store({
        name: projectName.value,
    }).then(() => {
        next()
    })
}
</script>

<style scoped></style>
