<template>
    <div class="p-10">
        <v-card
            @create="
                router.push({
                    name: 'createProject',
                })
            "
        >
            <template #title>Archived Projects</template>
            <template #subtitle>
                Your deleted projects are stored here. You can restore them or
                permanently delete them.
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
                <template #additional-actions="{ item }">
                    <button
                        class="mr-2"
                        @click.stop.prevent="
                            () => {
                                post(`/projects/${item.id}/restore`)
                                useRepo(Project).save({
                                    id: item.id,
                                    deleted_at: null,
                                })
                            }
                        "
                    >
                        <svg-icon
                            class="h-4 w-4"
                            name="restore"
                        />
                    </button>
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
import { computed, inject, onMounted } from 'vue'
import { useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import { get, post } from '@/utils/api'
import SvgIcon from '@/components/SvgIcon.vue'

const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

pageSpinner?.show()
Project.fetchAll().then(() => pageSpinner?.hide())

const projects = computed(() =>
    useRepo(Project)
        .where((p) => p.role === 'owner')
        .where((p) => !!p.deleted_at)
        .get()
)

function destroy(id: string) {
    pageSpinner?.show()
    Project.destroy(id).finally(() => pageSpinner?.hide())
}

onMounted(async () => {
    useRepo(Project).save(
        await get('/projects/archived').then((res) => res.data.data)
    )
})
</script>

<style scoped></style>
