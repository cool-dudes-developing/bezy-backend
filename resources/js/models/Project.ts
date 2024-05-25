import { Model, useRepo } from 'pinia-orm'
import {
    Attr,
    BelongsToMany, Bool,
    HasMany,
    Str,
    Uid,
} from 'pinia-orm/dist/decorators'
import Method from './Method'
import * as api from '@/utils/api'
import User from '@/models/User'
import ProjectUser from '@/models/ProjectUser'

export default class Project extends Model {
    static entity = 'projects'

    @Uid() declare id: string
    @Attr('') declare user_id: string
    @Str('') declare slug: string
    @Str('') declare name: string
    @Str('') declare description: string
    @Str('') declare uri: string
    @Str('') declare role: string
    @Bool(false) declare can_edit: boolean
    @Bool(false) declare is_accepted: boolean
    @Str('') declare created_at: string
    @Str('') declare updated_at: string
    @Str('') declare deleted_at: string
    @HasMany(() => Method, 'project_id', 'id') declare methods: Method[]
    @BelongsToMany(() => User, () => ProjectUser, 'project_id', 'user_id')
    declare members: User[]

    static fetchAll() {
        return api.get('/projects').then((response) => {
            console.log(response.data)
            useRepo(Project).save(response.data.data)
        })
    }

    static fetch(id: string) {
        return api.get(`/projects/${id}`).then((response) => {
            useRepo(Project).save(response.data.data)
        })
    }

    static store(data: any) {
        return api.post('/projects', data).then((response) => {
            useRepo(Project).save(response.data.data)
        })
    }

    static destroy(id: string) {
        return api.del(`/projects/${id}`).then((response) => {
            useRepo(Project).destroy(id)
        })
    }

    get endpoints() {
        return this.methods.filter((method) => method.type === 'endpoint')
    }

    static changeRole(project: string, user: string, role: string) {
        return api
            .put(`/projects/${project}/members/${user}`, { role: role })
            .then((response) => {
                useRepo(Project).save(response.data.data)
            })
    }

    static removeMember(project: string, user: string) {
        return api
            .del(`/projects/${project}/members/${user}`)
            .then((response) => {
                useRepo(ProjectUser)
                    .where('project_id', project)
                    .where('user_id', user)
                    .delete()
            })
    }

    static invite(project: string, email: string, role: string) {
        return api
            .post(`/projects/${project}/members`, { email, role })
            .then((response) => {
                useRepo(Project).save(response.data.data)
            })
    }

    static acceptInvite(project: string) {
        return api
            .put(`/projects/${project}/accept`)
            .then((response) => {
                useRepo(Project).save(response.data.data)
            })
    }

    static declineInvite(project: string) {
        return api
            .put(`/projects/${project}/reject`)
            .then((response) => {
                useRepo(Project).save(response.data.data)
            })
    }
}
