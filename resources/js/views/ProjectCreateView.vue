<template>
  <div class="flex flex-col items-center justify-center gap-2.5 w-full h-full">
    <form action="" class="flex flex-col gap-2.5" @submit.prevent="create">
      <header class="h-12 font-header text-3xl font-bold text-pink">Create new project</header>
      <InputComponent name="Project name" v-model="name"></InputComponent>
      <label for="description" class="h-[1.375rem] font-label flex items-center text-white"
        >Project description</label
      >
      <textarea
        v-model="description"
        name="description"
        class="p-[0.625rem] w-[100%] h-[7.5rem] bg-transparent backdrop-brightness-75 drop-shadow-sm border border-blue rounded-[0.9375rem] text-white font-input placeholder:text-white active:backdrop-brightness-50 focus:backdrop-brightness-50 transition"
      />
      <input
        type="submit"
        class="flex justify-center items-center h-[2.5rem] button"
        value="Create"
      />
    </form>
  </div>
</template>

<script lang="ts" setup>
import InputComponent from '@/components/InputComponent.vue'
import Project from '@/models/Project'
import { PageSpinnerKey } from '@/symbols'
import { useRepo } from 'pinia-orm'
import { inject, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const description = ref('')
const pageSpinner = inject(PageSpinnerKey)

function create() {
  pageSpinner?.show()
  Project.store({
    name: name.value,
    description: description.value
  })
    .then(() => {
      const new_project_id = useRepo(Project).orderBy('created_at', 'desc').first()
      router.push({ name: 'project', params: { 'project': new_project_id?.id } })
    })
    .finally(() => pageSpinner?.hide())
}
</script>
