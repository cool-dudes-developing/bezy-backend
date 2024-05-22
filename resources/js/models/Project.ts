import { Model, useRepo } from 'pinia-orm'
import { Attr, HasMany, Str, Uid } from 'pinia-orm/dist/decorators'
import Endpoint from './Endpoint'
import Method from './Method'
import * as api from '@/utils/api'

export default class Project extends Model {
  static entity = 'projects'

  @Uid() declare id: string
  @Attr('') declare user_id: string
  @Str('') declare slug: string
  @Str('') declare name: string
  @Str('') declare description: string
  @Str('') declare uri: string
  @Str('') declare created_at: string
  @Str('') declare updated_at: string
  @HasMany(() => Method, 'project_id', 'id') declare methods: Method[]

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
}
