<template>
    <div class="overflow-y-auto bg-dark px-2 pt-5">
        <div class="flex items-center">
            <router-link
                :to="{
                    name: 'tables',
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
                Tables
            </h3>
        </div>
        <!--        <ul class="overflow-hidden rounded divide-y divide-acc">-->
        <!--            <router-link-->
        <!--                v-for="table in tables"-->
        <!--                v-slot="{ href, navigate }"-->
        <!--                :to="{ name: 'table', params: { project, table: table.id } }"-->
        <!--                custom-->
        <!--            >-->
        <!--                <li-->
        <!--                    :class="{-->
        <!--                        'bg-sec': table.id === tableId,-->
        <!--                    }"-->
        <!--                    class="cursor-pointer px-2"-->
        <!--                    @click="navigate"-->
        <!--                >-->
        <!--                    <edit-text-input-->
        <!--                        v-model="table.name"-->
        <!--                        @save="-->
        <!--                            DatabaseTable.update(project, table.id, {-->
        <!--                                name: $event,-->
        <!--                            })-->
        <!--                        "-->
        <!--                    />-->
        <!--                </li>-->
        <!--            </router-link>-->
        <!--        </ul>-->
        <items-list
            :items="tables"
            :selected="tableId"
            :delete-enabled="isEditable"
            pointer
            @delete="DatabaseTable.destroy(projectId, $event.id)"
            @select="
                router.push({
                    name: 'table',
                    params: { project: projectId, table: $event.id },
                })
            "
        >
            <template #item="{ item }">
                <edit-text-input
                    v-model="item.name"
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
import DatabaseTable from '@/models/DatabaseTable'
import { useRoute, useRouter } from 'vue-router'
import SvgIcon from '@/components/SvgIcon.vue'
import Method from '@/models/Method'
import EditTextInput from '@/components/EditTextInput.vue'
import ItemsList from '@/components/ItemsList.vue'
import Project from '@/models/Project'

const tables = computed(() => {
    return useRepo(DatabaseTable).all()
})

const route = useRoute()
const router = useRouter()
const projectId = computed(() => route.params.project as string)
const tableId = computed(() => route.params.table as string)
const project = computed(() => useRepo(Project).find(projectId.value))
const isEditable = computed(() => project.value?.role !== 'viewer')

onMounted(() => {
    DatabaseTable.fetchAll(projectId.value)
})
</script>

<style scoped></style>
