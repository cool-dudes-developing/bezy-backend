<template>
    <div
        v-if="project"
        class="p-10"
    >
        <v-card>
            <template #actions>
                <div class="flex gap-2">
                    <button
                        class="flex items-center gap-2 p-1 text-xs"
                        @click="emit('create')"
                    >
                        <svg-icon
                            class="h-5 w-5"
                            name="folder-plus"
                        />
                    </button>
                    <button
                        class="flex items-center gap-2 p-1 text-xs"
                        @click="
                            modal.show(
                                UploadFile,
                                {
                                    type: 'full',
                                },
                                {
                                    onUpload: uploadFiles,
                                }
                            )
                        "
                    >
                        <svg-icon
                            class="h-5 w-5"
                            name="file-plus"
                        />
                    </button>
                </div>
            </template>
            <template #title>Storage</template>
            <template #subtitle>Manage your project storage.</template>
            <items-list
                :items="files"
                @delete="
                    api.del(`/projects/${project.value.id}/files/${$event.id}`)
                "
            >
                <template #item="{ item }">
                    <div class="flex items-center gap-2">
                        <svg-icon
                            :name="
                                item.type.startsWith('image')
                                    ? 'file-image'
                                    : item.type.startsWith('folder')
                                      ? 'folder'
                                      : item.type.startsWith('application')
                                        ? 'file-archive'
                                        : item.type.startsWith('audio')
                                          ? 'file-audio'
                                          : item.type.startsWith('video')
                                            ? 'file-video'
                                            : 'file'
                            "
                            class="h-4"
                        />
                        <p>
                            {{ item.name }}
                        </p>
                    </div>
                </template>
                <template #empty>
                    <p class="max-w-prose">
                        You have not uploaded any files to this project. Upload
                        files manually or through the API.
                    </p>
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { computed, inject, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { useRepo } from 'pinia-orm'
import Project from '@/models/Project'

import { PageSpinnerKey } from '@/symbols'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'
import * as api from '@/utils/api'
import useModal from '@/plugins/modal'
import DialogPopup from '@/components/modals/DialogPopup.vue'
import SvgIcon from '@/components/SvgIcon.vue'
import UploadFile from '@/components/modals/UploadFile.vue'

const route = computed(() => useRoute())
const router = useRouter()
const pageSpinner = inject(PageSpinnerKey)
const modal = useModal()

const project = computed(() =>
    useRepo(Project)
        .withAll()
        .find(route.value.params.project as string)
)

pageSpinner?.show()
const files = ref([])

onMounted(() => {
    Project.fetch(route.value.params.project as string)
        .then(() => {
            api.get(`/projects/${route.value.params.project}/files`).then(
                (response) => {
                    files.value = response.data
                }
            )
        })
        .catch(() => router.push({ name: '404' }))
        .finally(() => pageSpinner?.hide())
})

function uploadFiles(payload, close, m, force = false) {
    const [filesToUpload] = payload as [FileList]
    const formData = new FormData()

    for (let i = 0; i < filesToUpload.length; i++) {
        formData.append('files[]', filesToUpload[i])
    }

    formData.append('force', force ? 1 : 0)

    api.post(`/projects/${project.value.id}/files`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then((response) => {
            response.data.forEach((file) => {
                if (!files.value.find((f) => f.name === file.name)) {
                    files.value.push(file)
                }
            })
            close()
        })
        .catch((err) => {
            if (err.response.status === 409) {
                modal.show(
                    DialogPopup,
                    {
                        type: 'full',
                        title: 'File already exists',
                        message:
                            'The file you are trying to upload already exists.',
                        variant: 'danger',
                        primary: 'Overwrite',
                        secondary: 'Cancel',
                    },
                    {
                        onPrimary: (payloadInner, closeInner, modalInner) => {
                            closeInner()
                            uploadFiles(payload, close, modal, true)
                        },
                        onSecondary: (payloadInner, closeInner, modalInner) => {
                            closeInner()
                            close()
                        },
                    }
                )
            }
        })
}
</script>
