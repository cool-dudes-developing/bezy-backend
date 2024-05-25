<template>
    <aside class="flex flex-col gap-[15px] bg-[#1f1e1e] px-[20px] py-[30px]">
        <div class="flex items-center">
            <router-link
                :to="{
                    name: 'projects',
                }"
            >
                <svg-icon
                    class="h-6"
                    name="chevron-left"
                />
            </router-link>
            <h3
                class="line-clamp-1 font-header text-2xl font-bold text-[#f0f0f0]"
            >
                {{ project?.name }}
            </h3>
        </div>
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
                name="files"
            />
            Storage
        </router-link>
        <router-link
            :is="'div'"
            :to="{ name: 'projectTeam' }"
        >
            <svg-icon
                class="h-4 w-4"
                name="people"
            />
            Team
        </router-link>
    </aside>
</template>

<script lang="ts" setup>
import { useRepo } from 'pinia-orm'
import { computed, inject } from 'vue'
import Project from '@/models/Project'
import { useRoute, useRouter } from 'vue-router'
import { PageSpinnerKey } from '@/symbols'
import SvgIcon from '@/components/SvgIcon.vue'

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
    @apply text-electricBlue;
}
</style>
