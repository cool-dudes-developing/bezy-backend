<template>
    <div v-if="project">
        <header class="h-12 font-header text-3xl font-bold text-pink">{{ project.name }}</header>
        <header class="h-10 font-header text-2xl font-bold text-pink">Storage</header>
        <TableComponent>
            <TableHeaderRow>
                <th>Name</th>
                <th>Type</th>
                <th>Created</th>
                <th class="w-10 border-none"></th>
            </TableHeaderRow>
            <input type="file" id="file-upload" hidden>
            <TableRow>
                <td colspan="4" class="text-center text-blue font-bold p-0">
                    <label for="file-upload" class="cursor-pointer">
                        <div class="h-full p-[10px]">
                            Upload file
                        </div>
                    </label>
                </td>
            </TableRow>
        </TableComponent>
    </div>
    <div v-else>Error (project.value untruthy)</div>
</template>

<script lang="ts" setup>
import { computed, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import TableComponent from '@/components/TableComponent.vue'
import TableHeaderRow from '@/components/TableHeaderRow.vue'
import TableRow from '@/components/TableRow.vue'

const route = computed(() => useRoute())
const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)

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