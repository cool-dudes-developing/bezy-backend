import { type Component, inject } from 'vue'
import { type ModalEventCallback, modalInjectKey } from '@/plugins/modalPlugin'

export default function useModal() {
    const { show: showModal, close: closeModal } = inject(modalInjectKey)!!

    return {
        show: (
            component: Component | string,
            props: Record<string, any> = {},
            events: { [key: string]: ModalEventCallback } = {}
        ): string => {
            console.log(props)
            return showModal(component, props, events)
        },

        close: (id: string | undefined = undefined): void => {
            closeModal(id)
        },
    }
}
