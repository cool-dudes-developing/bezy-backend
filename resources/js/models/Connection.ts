import { Model, useRepo } from 'pinia-orm'
import { Attr, BelongsTo, Uid } from 'pinia-orm/dist/decorators'
import Block from './Block'
import Port from './Port'
import type Method from './Method'
import * as api from '@/utils/api'

export default class Connection extends Model {
  static entity = 'connections'

  @Uid() declare id: string
  @Attr('') declare method_id: string
  @Attr('') declare from_method_block_id: string
  @Attr('') declare to_method_block_id: string
  @Attr('') declare from_port_id: string
  @Attr('') declare to_port_id: string

  @BelongsTo(() => Block, 'method_id', 'id') declare method: Method
  @BelongsTo(() => Block, 'from_method_block_id', 'id') declare from_method_block: Block
  @BelongsTo(() => Block, 'to_method_block_id', 'id') declare to_method_block: Block
  @BelongsTo(() => Port, 'from_port_id', 'id') declare from_port: Port
  @BelongsTo(() => Port, 'to_port_id', 'id') declare to_port: Port

  static store(
    method_id: string,
    from_method_block_id: string,
    to_method_block_id: string,
    from_port_id: string,
    to_port_id: string
  ) {
    return api
      .post('/methods/' + method_id + '/connections', {
        from_method_block_id: from_method_block_id,
        to_method_block_id: to_method_block_id,
        from_port_id: from_port_id,
        to_port_id: to_port_id
      })
      .then((response) => {
        return useRepo(Connection).save(response.data.data) as unknown as Connection
      })
  }

  static destroy(id: string) {
    return api
      .del('/methods/' + useRepo(Connection).find(id)?.method_id + '/connections/' + id)
      .then(() => {
        useRepo(Connection).destroy(id)
      })
  }
}
