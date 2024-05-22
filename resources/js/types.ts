import type { Ref } from 'vue'

export interface SpinnerInject {
    visible: Ref<boolean>

    show(): void

    hide(): void
}
