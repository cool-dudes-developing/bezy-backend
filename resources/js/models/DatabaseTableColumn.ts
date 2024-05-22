import { Model } from 'pinia-orm'
import { Attr, Str, Uid } from 'pinia-orm/dist/decorators'

export default class DatabaseTableColumn extends Model {
  static entity = 'database_table_columns'

  @Uid() declare id: string
  @Uid() declare table_id: string
  @Str('') declare name: string
  @Str('') declare type: string
  @Attr('') declare default: string
  @Attr(false) declare is_nullable: boolean
  @Str('') declare comment: string

}