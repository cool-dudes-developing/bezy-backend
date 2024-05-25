<template>
    <ul class="divide-y divide-petronas/25 overflow-hidden rounded-xl bg-dark">
        <template v-if="items.length">
            <li
                v-for="item in items"
                :class="[
                    item.id === selected ? 'bg-sec' : 'hover:bg-sec/50',
                    {
                        'cursor-pointer': pointer,
                    },
                ]"
                class="flex justify-between px-2 first:pt-1 last:pb-1 cursor-pointer"
                @click="emit('select', item)"
            >
                <slot
                    :item="item"
                    name="item"
                />
                <slot
                    :on-delete="() => deleteItem(item)"
                    :item="item"
                    name="actions"
                >
                    <div class="flex items-center gap-1">
                        <slot
                            :item="item"
                            name="additional-actions"
                        />
                        <button
                            v-if="deleteEnabled"
                            class="shrink-0"
                            @click.prevent.stop="deleteItem(item)"
                        >
                            <svg-icon
                                class="h-4 w-4"
                                name="trashcan"
                            />
                        </button>
                    </div>
                </slot>
            </li>
        </template>
        <li
            v-else
            class="p-1"
        >
            <slot name="empty" />
        </li>
    </ul>
</template>

<script lang="ts" setup>
import useModal from '@/plugins/modal'
import DialogPopup from '@/components/modals/DialogPopup.vue'

const emit = defineEmits(['delete', 'select'])

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    selected: {
        type: [String, Number],
        default: null,
    },
    pointer: {
        type: Boolean,
        default: false,
    },
    deleteEnabled: {
        type: Boolean,
        default: true,
    },
})

const modal = useModal()

function deleteItem(item) {
    modal.show(
        DialogPopup,
        {
            type: 'full',
            variant: 'danger',
            title: 'Delete item',
            message: 'Are you sure you want to delete this item?',
        },
        {
            onPrimary: (payload, close, modal) => {
                emit('delete', item)
                close()
            },
            onSecondary: (payload, close, modal) => {
                close()
            },
        }
    )
}
</script>

<style scoped></style>
