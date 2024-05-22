<template>
    <div class="overflow-y-auto bg-dark px-2 pt-5">
        <div class="flex items-center">
            <router-link
                :to="{
                    name: 'projectBackend',
                    params: { project },
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
                :to="{
                    name: isEndpoint ? 'endpointCreate' : 'methodCreate',
                    params: { project },
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
            :items="methods"
            :selected="methodId"
            pointer
            @delete="Method.destroy(project, $event.id)"
            @select="
                router.push({
                    name: isEndpoint ? 'endpoint' : 'method',
                    params: {
                        project,
                        [isEndpoint ? 'endpoint' : 'method']: $event.id,
                    },
                })
            "
        >
            <template #item="{ item }">
                <edit-text-input
                    v-model="item.title"
                    @save="
                        Method.update(project, item.id, {
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

const methods = computed(() => {
    return useRepo(Method)
        .query()
        .where('type', isEndpoint.value ? 'endpoint' : 'method')
        .where('project_id', project.value)
        .get()
})

const route = useRoute()
const router = useRouter()
const isEndpoint = computed(() => route.name?.toString().includes('endpoint'))
const project = computed(() => route.params.project as string)
const methodId = computed(
    () =>
        (isEndpoint.value
            ? route.params.endpoint
            : route.params.method) as string
)

onMounted(() => {
    if (!methods.value.length) {
        // Method.fetchAll(project.value)
    }
})
</script>

<style scoped></style>
