import { Model } from 'pinia-orm'
import { Attr, BelongsTo, Str, Uid } from 'pinia-orm/dist/decorators'
import Block from './Block'

export default class Port extends Model {
  static entity = 'ports'

  @Uid() declare id: string
  @Uid() declare block_id: string
  @Str('') declare name: string
  @Str('') declare type: string
  @Str('') declare direction: string

  @BelongsTo(() => Block, 'block_id', 'id') declare block: Block
  @BelongsTo(() => Block, 'target_id', 'id') declare target?: Block
}
