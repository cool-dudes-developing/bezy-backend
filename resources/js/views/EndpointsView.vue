<template>
  <div :class="endpoints?.length != 0 ? NormalStateClass : EmptyStateClass">
    <div class="flex flex-col gap-2.5 p-2.5">
      <header class="h-12 font-header text-3xl font-bold text-pink">Endpoints</header>
      <table class="table-fixed border-collapse border border-border rounded">
        <tr class="bg-background-300 border border-border">
          <th>Endpoint</th>
          <th>Logic method</th>
          <th>Type</th>
          <th>Middlewares</th>
        </tr>
        <tr class="bg-background-400 border border-border">
          <td>GET /users</td>
          <td>Get users</td>
          <td>API</td>
          <td>1</td>
        </tr>
        <tr class="bg-background-400 border border-border">
          <td>GET /users/{user}</td>
          <td>Get user by id</td>
          <td>API</td>
          <td>2</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import Endpoint from '@/models/Endpoint'
import Project from '@/models/Project'
import { PageSpinnerKey } from '@/symbols'
import { useRepo } from 'pinia-orm'
import { ref, computed, inject } from 'vue'
import { useRoute } from 'vue-router'

const route = computed(() => useRoute())

const pageSpinner = inject(PageSpinnerKey)

pageSpinner?.show()
Endpoint.fetchAll(route.value.params.project as string)
  .then(() => console.log(route.value))
  .finally(() => pageSpinner?.hide())

const endpoints = computed(
  () =>
    useRepo(Project)
      .with('endpoints')
      .find(route.value.params.project as string)?.endpoints
)

const EmptyStateClass = ref('flex flex-col justify-center items-center gap-2.5 py-5 px-2.5 h-full')
const NormalStateClass = ref('flex flex-col gap-2.5 py-5 px-2.5')
</script>

<style>
th,
td {
  text-align: left;
  padding: 10px 12px;
}
</style>
