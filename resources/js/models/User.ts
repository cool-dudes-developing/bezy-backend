import { Model, useRepo } from 'pinia-orm'
import { HasMany, Str, Uid } from 'pinia-orm/dist/decorators'
import Project from './Project'
import * as api from '@/utils/api'
import axios from 'axios'
import router from '@/router'

export default class User extends Model {
    static entity = 'users'

    @Uid() declare id: string
    @Str('') declare name: string
    @Str('') declare email: string
    @HasMany(() => Project, 'user_id', 'id') declare projects: Project[]

    static login(email: string, password: string) {
        return api.post('/auth/login', { email, password }).then((response) => {
            useRepo(User).save(response.data.user)

            const token = response.data.access_token
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

            localStorage.setItem('token', token)
            localStorage.setItem('user_id', response.data.user.id)
        })
    }

    static register(
        name: string,
        email: string,
        password: string,
        password_confirmation: string
    ) {
        return api
            .post('/auth/register', {
                name,
                email,
                password,
                password_confirmation,
            })
            .then((response) => {
                useRepo(User).save(response.data.user)
                axios.defaults.headers.common['Authorization'] =
                    `Bearer ${response.data.access_token}`
                localStorage.setItem('token', response.data.access_token)
                localStorage.setItem('user_id', response.data.user.id)
            })
    }

    static logout() {
        localStorage.removeItem('token')
        localStorage.removeItem('user_id')
        axios.defaults.headers.common['Authorization'] = ''
        router.push({ name: 'auth-home' })
    }

    static show(id: string) {
        return api.get(`/users/${id}`).then((response) => {
            useRepo(User).save(response.data)
        })
    }

    static loadCurrentUser() {
        if (User.isAuthorized()) {
            return api.get('/auth/user').then((response) => {
                useRepo(User).save(response.data)
            })
        }
        return Promise.resolve()
    }

    static isAuthorized() {
        return !!localStorage.getItem('token')
    }

    static get currentUser(): User | null {
        return useRepo(User).find(localStorage.getItem('user_id') || '')
    }
}
