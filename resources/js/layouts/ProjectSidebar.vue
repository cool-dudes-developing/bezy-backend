<template>
    <aside class="flex flex-col gap-[15px] bg-[#1f1e1e] px-[20px] py-[30px]">
        <h3 class="font-header text-2xl font-bold text-[#f0f0f0] line-clamp-1">
            {{ project?.name }}
        </h3>
        <router-link
            :is="'div'"
            :to="{ name: '404' }"
        >
            <svg-icon
                class="h-4 w-4"
                name="image"
            />
            Frontend
        </router-link>
        <router-link
            :is="'div'"
            :to="{ name: 'projectBackend' }"
        >
            <svg-icon
                class="h-4 w-4"
                name="code"
            />
            Backend
        </router-link>
        <router-link
            :is="'div'"
            :to="{ name: 'tables' }"
        >
            <svg-icon
                class="h-4 w-4"
                name="database"
            />
            Database
        </router-link>
        <router-link
            :is="'div'"
            :to="{ name: 'projectStorage' }"
        >
            <svg-icon
                class="h-4 w-4"
                name="folder"
            />
            Storage
        </router-link>
        <router-link
            :is="'div'"
            id="hubReturn"
            :to="{ name: 'platform' }"
            class="mt-auto"
        >
            <svg-icon
                class="h-4 w-4"
                name="log-out"
            />
            Go to hub
        </router-link>
    </aside>
</template>

<script lang="ts" setup>
import { useRepo } from 'pinia-orm'
import { computed, inject } from 'vue'
import Project from '@/models/Project'
import { useRoute, useRouter } from 'vue-router'
import { PageSpinnerKey } from '@/symbols'

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
    .catch((err) => router.push({ name: '404' }))
    .finally(() => pageSpinner?.hide())

// const dropdownOpen = ref({ database: false, logic: false, endpoints: false, storage: false })

// function toggleDropdown(key: string) {
//   dropdownOpen.value[key] = !dropdownOpen.value[key]
// }
</script>

<style scoped>
aside > * {
    @apply flex items-center gap-[5px] font-sidebarButton font-medium;
}

.dropdown > * {
    @apply flex items-center gap-[5px] font-sidebarButton font-medium;
}

.dropdown {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.6s ease-out;
}

#hubReturn {
    @apply text-white;
}

.dropdown.active {
    max-height: max-content;
}

.router-link-active {
    @apply text-purplePizzaz;
}
</style>
