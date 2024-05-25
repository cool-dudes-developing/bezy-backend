import { Model, useRepo } from 'pinia-orm'
import {
    Attr,
    HasMany,
    HasManyBy,
    BelongsToMany,
    Num,
    Str,
    Uid,
} from 'pinia-orm/dist/decorators'
import Port from './Port'
import * as api from '@/utils/api'
import { Shape } from './Shapes'
import MarketplaceAsset from '@/models/MarketplaceAsset'
import { dia } from 'jointjs'
import Connection from '@/models/Connection'
import BlockPort from '@/models/BlockPort'

export default class Block extends Model {
    static entity = 'blocks'

    @Uid() declare id: string
    @Attr(null) declare method_id: string | null
    @Attr(null) declare block_id: string | null
    @Str('') declare name: string
    @Str('') declare title: string
    @Str('') declare type: string
    @Str('') declare description: string
    @Str('') declare category: string
    @Str('') declare constant: string
    @Num(0) declare x: number
    @Num(0) declare y: number
    @Attr([]) declare outPortIds: string[]
    @Attr([]) declare inPortIds: string[]
    @BelongsToMany(() => Port, () => BlockPort, 'block_id', 'port_id')
    declare ports: Port[]
    @HasManyBy(() => Port, 'outPortIds') declare outPorts: Port[]
    @HasManyBy(() => Port, 'inPortIds') declare inPorts: Port[]
    @HasMany(() => Connection, 'from_method_block_id', 'id')
    declare outboundConnections: Connection[]
    @HasMany(() => Connection, 'to_method_block_id', 'id')
    declare inboundConnections: Connection[]

    static fetchAllTemplates() {
        return api.get('/blocks/templates').then((response) => {
            useRepo(Block).save(response.data.data.blocks)
            useRepo(MarketplaceAsset).save(response.data.data.assets)
        })
    }

    static save(block: Block) {
        return api.put(
            '/methods/' +
                useRepo(Block).find(block.id)?.method_id +
                '/blocks/' +
                block.id,
            block
        ).then((res) => {
            useRepo(Block).save(res.data.data)
        })
    }

    static destroy(id: string) {
        return api
            .del(
                '/methods/' +
                    useRepo(Block).find(id)?.method_id +
                    '/blocks/' +
                    id
            )
            .then(() => {
                useRepo(Block).destroy(id)
            })
    }

    static portSort(a: Port, b: Port) {
        if (a.type === 'flow' && b.type === 'flow') {
            return a.created_at - b.created_at
        } else if (a.type === 'flow') {
            return -1
        } else if (b.type === 'flow') {
            return 1
        } else {
            return a.created_at - b.created_at
        }
    }

    addPort(port: Port) {
        useRepo(Port).save({
            ...port,
            block_id: this.id,
        })
        useRepo(BlockPort).save({
            block_id: this.id,
            port_id: port.id,
        })

        const outPortIds = this.outPortIds
        const inPortIds = this.inPortIds

        let direction = port.direction

        // if this is a start or end block, we need to flip the direction
        if (this.type === 'start' || this.type === 'end')
            direction = direction === 'in' ? 'out' : 'in'

        if (direction === 'in') {
            console.log('in')
            inPortIds.push(port.id)
        } else {
            console.log('out')
            outPortIds.push(port.id)
        }

        useRepo(Block).save({
            id: this.id,
            inPortIds,
            outPortIds,
        })
    }

    get superCategory() {
        return this.category?.split('/').pop()
    }

    get categoryGroup() {
        return this.category?.split('/').shift()
    }

    get longestPortName() {
        return Math.max(...this.ports.map((port: Port) => port.name.length))
    }

    get longsetString() {
        return Math.max(this.name.length, this.longestPortName, this.constant.length * 0.6)
    }

    get width() {
        return Math.max(150, this.longsetString * 8 + 20)
    }

    get height() {
        return Math.max(
            50 + this.inPorts.length * 30,
            50 + this.outPorts.length * 30
        )
    }

    get buildingShape() {
        return new Shape({
            id: this.id,
            size: {
                width: this.width,
                height: this.height,
            },
            position: {
                x: this.x,
                y: this.y,
            },
            attrs: {
                label: {
                    text: this.title,
                },
                constant: {
                    text: this.constant,
                },
            },
            ports: {
                items: [
                    ...this.outPorts.sort(Block.portSort).map(
                        (port: Port) =>
                            ({
                                id: port.id,
                                group: 'out',
                                args: {
                                    type: port.type,
                                },
                                attrs: {
                                    portLabel: {
                                        text:
                                            this.type === 'start' &&
                                            port.type === 'flow'
                                                ? 'Out'
                                                : port.name,
                                    },
                                },
                            }) as dia.Element.Port
                    ),
                    ...this.inPorts.sort(Block.portSort).map(
                        (port: Port) =>
                            ({
                                id: port.id,
                                group: 'in',
                                args: {
                                    type: port.type,
                                },
                                attrs: {
                                    portLabel: {
                                        text:
                                            this.type === 'end' &&
                                            port.type === 'flow'
                                                ? 'In'
                                                : port.name,
                                    },
                                },
                            }) as dia.Element.Port
                    ),
                ],
            },
        })
    }
}
