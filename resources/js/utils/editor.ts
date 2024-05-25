import type Port from '@/models/Port'
import { dia, linkTools, shapes } from 'jointjs'
import { HighlightFrame } from '@/models/Shapes'
import _ from 'lodash'

export const editorLinkTools = new dia.ToolsView({
    tools: [
        new linkTools.Remove({
            distance: '90%',
            markup: [
                {
                    tagName: 'circle',
                    selector: 'button',
                    attributes: {
                        r: 10,
                        fill: '#f6f6f6',
                        stroke: '#ff5148',
                        'stroke-width': 2,
                        cursor: 'pointer',
                    },
                },
                {
                    tagName: 'path',
                    selector: 'icon',
                    attributes: {
                        d: 'M -6 -6 6 6 M -6 6 6 -6',
                        fill: 'none',
                        stroke: '#ff5148',
                        'stroke-width': 2,
                        'pointer-events': 'none',
                    },
                },
            ],
        }),
    ],
})

export const editorCustomLink = new shapes.standard.DoubleLink({
    z: -1,
    attrs: {
        line: {
            stroke: '#fff',
            strokeWidth: 14,
            markerEnd: null,
            targetMarker: null,
        },
        outline: {
            strokeWidth: 18,
        },
    },
    labels: [],
})

export const cursorShape = new shapes.standard.Rectangle({
    size: {
        width: 24,
        height: 24,
    },
    markup: [
        {
            tagName: 'path',
            selector: 'cursor',
        },
        {
            tagName: 'rect',
            selector: 'body',
        },
        {
            tagName: 'text',
            selector: 'label',
        },
        {
            tagName: 'rect',
            selector: 'message-rect'
        },
        {
            tagName: 'text',
            selector: 'message-text'
        }
    ],
    attrs: {
        cursor: {
            fill: 'transparent',
            stroke: '#f00',
            'stroke-width': 2,
            d: 'm4 4 7.07 17 2.51-7.39L21 11.07z',
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
        },
        body: {
            fill: 'red',
            strokeWidth: 0,
            x: 15,
            y: 24,
        },
        label: {
            text: 'Name',
            fill: 'white',
            'font-size': 12,
            'font-weight': 'bold',
            'font-variant': 'small-caps',
            refY: 36,
            refX: 20,
            'text-anchor': 'left',
        },
        'message-rect': {
            fill: 'red',
            width: 125,
            height: 20,
            x: 15,
            y: 48,
        },
        'message-text': {
            text: 'Really long messagagagagagagagagegegegege',
            fill: 'white',
            'font-size': 12,
            textWrap: {
                width: 100,
                height: null,
            },
            refY: 52,
            refX: 20,
            'text-anchor': 'left',
        }
    },
})

export function styleLink(
    link: dia.Link,
    port: Port,
    secondPort: Port | null = null
) {
    // color link based on port type
    const type = secondPort
        ? port.type === 'any'
            ? secondPort.type
            : port.type
        : port.type
    switch (type) {
        case 'flow':
            link.attr('line/stroke', '#E06C75')
            break
        case 'number':
            link.attr('line/stroke', '#61AFEF')
            break
        case 'string':
            link.attr('line/stroke', '#C678DD')
            break
        case 'boolean':
            link.attr('line/stroke', '#98C379')
            break
        case 'object':
            link.attr('line/stroke', '#E5C07B')
            break
        case 'array':
            link.attr('line/stroke', '#56B6C2')
            break
    }
    // add label
    link.appendLabel({
        attrs: {
            text: {
                text: type.split('<')[0],
                fontFamily: 'monospace',
                fontSize: 12,
                fontWeight: 'bold',
                'font-variant': 'small-caps',
            },
            rect: {
                fillOpacity: 0.9,
                fill: 'transparent',
            },
        },
        position: {
            args: {
                keepGradient: true,
                ensureLegibility: true,
            },
        },
    })
}

export function offsetToLocalPoint(
    offsetX: number,
    offsetY: number,
    paper: dia.Paper
) {
    const svgPoint = paper.svg.createSVGPoint()
    svgPoint.x = offsetX
    svgPoint.y = offsetY
    return svgPoint.matrixTransform(paper.viewport.getCTM()!.inverse())
}

export function selectElement(elementView: dia.ElementView) {
    HighlightFrame.add(elementView, 'root', 'frame', { padding: 10 })
    elementView.addTools(
        new dia.ToolsView({
            tools: [
                new linkTools.Remove({
                    scale: 1.5,
                    x: '100%',
                    y: '-10%',
                }),
            ],
        })
    )
}

export function unselectElement(elementView: dia.ElementView) {
    HighlightFrame.remove(elementView, 'frame')
    elementView.removeTools()
}

export function validateConnection(
    cellViewS: dia.CellView,
    magnetS: SVGElement,
    cellViewT: dia.CellView,
    magnetT: SVGElement,
    endView: dia.LinkEnd,
    linkView: dia.LinkView
) {
    // Prevent linking from input ports
    if (magnetS && magnetS.getAttribute('port-group') === 'in') return false

    // Prevent linking from output ports to input ports within one element
    if (cellViewS === cellViewT) return false

    // Prevent linking if input port is already connected
    if (magnetT && magnetT.getAttribute('port-group') === 'in') {
        // target port id
        const targetPortId = magnetT.getAttribute('port')

        // target connected links
        const graph = cellViewT.model.graph
        const links = graph.getConnectedLinks(cellViewT.model, {
            inbound: true,
        }) as dia.Link[]

        // console.log(links)
        // check if any of the links has the same target port
        // if (links.find((link) => link.get('target').port === targetPortId))
        //     return false

        // check if type of source port is the same as target port
        const sourcePortId = magnetS.getAttribute('port')
        const targetPort = cellViewT.model
            .get('ports')
            .items.find(
                (port: dia.Element.Port) => port.id === targetPortId
            ) as dia.Element.Port
        const sourcePort = cellViewS.model
            .get('ports')
            .items.find(
                (port: dia.Element.Port) => port.id === sourcePortId
            ) as dia.Element.Port

        // check if ports exist in storage
        if (!sourcePort || !targetPort) return false

        let sourceType = sourcePort.args?.type
        let targetType = targetPort.args?.type

        // remove generic types
        if (sourceType?.includes('<') && sourceType?.includes('>')) {
            sourceType = sourceType.split('<')[0]
        }
        if (targetType?.includes('<') && targetType?.includes('>')) {
            targetType = targetType.split('<')[0]
        }

        // check if types are compatible
        if (sourceType !== 'flow' && targetType !== 'flow') {
            if (sourceType === 'any' || targetType === 'any') return true
        }
        if (sourceType !== targetType) return false

        return true
    }
    // Prevent linking to output ports
    return magnetT && magnetT.getAttribute('port-group') === 'in'
}

export function validateMagnet(cellView: dia.CellView, magnet: SVGElement) {
    // Prevent links from ports that already have a link
    const port = magnet.getAttribute('port')
    const portType = cellView.model.attributes.ports.items.find(
        (p) => p.id === port
    ).args.type
    console.log(portType)
    const graph = cellView.model.graph
    const links = graph.getConnectedLinks(cellView.model, {
        outbound: true,
    })

    const portLinks = _.filter(links, function (o) {
        return (
            (o.get('source').port == port &&
                o.get('source').id == cellView.model.id) ||
            (o.get('target').port == port &&
                o.get('target').id == cellView.model.id)
        )
    })
    if (portLinks.length > 0) {
        if (portType === 'flow') {
            console.log('port already connected', portLinks)
            return false
        }
    }

    // Note that this is the default behaviour. Just showing it here for reference.
    // Disable linking interaction for magnets marked as passive (see below `.inPorts circle`).
    return magnet.getAttribute('magnet') !== 'passive'
}
