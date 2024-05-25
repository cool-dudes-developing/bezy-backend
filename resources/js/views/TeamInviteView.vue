<template>
    <div class="p-10">
        <v-card>
            <template #title>Invite to team</template>
            <template #subtitle>Invite new members to your team.</template>
            <input-component
                v-model="email"
                name="Email"
            />
            <div>
                <input
                    id="viewerRole"
                    v-model="role"
                    type="radio"
                    value="viewer"
                />
                <label for="viewerRole">Viewer</label>
            </div>
            <div>
                <input
                    id="editorRole"
                    v-model="role"
                    type="radio"
                    value="editor"
                />
                <label for="editorRole">Editor</label>
            </div>
            <v-button
                variant="primary"
                @click="invite"
            >
                Invite
            </v-button>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import VCard from '@/components/VCard.vue'
import { computed, ref } from 'vue'
import InputComponent from '@/components/InputComponent.vue'
import VButton from '@/components/VButton.vue'
import { useRoute, useRouter } from 'vue-router'
import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

const email = ref('')
const role = ref('viewer')
const route = useRoute()
const router = useRouter()
const projectId = computed(() => {
    return route.params.project as string
})
const project = computed(() => {
    return useRepo(Project).with('members').find(projectId.value)
})

function invite() {
    Project.invite(projectId.value, email.value, role.value).then(() => {
        router.push({
            name: 'projectTeam',
            params: { project: projectId.value },
        })
    })
}
</script>

<style scoped></style>
