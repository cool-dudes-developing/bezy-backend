import { Model, useRepo } from 'pinia-orm'
import { Str, Uid } from 'pinia-orm/dist/decorators'
import * as api from '@/utils/api'

export default class Endpoint extends Model {
  static entity = 'endpoints'

  @Uid() declare id: string
  @Uid() declare project_id: string
  @Str('') declare name: string
  @Str('') declare uri: string
  @Str('') declare method: string

  static fetchAll(project_id: string) {
    return api.get('/projects/' + project_id + '/endpoints/').then((response) => {
      useRepo(Endpoint).save(response.data.data)
    })
  }
}
