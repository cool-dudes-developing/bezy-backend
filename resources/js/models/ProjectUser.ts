import { Model } from 'pinia-orm'
import { Attr, Str, Uid } from 'pinia-orm/dist/decorators'

export default class ProjectUser extends Model {
    static entity = 'project_users'

    static primaryKey = ['project_id', 'user_id']

    @Uid() declare id: string
    @Attr() declare project_id: string
    @Attr() declare user_id: string
}
