import { Model, useRepo } from 'pinia-orm'
import { Attr, Str, Uid } from 'pinia-orm/dist/decorators'
import * as api from '@/utils/api'
import type Block from '@/models/Block'

export default class MarketplaceAsset extends Model {
    static entity = 'marketplace_assets'

    @Uid() declare id: string
    @Uid() declare author_id: string
    @Str('') declare name: string
    @Str('') declare description: string
    @Str('') declare caption: string
    @Attr(false) declare is_liked: boolean
    @Attr(0) declare usages: number
    @Attr(0) declare likes: number
    @Attr({}) declare author: object
    @Attr({}) declare block: Block
    @Attr([]) declare tags: string[]

    static fetchAll() {
        return api.get('/assets').then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }

    static fetch(id: string) {
        return api.get('/assets/' + id).then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }

    static fetchRecent() {
        return api.get('/assets/recent').then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }

    static update(id: string, data: object) {
        return api.put('/assets/' + id, data).then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }

    static like(id: string) {
        return api.post('/assets/' + id + '/like').then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }

    static fetchLiked() {
        return api.get('/assets/liked').then((response) => {
            useRepo(MarketplaceAsset).save(response.data.data)
        })
    }
}
