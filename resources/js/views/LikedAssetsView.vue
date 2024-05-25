<template>
    <div class="p-10">
        <v-card disable-create>
            <template #title>Liked Assets</template>
            <template #subtitle>Assets you have liked</template>
            <items-list :items="assets">
                <template #item="{ item }">
                    <asset-card :item="item" />
                </template>
                <template #empty>
                    <div class="flex flex-col gap-5 items-start">
                        <p class="max-w-prose">
                            You have not liked any assets yet. You can like assets
                            by clicking the heart icon on the asset card. Liked
                            assets will be displayed first in method editor.
                        </p>
                        <v-button variant="primary" @click="router.push({
                            name: 'marketplace',
                        })">
                            Go to Marketplace
                        </v-button>
                    </div>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import { computed, onMounted } from 'vue'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { useRepo } from 'pinia-orm'
import AssetCard from '@/views/marketplace/AssetCard.vue'
import VButton from '@/components/VButton.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const assets = computed(() => {
    return useRepo(MarketplaceAsset).where('is_liked', true).get()
})

onMounted(() => {
    MarketplaceAsset.fetchLiked()
})
</script>

<style scoped></style>
