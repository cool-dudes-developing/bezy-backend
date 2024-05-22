import { Model, useRepo } from 'pinia-orm'
import { Attr, HasMany, Str, Uid } from 'pinia-orm/dist/decorators'
import * as api from '@/utils/api'
import DatabaseTableColumn from '@/models/DatabaseTableColumn'

export default class DatabaseTable extends Model {
    static entity = 'database_tables'

    @Uid() declare id: string
    @Uid() declare project_id: string
    @Str('') declare name: string
    @Attr(0) declare rows_count: number
    @Attr([]) declare rows: any[]

    @HasMany(() => DatabaseTableColumn, 'table_id', 'id')
    declare columns: DatabaseTableColumn[]

    static fetchAll(project_id: string) {
        return api
            .get('/projects/' + project_id + '/tables')
            .then((response) => {
                useRepo(DatabaseTable).save(response.data.data)
            })
    }

    static fetch(project_id: string, id: string) {
        return api
            .get('/projects/' + project_id + '/tables/' + id)
            .then((response) => {
                useRepo(DatabaseTable).save(response.data.data)
            })
    }

    static store(project_id: string, data: object) {
        return api
            .post(`/projects/${project_id}/tables`, data)
            .then((response) => {
                useRepo(DatabaseTable).save(response.data.data)
            })
    }

    static update(project_id: string, id: string, data: object) {
        return api
            .put(`/projects/${project_id}/tables/${id}`, data)
            .then((response) => {
                useRepo(DatabaseTable).save(response.data.data)
            })
    }

    static destroy(project_id: string, id: string) {
        return api
            .del(`/projects/${project_id}/tables/${id}`)
            .then((response) => {
                useRepo(DatabaseTable).remove(id)
            })
    }

    public fetchRows() {
        return api.get('/tables/' + this.id + '/rows').then((response) => {
            console.log(response.data.data)
            useRepo(DatabaseTable).save({
                id: this.id,
                rows: response.data.data,
            })
        })
    }

    get primary_id() {
        return this.columns.find((column) => column.name === 'id')?.id
    }
}
