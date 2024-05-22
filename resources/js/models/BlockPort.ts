import { Model } from 'pinia-orm'
import { Uid } from 'pinia-orm/dist/decorators'

export default class BlockPort extends Model {
    static entity = 'block_ports'

    static primaryKey = ['block_id', 'port_id']

    @Uid() declare block_id: string
    @Uid() declare port_id: string
}
