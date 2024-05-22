<template>
    <div class="stripped flex grow flex-col">
        <table
            ref="tableElement"
            class="min-w-full table-fixed text-sm"
        >
            <thead>
                <tr class="h-[22px]">
                    <th
                        v-for="column in columns"
                        :key="column.id"
                        class="w-72"
                    >
                        {{ column.name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, index) in rowsCopy"
                    :key="index"
                    :class="[getRowState(index)]"
                    class="h-[22px]"
                    tabindex="-1"
                    @click="edit.rowId = index"
                    @keydown.delete.self="deleteRow(index)"
                >
                    <td
                        v-for="column in columns"
                        :key="column.id"
                        :class="[getCellState(index, column.id)]"
                        class="overflow-hidden border-x border-border"
                        @dblclick="editRecord(index, column.id)"
                    >
                        <slot
                            :name="column.id"
                            :row="row"
                        >
                            <editor-table-cell
                                v-model="row[column.id]"
                                :edit="
                                    edit.rowId === index &&
                                    edit.columnId === column.id
                                "
                                :is-structure="isStructure"
                                :type="isStructure ? column.id : 'text'"
                                @blur="onCellBlur(index, column.id)"
                                @next-row="editNextRow"
                                @next-cell="editNextCell"
                                @prev-row="editNextRow(-1)"
                                @prev-cell="editNextCell(-1)"
                            />
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="grow"></div>
        <div
            class="sticky bottom-0 flex w-full items-center gap-2 border-t border-border bg-dark px-2 py-1 text-sm"
        >
            <slot name="footer" />
            <button
                class="rounded-lg bg-gray-600 px-2 py-1"
                @click="addRow"
            >
                + {{ isStructure ? 'Column' : 'Row' }}
            </button>
            <button
                class="rounded-lg bg-gray-600 px-2 py-1"
                @click="
                    emit('save', {
                        added: newRows,
                        changed: modifiedRows,
                        deleted: markedRows,
                        all: rowsCopy,
                    })
                "
            >
                Save
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, type PropType, ref, watch } from 'vue'
import type DatabaseTableColumn from '@/models/DatabaseTableColumn'
import EditorTableCell from '@/components/EditorTableCell.vue'
import { onClickOutside, useMagicKeys } from '@vueuse/core'

const emit = defineEmits(['save'])
const props = defineProps({
    columns: {
        type: Array as PropType<DatabaseTableColumn[]>,
        required: true,
    },
    rows: {
        type: Object as PropType<
            Record<
                string,
                Record<string, string | undefined | null | number | boolean>
            >
        >,
        required: true,
    },
    isStructure: {
        type: Boolean,
        default: false,
    },
})

const tableElement = ref<HTMLElement | null>(null)
const rowsCopy = ref<
    Record<string, Record<string, string | undefined | null | number | boolean>>
>({})
const markedRows = ref<string[]>([])

const newRows = computed(() => {
    return Object.fromEntries(
        Object.keys(rowsCopy.value)
            .filter((key) => key.startsWith('%NEW_ROW%'))
            .map((key) => [key, rowsCopy.value[key]])
    )
})

const modifiedRows = computed(() => {
    return Object.fromEntries(
        Object.keys(rowsCopy.value)
            .filter(
                (key) =>
                    !key.startsWith('%NEW_ROW%') &&
                    Object.keys(rowsCopy.value[key]).some(
                        (columnId) =>
                            rowsCopy.value[key][columnId] !==
                            props.rows[key]?.[columnId]
                    )
            )
            .map((key) => [key, rowsCopy.value[key]])
    )
})

useMagicKeys({
    passive: false,
    onEventFired(e) {
        if (e.key === 's' && (e.metaKey || e.ctrlKey)) {
            e.preventDefault()
            emit('save', {
                added: newRows.value,
                changed: modifiedRows.value,
                deleted: markedRows.value,
                all: rowsCopy.value,
            })
        }
    },
})
watch(
    () => props.rows,
    () => {
        rowsCopy.value = JSON.parse(JSON.stringify(props.rows))
        markedRows.value = []
    },
    { immediate: true }
)
onClickOutside(tableElement, () => {
    edit.value.rowId = '%'
    edit.value.columnId = null
})

const edit = ref<{ rowId: string; columnId: string | null }>({
    rowId: '%',
    columnId: null,
})

function getRowState(rowId: string) {
    if (rowId === edit.value.rowId) {
        return 'bg-sec'
    } else if (rowId.startsWith('%NEW_ROW%')) {
        // this is a new row
        return 'bg-green-800'
    } else if (markedRows.value.includes(rowId)) {
        return 'bg-red-800'
    } else {
        // if (rowId % 2 === 0) return 'bg-[#1f1e1e]'
        // else return 'bg-[#2a2a2a]'
    }
}

function getCellState(rowId: string, columnId: string) {
    // if this is not a new row
    if (props.rows[rowId] && !markedRows.value.includes(rowId)) {
        // if the value in the copy is different from the value in the original
        if (rowsCopy.value[rowId][columnId] !== props.rows[rowId]?.[columnId]) {
            return 'bg-orange-500'
        }
    }
}

function editRecord(rowId: string, columnId: string) {
    edit.value.rowId = rowId
    edit.value.columnId = columnId
    requestAnimationFrame(() => {
        const input = document.querySelector('input')
        input?.focus()
    })
}

function onCellBlur(rowId: string, columnId: string) {
    if (edit.value.rowId === rowId && edit.value.columnId === columnId) {
        edit.value.columnId = null
    }
}

function addRow() {
    const newRowId = '%NEW_ROW%' + Math.random().toString(36).substring(7)
    rowsCopy.value[newRowId] = Object.fromEntries(
        props.columns.map((column) => [column.id, undefined])
    )
    editRecord(newRowId, props.columns[0].id)
}

function deleteRow(rowId: string) {
    if (rowId.startsWith('%NEW_ROW%')) {
        delete rowsCopy.value[rowId]
    } else {
        markedRows.value.push(rowId)
        editNextRow(1, true)
    }
}

function editNextRow(
    direction: number = 1,
    dontAddRow: boolean = false,
    columnId?: string
) {
    const keys = Object.keys(rowsCopy.value)
    const currentRowIndex = keys.findIndex((key) => key === edit.value.rowId)
    const nextKey = keys[currentRowIndex + direction] ?? undefined
    if (nextKey !== undefined) {
        editRecord(
            nextKey,
            columnId ?? edit.value.columnId ?? props.columns[0].id
        )
    } else {
        if (direction === 1 && !dontAddRow) {
            addRow()
        }
    }
}

function editNextCell(direction: number = 1) {
    const currentColumnIndex = props.columns.findIndex(
        (column) => column.id === edit.value.columnId
    )
    if (currentColumnIndex !== -1) {
        const nextColumn = props.columns[currentColumnIndex + direction]
        if (nextColumn !== undefined) {
            editRecord(edit.value.rowId, nextColumn.id)
        } else {
            editNextRow(
                direction,
                false,
                props.columns.at(direction === 1 ? 0 : -1)?.id
            )
            // const nextRow = rowsCopy.value[edit.value.rowId + direction]
            // if (nextRow !== undefined) {
            //     editRecord(
            //         edit.value.rowId + direction,
            //         props.columns.at(direction === 1 ? 0 : -1)?.id
            //     )
            // } else {
            //     if (direction === 1) {
            //         addRow()
            //     }
            // }
        }
    }
}
</script>

<style scoped>
.stripped {
    background: repeating-linear-gradient(
        0deg,
        #1f1e1e,
        #1f1e1e 22px,
        #2a2a2a 22px,
        #2a2a2a 44px
    );
    background-size: 100% 44px;
}
</style>
