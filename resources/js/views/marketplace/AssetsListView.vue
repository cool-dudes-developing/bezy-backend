<template>
    <div class="p-10">
        <v-card disable-create>
            <template #title>Assets</template>
            <template #subtitle>Blocks created by the community</template>
            <div class="flex justify-between">
                <div
                    class="flex items-center rounded-lg border border-petronas px-1 outline-1 focus-within:outline"
                >
                    <input
                        v-model="search"
                        class="appearance-none bg-transparent px-1 text-white outline-0 ring-0"
                        placeholder="Search for assets"
                    />
                    <button @click="search">
                        <svg-icon
                            class="h-4"
                            name="search"
                        />
                    </button>
                </div>
            </div>
            <items-list
                :delete-enabled="false"
                :items="assets"
                @select="
                    router.push({
                        name: 'asset',
                        params: {
                            id: $event.id,
                        },
                    })
                "
            >
                <template #item="{ item }">
                    <asset-card :item="item" />
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { useRepo } from 'pinia-orm'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { computed, onMounted, ref } from 'vue'
import ItemsList from '@/components/ItemsList.vue'
import VCard from '@/components/VCard.vue'
import { useRouter } from 'vue-router'
import SvgIcon from '@/components/SvgIcon.vue'
import AssetCard from '@/views/marketplace/AssetCard.vue'

const search = ref('')
const assetRepo = useRepo(MarketplaceAsset)
const assets = computed(() =>
    assetRepo
        .where((item) => {
            if (search.value.trim() === '') return true
            const strings = [
                ...item.name.toLowerCase().split(' '),
                ...item.description.toLowerCase().split(' '),
                ...item.caption.toLowerCase().split(' '),
            ].filter((string) => string !== '')
            const searchStrings = search.value.toLowerCase().split(' ')
            return (
                searchStrings.filter((searchString) =>
                    strings.some((string) => string.includes(searchString))
                ).length !== 0
            )
        })
        .get()
)
const router = useRouter()

onMounted(() => {
    MarketplaceAsset.fetchAll()
})
</script>

