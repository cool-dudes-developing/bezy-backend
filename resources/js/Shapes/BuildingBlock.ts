import { dia } from 'jointjs'
import { Model } from '@joint/decorators'
const selector = 'fill'
@Model({
  template: `
    <g class="bblock">
        <g class="scalable">
          <rect class="body" :fill="color" width="calc(0.5*w)" height="calc(h)"></rect>
          <text @selector="text">Hello world</text>
        </g>
    </g>`,
  attributes: {
    color: 'green',
    name: {
      text: 'Building Block',
      fill: 'black',
      fontSize: 12,
      fontWeight: 'bold',
      fontFamily: 'Arial, helvetica, sans-serif'
    },
    attrs: {
      text: {
        textWrap: {
          maxLineCount: 2,
          ellipsis: true
        }
      }
    }
  }
})
export default class BuildingBlock extends dia.Element {}
