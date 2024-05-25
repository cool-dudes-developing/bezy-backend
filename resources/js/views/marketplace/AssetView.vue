<template>
    <div
        v-if="asset"
        class="flex flex-col gap-3 p-5"
    >
        <div class="flex items-center justify-between">
            <div class="flex grow flex-col gap-3">
                <input
                    v-if="asset.author.id === User.currentUser.id"
                    v-model="asset.name"
                    class="w-full max-w-md appearance-none rounded-lg border bg-transparent px-1 text-3xl"
                    placeholder="Set the name of your asset"
                />
                <h1
                    v-else
                    class="max-w-md text-3xl"
                >
                    {{ asset.name }}
                </h1>
                <input
                    v-if="asset.author.id === User.currentUser.id"
                    v-model="asset.caption"
                    class="max-w-xs appearance-none rounded-lg border bg-transparent px-1"
                    placeholder="Set your caption here..."
                />
                <small
                    v-else
                    class="max-w-xs"
                >
                    {{ asset.caption }}
                </small>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <template v-if="asset.author.id !== User.currentUser?.id">
                    <div
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-pink text-xs"
                    >
                        {{ asset.author.name[0].toUpperCase() }}
                    </div>
                    <div>
                        {{ asset.author.name }}
                    </div>
                </template>
                <v-button
                    v-else
                    class="flex items-center gap-2"
                    variant="primary"
                    @click="MarketplaceAsset.update(asset.id, asset)"
                >
                    <svg-icon
                        class="h-5"
                        name="save"
                    />
                    Save
                </v-button>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <v-button
                class="flex gap-2"
                variant="secondary"
                @click="MarketplaceAsset.like(asset.id)"
            >
                <svg-icon
                    :class="{
                        'fill-red-500': asset.is_liked,
                    }"
                    class="h-6 text-red-600"
                    name="heart"
                />
                Favorite
            </v-button>
            <span v-if="true">
                {{ asset.usages ?? '0' }}
                Usages
            </span>
            <span>
                {{ asset.likes ?? '0' }}
                Likes
            </span>
        </div>
        <textarea
            v-if="asset.author.id === User.currentUser.id"
            v-model="asset.description"
            class="appearance-none rounded-lg border bg-transparent px-1"
            placeholder="Set your description here..."
        />
        <p v-else>
            {{ asset.description }}
        </p>
        <div class="flex gap-3">
            <span
                v-for="tag in asset.tags"
                class="rounded-full bg-petronas px-1 text-xs text-dark"
            >
                {{ tag }}
            </span>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { useRepo } from 'pinia-orm'
import SvgIcon from '@/components/SvgIcon.vue'
import User from '@/models/User'
import VButton from '@/components/VButton.vue'

const route = computed(() => useRoute())
const assetRepo = computed(() => useRepo(MarketplaceAsset))
const asset = computed(() => {
    return assetRepo.value.find(route.value.params.id as string)
})

onMounted(() => {
    MarketplaceAsset.fetch(route.value.params.id as string)
})
</script>

<style scoped></style>
