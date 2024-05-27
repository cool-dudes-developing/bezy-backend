<template>
    <div class="overflow-y-auto bg-dark px-2 pt-5">
        <div class="flex items-center">
            <router-link
                :to="{
                    name: 'projectBackend',
                    params: { project: projectId },
                }"
            >
                <svg-icon
                    class="h-6"
                    name="chevron-left"
                />
            </router-link>
            <h3
                class="line-clamp-1 font-header text-2xl font-bold text-[#f0f0f0]"
            >
                {{ isEndpoint ? 'Endpoints' : 'Methods' }}
            </h3>
            <router-link
                v-if="project?.role !== 'viewer'"
                :to="{
                    name: isEndpoint ? 'endpointCreate' : 'methodCreate',
                    params: { project: projectId },
                }"
                class="ml-auto"
            >
                <svg-icon
                    class="h-4"
                    name="plus"
                />
            </router-link>
        </div>
        <items-list
            :delete-enabled="project?.role !== 'viewer'"
            :items="methods"
            :selected="methodId"
            pointer
            @delete="Method.destroy(projectId, $event.id)"
            @select="
                router.push({
                    name: isEndpoint ? 'endpoint' : 'method',
                    params: {
                        project: projectId,
                        [isEndpoint ? 'endpoint' : 'method']: $event.id,
                    },
                })
            "
        >
            <template #item="{ item }">
                <edit-text-input
                    v-model="item.title"
                    @save="
                        Method.update(projectId, item.id, {
                            title: $event,
                        })
                    "
                />
            </template>
        </items-list>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useRepo } from 'pinia-orm'
import { useRoute, useRouter } from 'vue-router'
import SvgIcon from '@/components/SvgIcon.vue'
import Method from '@/models/Method'
import EditTextInput from '@/components/EditTextInput.vue'
import ItemsList from '@/components/ItemsList.vue'
import Project from '@/models/Project'

const route = useRoute()
const router = useRouter()
const isEndpoint = computed(() => route.name?.toString().includes('endpoint'))
const projectId = computed(() => route.params.project as string)
const project = computed(() => useRepo(Project).find(projectId.value))
const methodId = computed(
    () =>
        (isEndpoint.value
            ? route.params.endpoint
            : route.params.method) as string
)
const methods = computed(() => {
    return useRepo(Method)
        .query()
        .where('type', isEndpoint.value ? 'endpoint' : 'method')
        .where('project_id', projectId.value)
        .get()
})

onMounted(() => {
    if (!methods.value.length) {
        // Method.fetchAll(project.value)
    }
})
</script>

<style scoped></style>
