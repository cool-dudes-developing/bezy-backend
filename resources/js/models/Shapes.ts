import { dia, shapes } from 'jointjs'
import DoubleLinkAttributes = shapes.standard.DoubleLinkAttributes

const portDefaults = {
    label: {
        position: {
            name: 'inside',
            args: {
                offset: 20,
                y: -2,
            },
        },
    },
    size: { width: 20, height: 20 },
    attrs: {
        portLabel: {
            fontFamily: 'monospace',
            fontSize: 12,
            pointerEvents: 'none',
            textVerticalAnchor: 'middle',
        },
        portBody: {
            d: 'M 0 -calc(0.5 * h) a calc(0.5 * h) calc(0.5 * h) 0 1 1 0 calc(h) a calc(0.5 * h) calc(0.5 * h) 0 1 1 0 -calc(h) Z',
            magnet: 'active',
        },
    },
}

export class Shape extends dia.Element {
    defaults() {
        return {
            ...super.defaults,
            type: 'Shape',
            size: { width: 200, height: 140 },
            markup: [
{
                    tagName: 'rect',
                    selector: 'body',
                },
                {
                    tagName: 'rect',
                    selector: 'background',
                },
                {
                    tagName: 'text',
                    selector: 'label',
                },
                {
                    tagName: 'text',
                    selector: 'constant',
                },
            ],
            attrs: {
                body: {
                    stroke: '#40444b',
                    fill: 'transparent',
                    strokeWidth: 4,
                    width: 'calc(w)',
                    height: 'calc(h)',
                },
                background: {
                    fill: '#2f3136',
                    width: 'calc(w)',
                    height: 'calc(h)',
                },
                label: {
                    x: 'calc(0.5 * w)',
                    y: '10',
                    textAnchor: 'middle',
                    textVerticalAnchor: 'middle',
                    fontWeight: 'bold',
                    fontSize: 13,
                    fontFamily: 'sans-serif',
                    fill: '#fafafa',
                },
                constant: {
                    x: 'calc(0.5 * w)',
                    y: 'calc(h - 10)',
                    textAnchor: 'middle',
                    textVerticalAnchor: 'middle',
                    fontSize: 10,
                    fontFamily: 'sans-serif',
                    fill: '#bff89b',
                }
            },
            portMarkup: [
                {
                    tagName: 'path',
                    selector: 'portBody',
                    attributes: {
                        stroke: '#fff',
                        fill: '#353648',
                        'stroke-width': 4,
                    },
                },
            ],
            portLabelMarkup: [
                {
                    tagName: 'rect',
                    selector: 'portLabelBackground',
                },
                {
                    tagName: 'text',
                    selector: 'portLabel',
                    attributes: {
                        fill: '#fafafa',
                    },
                },
            ],
            ports: {
                groups: {
                    in: {
                        ...portDefaults,
                        position: {
                            name: 'left',
                        },
                    },
                    out: {
                        ...portDefaults,
                        position: {
                            name: 'right',
                        },
                    },
                },
            },
        }
    }

    preinitialize() {
        this.markup = [
            {
                tagName: 'rect',
                selector: 'body',
                attributes: {},
            },
            {
                tagName: 'rect',
                selector: 'background',
                attributes: {
                    fill: 'red',
                },
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: '#fff',
                },
            },
        ]
    }
}

export const HighlightFrame = dia.HighlighterView.extend({
    tagName: 'path',
    attributes: {
        stroke: '#53D8C5',
        'stroke-width': 2,
        fill: 'none',
        'pointer-events': 'none',
    },
    // Method called to highlight a CellView
    highlight({ model }) {
        const { padding = 0, bevel = 10 } = this.options
        const bbox = model.getBBox()
        // Highlighter is always rendered relatively to the CellView origin
        bbox.x = bbox.y = 0
        // Increase the size of the highlighter
        bbox.inflate(padding)
        const { x, y, width, height } = bbox
        this.vel.attr(
            'd',
            `
              M ${x} ${y + bevel}
              L ${x} ${y + height - bevel}
              L ${x + bevel} ${y + height}
              L ${x + width - bevel} ${y + height}
              L ${x + width} ${y + height - bevel}
              L ${x + width} ${y + bevel}
              L ${x + width - bevel} ${y}
              L ${x + bevel} ${y}
              Z
          `
        )
    },
})

export class CustomLink extends dia.Link {
    defaults() {
        return {
            ...super.defaults,
            attrs: {
                line: {
                    stroke: '#fff',
                    strokeWidth: 14,
                    targetMarker: null,
                },
                outline: {
                    strokeWidth: 18,
                },
            },
            labels: [
                {
                    attrs: {
                        text: {
                            text: `type goes here`,
                            fontFamily: 'sans-serif',
                            fontSize: 10,
                        },
                        rect: {
                            fillOpacity: 0.9,
                        },
                    },
                    position: {
                        args: {
                            keepGradient: true,
                            ensureLegibility: true,
                        },
                    },
                },
            ],
        }
    }
}
