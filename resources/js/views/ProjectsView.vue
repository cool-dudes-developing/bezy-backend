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
            >
                <template #item="{ item }">
                    <router-link
                        :to="{ name: 'project', params: { project: item.id } }"
                        class="flex justify-between px-2 py-2 hover:bg-slate-800"
                    >
                        {{ item.name }}
                    </router-link>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { computed, inject, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import VButton from '@/components/VButton.vue'
import SvgIcon from '@/components/SvgIcon.vue'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'

const route = computed(() => useRoute())
const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

const EmptyStateClass = ref(
    'flex flex-col justify-center items-center gap-2.5 py-5 px-2.5'
)
const NormalStateClass = ref('flex flex-col gap-2.5 py-5 px-2.5')
const cells = ref([])

pageSpinner?.show()
Project.fetchAll().then(() => pageSpinner?.hide())

const projects = computed(() => useRepo(Project).all())

function destroy(id: string) {
    pageSpinner?.show()
    Project.destroy(id).finally(() => pageSpinner?.hide())
}

function log() {
    console.log(cells.value[2])
}

onMounted(() => {
    log()
})
</script>

<style>
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
