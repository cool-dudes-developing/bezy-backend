<template>
    <div
        class="absolute w-64 rounded-xl bg-black/50 pb-1 shadow-xl backdrop-blur"
    >
        <div
            class="absolute -left-3 top-0 h-6 w-6 rounded-full border-4 border-petronas bg-[#353648]"
        />
        <div
            class="flex h-6 flex-row justify-between rounded-tr-xl bg-petronas pl-4 text-dark"
        >
            <h1 class="">Add block</h1>
            <button @click="emit('close')">
                <svg-icon
                    class="aspect-square h-6 w-6"
                    name="close"
                />
            </button>
        </div>
        <transition
            appear
            appear-active-class="duration-300"
            appear-class="opacity-0"
            appear-to-class="opacity-100"
        >
            <div class="flex flex-col overflow-hidden">
                <div
                    class="mx-1 my-1 flex rounded bg-petronas/10 focus-within:ring"
                >
                    <svg-icon
                        class="h-6"
                        name="search"
                    />
                    <input
                        ref="searchInput"
                        v-model="blocksSearch"
                        class="grow appearance-none bg-transparent focus:outline-none"
                        type="text"
                        @keydown.down.prevent="nextItem"
                        @keydown.up.prevent="prevItem"
                        @keydown.enter.prevent="
                            emit(
                                'add',
                                blocksSearchResults.find(
                                    (b) => b.id === highlightIndex
                                )
                            )
                        "
                        @keydown.esc="emit('close')"
                    />
                </div>
                <transition-group
                    ref="container"
                    class="max-h-52 overflow-y-auto overflow-x-hidden"
                    enter-active-class="duration-300"
                    enter-from-class="opacity-0 translate-x-5"
                    leave-active-class="duration-300"
                    leave-to-class="opacity-0 absolute translate-x-5"
                    move-class="duration-300"
                    tag="div"
                >
                    <p
                        v-if="!blocksSearchResults.length"
                        class="py-2 text-center"
                    >
                        No blocks found
                    </p>
                    <template v-for="(items, group) in blocksGrouped">
                        <div
                            v-if="
                                group !== '' &&
                                items.length &&
                                group !== null &&
                                group !== 'null'
                            "
                            :key="'supergroup' + group"
                            class="sticky top-0 z-10 bg-black/50 px-1 py-1 text-sm backdrop-blur-xl"
                        >
                            {{ group.split('/').join(' ') }}
                        </div>

                        <div
                            v-for="block in items"
                            :key="block.id"
                            :class="{
                                'bg-dark': highlightIndex === block.id,
                            }"
                            :data-index="block.id"
                            class="flex flex-row items-center justify-between gap-1 px-1 hover:bg-dark"
                            @click="emit('add', block)"
                        >
                            <h2 class="line-clamp-1 text-base">
                                {{ block.title }}
                            </h2>
                            <button
                                @click.stop="
                                    modal.show(BlockDetails, {
                                        block,
                                    })
                                "
                            >
                                <svg-icon
                                    class="h-5 w-5"
                                    name="help"
                                />
                            </button>
                        </div>
                    </template>
                </transition-group>
            </div>
        </transition>
    </div>
</template>
<script lang="ts" setup>
import { computed, onMounted, ref, type TransitionGroup, watch } from 'vue'
import { useRepo } from 'pinia-orm'
import Block from '@/models/Block'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import SvgIcon from '@/components/SvgIcon.vue'
import useModal from '@/plugins/modal'
import BlockDetails from '@/components/modals/BlockDetails.vue'
import { groupBy } from '../utils/utils'

const emit = defineEmits(['close', 'add'])

const props = defineProps({
    filter: {
        type: String,
        required: true,
    },
})

const highlightIndex = ref<string | null>(null)
const blockRepo = computed(() => useRepo(Block))
const assetRepo = computed(() => useRepo(MarketplaceAsset))
const templateBlocks = computed(() =>
    blockRepo.value
        .where('type', (value) => value !== 'endpoint')
        .where('method_id', null)
        .withAll()
        .get()
        .map((block) => {
            if (!block.category) block.category = 'Your Methods'
            return block
        })
)
const templateAssets = computed(() =>
    assetRepo.value
        .withAll()
        .all()
        .map((asset) => {
            if (asset.is_liked) asset.block.category = 'Liked Assets'
            else asset.block.category = 'Community Assets'
            asset.block.title = asset.name
            return asset.block
        })
        .filter((block) => !templateBlocks.value.find((b) => b.id === block.id))
)
const blocksSearch = ref('')
const blocksFiltered = computed(() => {
    return templateBlocks.value
        .slice()
        .concat(templateAssets.value)
        .filter(
            (block) =>
                props.filter === '' ||
                props.filter === 'any' ||
                block.ports.map((p) => p.type).includes(props.filter) ||
                block.ports.map((p) => p.type).includes('any')
        )
})
const blocksSearchResults = computed(() => {
    return blocksFiltered.value.filter(
        (block) =>
            block.name
                ?.toLowerCase()
                .includes(blocksSearch.value.toLowerCase()) ||
            block.title
                ?.toLowerCase()
                .includes(blocksSearch.value.toLowerCase()) ||
            block.category
                ?.toLowerCase()
                .includes(blocksSearch.value.toLowerCase())
    )
})

const blocksGrouped = computed(() => {
    return Object.fromEntries(
        Object.entries(groupBy(blocksSearchResults.value, 'category'))
            .sort((group, blocks) => {
                if (group[0] === 'Your Methods') return -1
                if (group[0] === 'Liked Assets') return -1
                if (group[0] === 'Community Assets') return 1
                return 0
            })
    )
})

const blocksGroupedFlatIds = computed(() => {
    return blocksSearchResults.value.map((block) => block.id)
})

const searchInput = ref<HTMLInputElement | null>(null)
const container = ref<InstanceType<typeof TransitionGroup> | null>(null)
const modal = useModal()

watch(
    () => highlightIndex.value,
    (index) => {
        if (index !== null) {
            const el = container.value?.$el.querySelector(
                `[data-index="${index}"]`
            )
            if (el) {
                const rect = el.getBoundingClientRect()
                console.log(el.offsetTop - rect.height)
                container.value?.$el.scrollTo({
                    top: el.offsetTop - rect.height - 100,
                    behavior: 'smooth',
                })
            }
        }
    }
)

onMounted(() => {
    searchInput.value?.focus()
})

function nextItem() {
    const currentIndex = blocksGroupedFlatIds.value.indexOf(
        highlightIndex.value!
    )
    if (currentIndex < blocksGroupedFlatIds.value.length - 1) {
        highlightIndex.value = blocksGroupedFlatIds.value[currentIndex + 1]
    }
}

function prevItem() {
    const currentIndex = blocksGroupedFlatIds.value.indexOf(
        highlightIndex.value!
    )
    if (currentIndex > 0) {
        highlightIndex.value = blocksGroupedFlatIds.value[currentIndex - 1]
    }
}
</script>
<style>
/* element styling */
.available-cell rect {
    stroke-dasharray: 5, 2;
}
</style>
