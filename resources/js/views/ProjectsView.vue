<template>
    <div class="p-10">
        <v-card
            @create="
                router.push({
                    name: 'createProject',
                })
            "
        >
            <template #title>Projects</template>
            <template #subtitle>
                Your projects are the main entities in the platform. They
                contain all the methods and endpoints that you create.
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
                    <p>
                        You don't have any projects yet. Create one by clicking
                        the button above.
                    </p>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { computed, inject } from 'vue'
import { useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'

const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

pageSpinner?.show()
Project.fetchAll().then(() => pageSpinner?.hide())

const projects = computed(() =>
    useRepo(Project)
        .where((p) => p.role === 'owner')
        .where((p) => !p.deleted_at)
        .get()
)

function destroy(id: string) {
    pageSpinner?.show()
    Project.destroy(id).finally(() => pageSpinner?.hide())
}
</script>

<style scoped>
th,
td {
    text-align: left;
    padding: 10px 12px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border: 1px solid #69e5f8;
}
</style>
