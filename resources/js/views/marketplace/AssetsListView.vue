<template>
  <ul class="flex flex-col gap-3 p-5">
    <router-link
      v-for="asset in assets"
      :key="asset.id"
      v-slot="{navigate}"
      :to="{
      name: 'asset',
      params: {
        id: asset.id
      }
    }"
      custom
    >
      <li class="border px-5 py-2 border-accent flex justify-between" @click="navigate">

        <div>
          <h1 class="text-xl">{{ asset.name }}</h1>
          <small>caption</small>
        </div>
        <small>
          {{ asset.downloads }} downloads
        </small>
      </li>
    </router-link>
  </ul>
</template>

<script lang="ts" setup>
import { useRepo } from 'pinia-orm'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { computed, onMounted } from 'vue'

const assetRepo = useRepo(MarketplaceAsset)
const assets = computed(() => assetRepo.all())

onMounted(() => {
  MarketplaceAsset.fetchAll()
})
</script>

<style scoped>

</style>