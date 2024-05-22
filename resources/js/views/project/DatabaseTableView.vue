<template>
    <div class="flex grow flex-col gap-10">
        <editor-table
            v-if="table"
            :columns="
                tab === 'data'
                    ? table.columns
                    : [
                          {
                              id: 'id',
                              name: '#',
                          },
                          {
                              id: 'name',
                              name: 'Column Name',
                          },
                          {
                              id: 'type',
                              name: 'Type',
                          },
                          {
                              id: 'is_nullable',
                              name: 'Empty allowed',
                          },
                          {
                              id: 'default',
                              name: 'Default value',
                          },
                          {
                              id: 'comment',
                              name: 'Comment',
                          },
                      ]
            "
            :is-structure="tab === 'structure'"
            :rows="
                tab === 'data'
                    ? rows
                    : Object.fromEntries(
                          table.columns.map((c, index) => [
                              c.id,
                              {
                                  ...c,
                                  index: index,
                              },
                          ])
                      )
            "
            @save="save"
        >
            <template #id="{ row }">
                <span>{{ row.index + 1 }}</span>
            </template>
            <template #footer>
                <div class="rounded-lg bg-gray-800">
                    <button
                        :class="{
                            'bg-gray-600': tab === 'data',
                        }"
                        class="rounded-lg px-2 py-1"
                        @click="tab = 'data'"
                    >
                        Data
                    </button>
                    <button
                        :class="{
                            'bg-gray-600': tab === 'structure',
                        }"
                        class="rounded-lg px-2 py-1"
                        @click="tab = 'structure'"
                    >
                        Structure
                    </button>
                </div>
            </template>
        </editor-table>
    </div>
</template>

<script lang="ts" setup>
import { computed, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import DatabaseTable from '@/models/DatabaseTable'
import { useRepo } from 'pinia-orm'
import * as api from '@/utils/api'
import EditorTable from '@/components/EditorTable.vue'
import useModal from '@/plugins/modal'
import DialogPopup from '@/components/modals/DialogPopup.vue'
import DatabaseTableColumn from '@/models/DatabaseTableColumn'

const route = computed(() => useRoute())

const modal = useModal()
const table = computed(() =>
    useRepo(DatabaseTable)
        .withAll()
        .find(route.value.params.table as string)
)
const rows = ref([])
const tab = ref('data')

watch(
    () => route.value.params.table,
    (value) => {
        DatabaseTable.fetch(
            route.value.params.project as string,
            route.value.params.table as string
        ).then(() => {
            table.value?.fetchRows().then(() => {
                const primaryId = table.value?.columns.find(
                    (column) => column.name === 'id'
                )?.id
                // set index to id
                rows.value = (table.value?.rows ?? []).reduce((acc, row) => {
                    acc[row[primaryId]] = row
                    return acc
                }, {})
                console.log(rows.value)
            })
        })
    },
    { immediate: true }
)

function save({
    added,
    changed,
    deleted,
    all,
}: {
    added: any[]
    changed: any[]
    deleted: number[]
    all: any
}) {
    console.log('save', added, changed)
    if (tab.value === 'data') {
        api.put(`/tables/${route.value.params.table}/rows`, {
            added: added,
            changed: changed,
            delete: deleted,
        })
            .then((res) => {
                rows.value = Object.fromEntries(
                    Object.entries(JSON.parse(JSON.stringify(all))).map(
                        ([rowKey, row]) => {
                            if (res.data.data[rowKey]) {
                                console.log(
                                    res.data.data[rowKey][
                                        table.value?.primary_id
                                    ]
                                )
                                return [
                                    res.data.data[rowKey][
                                        table.value?.primary_id
                                    ],
                                    res.data.data[rowKey],
                                ]
                            }
                            return [rowKey, row]
                        }
                    )
                )
                //
                // rowsCopy.value = JSON.parse(JSON.stringify(rows.value))
                // deleteRows.value = []
            })
            .catch((e) => {
                modal.show(
                    DialogPopup,
                    {
                        type: 'full',
                        title: 'Error',
                        message: e.response.data.error,
                        secondary: null,
                    },
                    {
                        onPrimary: (payload, close, modal) => {
                            close()
                        },
                    }
                )
            })
    } else {
        Promise.all(
            Object.entries(changed)
                .map(([key, column]) => {
                    return api
                        .put(
                            `/tables/${route.value.params.table}/columns/${column.id}`,
                            column
                        )
                        .then((res) => {
                            useRepo(DatabaseTableColumn)
                                .where('id', column.id)
                                .delete()
                            useRepo(DatabaseTableColumn).save(res.data.data)
                        })
                })
                .concat(
                    Object.entries(added).map(([key, column]) => {
                        return api
                            .post(
                                `/tables/${route.value.params.table}/columns`,
                                column
                            )
                            .then((res) => {
                                useRepo(DatabaseTableColumn).save(res.data.data)
                            })
                    })
                )
        )
    }
}
</script>

<style scoped></style>
