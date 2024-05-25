<template>
    <div class="flex items-center justify-center">
        <v-card
            v-if="project"
            disable-create
        >
            <template #title>
                You have been invited to join {{ project.name }}
            </template>
            <template #subtitle>
                You can accept or decline the invitation to join the project.
            </template>
            <div class="grid max-w-sm grid-cols-2 gap-3">
                <v-button
                    variant="secondary"
                    @click="declineInvite"
                >
                    Decline
                </v-button>
                <v-button
                    variant="primary"
                    @click="acceptInvite"
                >
                    Accept
                </v-button>
            </div>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import VCard from '@/components/VCard.vue'
import { useRoute, useRouter } from 'vue-router'
import { computed, onMounted } from 'vue'
import Project from '@/models/Project'
import { useRepo } from 'pinia-orm'
import VButton from '@/components/VButton.vue'

const route = useRoute()
const router = useRouter()
const projectId = computed(() => {
    return route.params.project as string
})

const project = computed(() => {
    return useRepo(Project).with('members').find(projectId.value)
})

function acceptInvite() {
    Project.acceptInvite(projectId.value).then(() => {
        router.push({ name: 'project', params: { project: projectId.value } })
    })
}

function declineInvite() {
    Project.declineInvite(projectId.value).then(() => {
        router.push({ name: 'sharedProjects' })
    })
}

onMounted(()=>{
    if (!project.value) {
        Project.fetch(projectId.value).then(() => {
            if (project.value?.is_accepted) {
                router.push({ name: 'project', params: { project: projectId.value } })
            }
        })
    }
})
</script>

<style scoped></style>
