import * as joint from 'jointjs'

export class CustomShape extends joint.shapes.standard.Rectangle {
  constructor(attributes?: any, options?: any) {
    super(attributes, options)
    this.initialize()
  }

  initialize() {
    this.resize(150, 100)
    this.attr({
      body: {
        fill: 'white',
        stroke: 'black',
        strokeWidth: 2,
        rx: 5,
        ry: 5
      },
      label: {
        text: 'Flowchart Shape',
        fill: 'black',
        fontSize: 12,
        fontWeight: 'bold',
        fontFamily: 'Arial, helvetica, sans-serif'
      }
    })
  }

  getHeaderHeight(): number {
    return 20
  }

  getInputPorts(): any[] {
    return [
      { id: 'in1', group: 'in', args: { x: 0, y: 50 } },
      { id: 'in2', group: 'in', args: { x: 0, y: 70 } }
    ]
  }

  getOutputPorts(): any[] {
    return [
      { id: 'out1', group: 'out', args: { x: 150, y: 50 } },
      { id: 'out2', group: 'out', args: { x: 150, y: 70 } }
    ]
  }

  getPortMarkup(port: any): string {
    return `
      <g class="port${port.id}">
        <circle class="port-body" r="6" />
        <text class="port-label">${port.id}</text>
      </g>
    `
  }

  getMarkup(): string {
    const headerHeight = this.getHeaderHeight()
    return `
      <g class="rotatable">
        <g class="scalable">
          <rect class="body" />
          <text class="label" />
        </g>
        <rect class="header" height="${headerHeight}" />
        <g class="in-ports">
          ${this.getInputPorts()
            .map((port) => this.getPortMarkup(port))
            .join('')}
        </g>
        <g class="out-ports">
          ${this.getOutputPorts()
            .map((port) => this.getPortMarkup(port))
            .join('')}
        </g>
      </g>
    `
  }
}
