<template>
    <div class="p-10">
        <v-card
            @create="
                router.push({
                    name: 'createProject',
                })
            "
        >
            <template #title>Projects shared with me</template>
            <template #subtitle>
                These are the projects that other users have shared with you.
            </template>
            <items-list
                :items="projects"
                @delete="Project.destroy($event.id)"
                @select="
                    (item) =>
                        router.push({
                            name: 'project',
                            params: { project: item.id },
                        })
                "
            >
                <template #item="{ item }">
                    {{ item.name }}
                </template>
                <template #empty>
                    <p class="max-w-prose">
                        You have not been added to any projects. Ask the project
                        owner to add you to their project.
                    </p>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import Project from '@/models/Project'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import { useRouter } from 'vue-router'
import { computed, onMounted } from 'vue'
import { useRepo } from 'pinia-orm'

const router = useRouter()

const projects = computed(() =>
    useRepo(Project)
        .withAll()
        .where((project) => project.role !== 'owner')
        .get()
)

onMounted(() => {
    Project.fetchAll()
})
</script>

<style scoped></style>
