<template>
  <div v-if="asset" class="flex flex-col gap-3 p-5">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl">
          {{ asset.name }}
        </h1>
        <small>
          caption here
        </small>
      </div>
      <div class="flex gap-2 items-center text-sm">
        <div class="w-5 h-5 bg-pink rounded-full" />
        <div>
          {{ asset.author.name }}
        </div>
      </div>
    </div>
    <div class="flex gap-2">
      <button class="bg-accent-700">
        ⭐️
        Favorite
      </button>
      <button class="bg-accent-700">
        📥
        Add to method
      </button>
    </div>
    <p>
      {{ asset.description }}
    </p>
    <div class="flex gap-2">
      <div class="w-64 h-32 bg-accent-400">
        Image here
      </div>
      <div class="w-64 h-32 bg-accent-400">
        Image here
      </div>
      <div class="w-64 h-32 bg-accent-400">
        Image here
      </div>
    </div>
    <div v-for="version in asset.versions" :key="version.id" class="border border-accent px-5 py-2 grid grid-cols-2">
      <div class="flex flex-col">
        <span>v{{ version.version }}</span>
        <span class="text-purplePizzaz">{{version.type}}</span>
      </div>
      <p class="line-clamp-3">
        <b>Change log here</b>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet assumenda corporis cum doloremque minima nam, necessitatibus odio officia omnis, quidem soluta. Ea eum eveniet excepturi harum laudantium provident quod?
      </p>
    </div>
  </div>
</template>

<script lang="ts" setup>

import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { useRepo } from 'pinia-orm'

const route = computed(() => useRoute())
const assetRepo = computed(() => useRepo(MarketplaceAsset))
const asset = computed(() => {
  return assetRepo.value.find(route.value.params.id as string)
})

onMounted(() => {
  MarketplaceAsset.fetch(route.value.params.id as string)
})
</script>

<style scoped>

</style>