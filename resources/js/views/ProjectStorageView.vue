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
                        @click="createDirectory()"
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
            <div
                class="flex items-center gap-3 rounded-xl bg-accent-900 px-2 font-mono text-xs"
            >
                <button
                    @click="
                        () => {
                            if (browsePath === '/') {
                                return
                            }
                            loadFiles(
                                browsePath.split('/').slice(0, -2).join('/') +
                                    '/'
                            )
                        }
                    "
                >
                    <svg-icon
                        class="h-6 w-6"
                        name="arrow-up"
                    />
                </button>
                {{ browsePath }}
            </div>
            {{ editPath }}
            <items-list
                :items="directories.concat(files)"
                @delete="
                    api.del(`/projects/${project.value.id}/files/${$event.id}`)
                "
                @select="selectFile($event)"
            >
                <template #item="{ item }">
                    <div class="flex items-center gap-2">
                        <svg-icon
                            :name="
                                item.type === 'directory'
                                    ? 'folder'
                                    : item.type.startsWith('image')
                                      ? 'file-image'
                                      : item.type.includes('text') ||
                                          item.type.includes('json')
                                        ? 'file-text'
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
                        <input
                            v-if="editPath === item.path"
                            v-model="item.name"
                            class="bg-sec"
                            @blur="renameFile(item.path, item.name)"
                            @click.stop.prevent
                        />
                        <p v-else>
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
                <template #additional-actions="{ item }">
                    <button @click.stop.prevent="editPath = item.path">
                        <svg-icon
                            class="h-4 w-4"
                            name="file-pen"
                        />
                    </button>
                    <button
                        v-if="item.type !== 'directory'"
                        @click.stop.prevent="downloadItem(item)"
                    >
                        <svg-icon
                            class="h-4 w-4"
                            name="file-down"
                        />
                    </button>
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
const directories = ref([])
const browsePath = ref('/')
const editPath = ref('')

function loadFiles(path: string) {
    browsePath.value = path
    api.get(`/projects/${route.value.params.project}/files`, {
        params: {
            path,
        },
    }).then((response) => {
        files.value = response.data.files
        directories.value = response.data.directories
    })
}

onMounted(() => {
    Project.fetch(route.value.params.project as string)
        .then(() => {
            loadFiles(browsePath.value)
        })
        .catch(() => router.push({ name: '404' }))
        .finally(() => pageSpinner?.hide())
})

function downloadItem(item) {
    const link = document.createElement('a')
    link.href = `/storage/${item.path}`
    link.download = item.name
    link.click()
}

function selectFile(file) {
    if (file.type === 'directory') {
        loadFiles(browsePath.value + file.name + '/')
    }
}

function createDirectory(name: string | undefined) {
    api.post(`/projects/${project.value.id}/files`, {
        name: name || 'New Folder',
        path: browsePath.value,
    }).then((res) => {
        directories.value.push(res.data)
    })
}

function uploadFiles(payload, close, m, force = false) {
    const [filesToUpload] = payload as [FileList]
    const formData = new FormData()

    for (let i = 0; i < filesToUpload.length; i++) {
        formData.append('files[]', filesToUpload[i])
    }

    formData.append('force', force ? 1 : 0)
    formData.append('path', browsePath.value)

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

function renameFile(path, name) {
    api.put(`/projects/${project.value.id}/files/${name}`, {
        path,
        name,
    }).then(() => {
        editPath.value = ''
        loadFiles(browsePath.value)
    })
}
</script>
