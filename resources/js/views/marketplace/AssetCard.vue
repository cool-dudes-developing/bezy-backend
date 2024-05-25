<template>
    <div class="flex w-full items-center justify-between">
        <div>
            <h1 class="text-xl">{{ item.name }}</h1>
            <small>{{ item.caption }}</small>
        </div>
        <div class="flex gap-3 text-xs text-gray-400">
            <button
                class="flex items-center gap-1"
                @click.stop.prevent="MarketplaceAsset.like(item.id)"
            >
                {{ item.likes }}
                <svg-icon
                    :class="{
                        'fill-red-500': item.is_liked,
                    }"
                    class="h-4 text-red-600"
                    name="heart"
                />
            </button>
            <small class="flex items-center gap-1">
                {{ item.usages }}
                <svg-icon
                    class="h-4 text-white"
                    name="file-down"
                />
            </small>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import MarketplaceAsset from '@/models/MarketplaceAsset'

defineProps({
    item: {
        type: Object as PropType<MarketplaceAsset>,
        required: true,
    },
})
</script>
