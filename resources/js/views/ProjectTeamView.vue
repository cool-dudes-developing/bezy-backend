<template>
    <div class="p-10">
        <v-card v-if="project" @create="router.push({
            name: 'projectTeamInvite',
            params: { project: project.id }
        })">
            <template #title>Team</template>
            <template #subtitle>Manage your team.</template>
            <items-list
                :delete-enabled="false"
                :items="project?.members"
                @delete="removeMember"
            >
                <template #item="{ item }">
                    <div class="flex w-full items-center gap-1">
                        {{ item.name }}
                        <small class="text-sm text-light/75">
                            {{ item.role }}
                        </small>
                        <span
                            v-if="!item.accepted_at"
                            class="rounded-full bg-acc px-1 text-xs text-black"
                        >
                            pending invite
                        </span>
                    </div>
                </template>
                <template #empty>
                    <p class="max-w-prose">
                        You have not added any team members to this project. Add
                        new team members to collaborate on your project.
                    </p>
                </template>
                <template #actions="{ item, onDelete }">
                    <div
                        v-if="item.id !== User.currentUser?.id && project.role === 'owner'"
                        class="flex items-center gap-1"
                    >
                        <button
                            class="shrink-0"
                            @click.prevent="changeRole(item)"
                        >
                            <svg-icon
                                :name="
                                    item.role === 'admin'
                                        ? 'shield'
                                        : item.role === 'viewer'
                                          ? 'eye'
                                          : 'pencil-ruler'
                                "
                                class="h-4 w-4"
                            />
                        </button>
                        <button
                            class="shrink-0"
                            @click.prevent="onDelete"
                        >
                            <svg-icon
                                class="h-4 w-4"
                                name="trashcan"
                            />
                        </button>
                    </div>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import VCard from '@/components/VCard.vue'
import { useRepo } from 'pinia-orm'
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Project from '@/models/Project'
import ItemsList from '@/components/ItemsList.vue'
import User from '@/models/User'

const route = useRoute()
const router = useRouter()
const projectId = computed(() => route.params.project as string)

const project = computed(() => {
    return useRepo(Project).with('members').find(projectId.value)
})

function changeRole(member: User) {
    Project.changeRole(
        projectId.value,
        member.id,
        member.role === 'viewer' ? 'editor' : 'viewer'
    )
}

function removeMember(member: User) {
    Project.removeMember(projectId.value, member.id)
}
</script>

<style scoped></style>
