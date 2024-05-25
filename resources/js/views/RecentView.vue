<template>
    <div class="flex flex-col gap-2.5 px-5 py-7">
        <v-card
            class="flex flex-col gap-2.5"
            disable-create
        >
            <template #title>
                <header class="text-3xl font-bold">Your Recent Projects</header>
            </template>
            <div class="flex flex-wrap gap-2">
                <CardComponent
                    v-for="project in projects"
                    :key="project.id"
                    :link="'/platform/projects/' + project.id"
                    :primaryText="project.name"
                    iconName="folder-big"
                    secondaryText="Open project"
                />
                <CardComponent
                    v-if="projects.length < 10"
                    iconName="plus"
                    link="/platform/projects/create"
                    primaryText="Create project"
                    secondaryText="New project"
                />
            </div>
        </v-card>
        <v-card disable-create>
            <template #title>Recent Marketplace Assets</template>
            <template #subtitle>Blocks created by the community</template>
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
import { computed, inject, onMounted } from 'vue'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey, SpinnerKey } from '@/symbols'
import CardComponent from '@/components/CardComponent.vue'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import AssetCard from '@/views/marketplace/AssetCard.vue'
import { useRouter } from 'vue-router'

const pageSpinner = inject(PageSpinnerKey)
const router = useRouter()

pageSpinner?.show()
Project.fetchAll().then(() => pageSpinner?.hide())

const projects = computed(() =>
    useRepo(Project).orderBy('updated_at', 'desc').limit(10).get()
)

const assetRepo = useRepo(MarketplaceAsset)
const assets = computed(() =>
    assetRepo.orderBy('updated_at', 'desc').limit(10).get()
)

onMounted(() => {
    MarketplaceAsset.fetchRecent()
})
</script>

<style>
.hub-container {
    overflow-x: scroll;
}

.hub-content {
    display: flex;
    gap: 2.5rem;
    min-width: fit-content;
    white-space: nowrap;
}

.double-gap {
    gap: 0.75rem 1%;
}
</style>
