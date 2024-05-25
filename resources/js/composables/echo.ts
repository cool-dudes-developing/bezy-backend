import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import { ref, unref } from 'vue'

const echo = ref<Echo | undefined>()

export function useEcho() {
    console.log(import.meta.env)
    console.log(localStorage.getItem('token'))
    if (!echo.value)
        echo.value = new Echo({
            broadcaster: 'pusher',
            client: new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                }
            }),
            encrypted: true,
        })

    const echoInstance = unref(echo)

    if (!echoInstance) throw new Error('Echo instance is not initialized')

    return {
        echo: echoInstance,
    }
}
