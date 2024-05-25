<template>
    <div v-if="project">
        <div class="flex flex-col gap-20 p-10 lg:grid-cols-2 lg:gap-10 xl:grid">
            <v-card @create="router.push({ name: 'endpointCreate' })">
                <template #title>Endpoints</template>
                <template #subtitle>Your APIs public interface.</template>
                <items-list :items="project.endpoints">
                    <template #item="{ item }">
                        <router-link
                            :to="{
                                name: 'endpoint',
                                params: { endpoint: item.id },
                            }"
                            class="w-full"
                        >
                            {{ item.title }}
                            <small class="text-xs text-light/75">
                                URI: {{ item.uri ?? 'unset' }}
                            </small>
                        </router-link>
                    </template>
                    <template #empty>
                        <p class="max-w-prose">
                            You have not created any endpoints for this project.
                            Create new endpoint to connect your project to the
                            world.
                        </p>
                    </template>
                </items-list>
            </v-card>
            <v-card @create="router.push({ name: 'methodCreate' })">
                <template #title>Methods</template>
                <template #subtitle>Building blocks of your API.</template>
                <items-list
                    :items="project.methods.filter((e) => e.type === 'method')"
                    @delete="Method.destroy(project.id, $event.id)"
                >
                    <template #item="{ item }">
                        <router-link
                            :to="{
                                name: 'method',
                                params: { method: item.id },
                            }"
                            class="w-full"
                        >
                            {{ item.title }}
                            <small class="text-xs text-light/75">
                                In: {{ item.in }} Out: {{ item.out }}
                            </small>
                        </router-link>
                    </template>
                    <template #empty>
                        <p class="max-w-prose">
                            You have not created any methods for this project.
                            Create new methods to define the logic of your API.
                        </p>
                    </template>
                </items-list>
            </v-card>
        </div>
    </div>
    <div v-else>Error</div>
</template>

<script lang="ts" setup>
import { computed, inject, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import Method from '@/models/Method'

const route = computed(() => useRoute())
const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

onMounted(() => {
    console.log(project.value?.name)
    console.log(project.value?.user_id)
})

const project = computed(() =>
    useRepo(Project)
        .withAll()
        .find(route.value.params.project as string)
)

pageSpinner?.show()

Project.fetch(route.value.params.project as string)
    .catch(() => router.push({ name: '404' }))
    .finally(() => pageSpinner?.hide())
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
