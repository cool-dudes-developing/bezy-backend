<template>
    <button
        :class="[
            {
                'bg-petronas text-dark transition ease-in-out hover:brightness-75 disabled:brightness-50':
                    variant === 'primary',
                'bg-dark text-petronas backdrop-brightness-75 hover:backdrop-brightness-100 ':
                    variant === 'secondary',
                'border-0 text-petronas': variant === 'tertiary',
            },
            removeDefaultStyles
                ? ''
                : {
                      'p-1 text-start font-button text-sm text-petronas underline':
                          variant === 'underline',
                      'rounded-2xl border border-petronas px-3 py-2 font-button transition duration-300':
                          ['primary', 'secondary', 'tertiary'].includes(
                              variant
                          ),
                  },
        ]"
        :disabled="disabled"
        :role="to ? 'link' : 'button'"
        :type="type"
        @click="onClick"
    >
        <slot />
    </button>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router'

const emit = defineEmits(['click'])

const props = withDefaults(
    defineProps<{
        type?: 'button' | 'submit' | 'reset'
        disabled?: boolean
        variant?: 'primary' | 'secondary' | 'tertiary' | 'underline'
        removeDefaultStyles?: boolean
        to?: string | { name: string }
    }>(),
    {
        type: 'button',
        disabled: false,
        variant: 'primary',
        removeDefaultStyles: false,
    }
)

const router = useRouter()

function onClick() {
    if (props.to) {
        router.push(props.to)
    } else {
        emit('click')
    }
}
</script>

<style scoped></style>
