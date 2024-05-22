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
  @Attr(0) declare downloads: string
  @Attr([]) declare versions: object[]
  @Attr({}) declare author: object
  @Attr({}) declare block: Block

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
}
