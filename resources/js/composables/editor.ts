import * as joint from 'jointjs'
import { dia } from 'jointjs'
import {
    cursorShape,
    editorCustomLink,
    editorLinkTools,
    offsetToLocalPoint,
    selectElement,
    styleLink,
    unselectElement,
    validateConnection,
    validateMagnet,
} from '@/utils/editor'
import { useRepo } from 'pinia-orm'
import Block from '@/models/Block'
import { computed, ref, watch } from 'vue'
import Port from '@/models/Port'
import Connection from '@/models/Connection'
import useModal from '@/plugins/modal'
import Method from '@/models/Method'
import { createEventHook } from '@vueuse/core'
import chroma from 'chroma-js'

type Position = {
    x: number
    y: number
}

export default function useEditor(methodId: string) {
    const graph = new joint.dia.Graph()
    let dragStartPosition: Position | null = null
    let elementDragStartPosition: Position | null = null
    let localMousePosition: Position | null = null
    let highlightElement: dia.ElementView | null = null
    const blockRepo = computed(() => useRepo(Block))
    const portRepo = computed(() => useRepo(Port))
    const method = computed(() =>
        useRepo(Method).withAllRecursive().find(methodId)
    )
    const blocks = computed(() =>
        blockRepo.value.where('method_id', methodId).withAllRecursive().get()
    )
    const modal = useModal()
    const blocksPopupPosition = ref<Position | null>(null)
    const blocksPopupOpen = computed(() => blocksPopupPosition.value !== null)
    const blocksPopupType = ref('')
    const showFitButton = ref(false)
    let silentRemove = false
    let listenChange = true
    const mouseMoveHook = createEventHook<Position>()
    const graphEventHook = createEventHook<
        Position & { id: string; type: string; options: any }
    >()
    const paperOffset = ref<Position>({
        x: 0,
        y: 0,
    })

    function movePaper(paper: dia.Paper, dx: number, dy: number) {
        paper.translate(dx, dy)
        const position = paper.clientToLocalPoint(0, 0)
        paperOffset.value = {
            x: -position.x,
            y: -position.y,
        }
        // check if any elements in view
        const elements = paper.model.getElements()
        if (elements.length) {
            if (paper.findViewsInArea(paper.getContentBBox()).length === 0) {
                // no elements in view
                showFitButton.value = true
            } else {
                showFitButton.value = false
            }
        }
    }

    function renderBlock(block: Block, skipLinks = false) {
        silentRemove = true
        const existingBlock = graph.getCell(block.id)
        if (existingBlock) {
            graph.removeLinks(existingBlock)
            existingBlock.remove()
        }
        graph.addCell(block.buildingShape.toBack())
        if (!skipLinks) {
            renderLinks(block.inboundConnections)
            renderLinks(block.outboundConnections)
        }
        silentRemove = false
    }

    function renderLinks(links: Connection[]) {
        links.forEach((connection: Connection) => {
            // check if source and target blocks exist
            if (
                graph.getCell(connection.from_method_block_id) &&
                graph.getCell(connection.to_method_block_id)
            ) {
                const link = editorCustomLink.clone()
                styleLink(link, connection.to_port, connection.from_port)
                link.target({
                    id: connection.to_method_block_id,
                    port: connection.to_port_id,
                })
                link.source({
                    id: connection.from_method_block_id,
                    port: connection.from_port_id,
                })
                graph.addCell(link)
                link.toBack()
            } else {
                console.log('Source or target block not found')
            }
        })
    }

    function clearLinks(links: Connection[]) {
        silentRemove = true
        links.forEach((connection: Connection) => {
            const link = graph.getLinks().find((link) => {
                const source = link.get('source')
                const target = link.get('target')
                return (
                    source.id === connection.from_method_block_id &&
                    target.id === connection.to_method_block_id &&
                    source.port === connection.from_port_id &&
                    target.port === connection.to_port_id
                )
            })
            if (link) link.remove()
        })
        silentRemove = false
    }

    watch(
        () => blocks.value,
        (blocks, oldBlocks) => {
            if (!listenChange) return
            console.log('Blocks changed')
            // check if blocks were added or removed
            if (blocks.length !== oldBlocks.length) {
                console.log('Blocks count changed')
                blocks.forEach((block) => {
                    const oldBlock = oldBlocks.find(
                        (oldBlock) => oldBlock.id === block.id
                    )
                    if (!oldBlock) {
                        console.log('Block added')
                        renderBlock(block)
                    }
                })
            }
            // check if block ports were added or removed
            blocks.forEach((block) => {
                const oldBlock = oldBlocks.find(
                    (oldBlock) => oldBlock.id === block.id
                )
                if (!oldBlock) return
                if (block.ports.length !== oldBlock.ports.length) {
                    console.log('Block ports count changed')
                    block.ports.forEach((port) => {
                        const oldPort = oldBlock.ports.find(
                            (oldPort) => oldPort.id === port.id
                        )
                        if (!oldPort) {
                            console.log('Port added')
                            renderBlock(block)
                        }
                    })
                }
                if (oldBlock.constant !== block.constant) {
                    console.log('Block constant changed')
                    renderBlock(block)
                }

                if (
                    block.inboundConnections.length !==
                    oldBlock.inboundConnections.length
                ) {
                    console.log('Inbound connections count changed')
                    clearLinks(block.inboundConnections)
                    renderLinks(block.inboundConnections)
                }

                if (
                    block.outboundConnections.length !==
                    oldBlock.outboundConnections.length
                ) {
                    console.log('Outbound connections count changed')
                    clearLinks(block.outboundConnections)
                    renderLinks(block.outboundConnections)
                }
            })
        }
    )

    function closePopup(except: string = '') {
        blocksPopupPosition.value = null
        // clean all unconnected links
        graph.getLinks().forEach((link) => {
            if (!link.target().id && link.id !== except) {
                link.remove()
            }
        })
    }

    function registerCursorEvents(paper: dia.Paper) {
        paper.on('blank:pointerdblclick', function (event) {
            if (blocksPopupOpen.value) {
                closePopup()
            } else {
                // const coords = offsetToLocalPoint(
                //     event.originalEvent.offsetX,
                //     event.originalEvent.offsetY,
                //     paper
                // )
                const coords = {
                    x: event.offsetX,
                    y: event.offsetY,
                }
                blocksPopupType.value = 'any'
                blocksPopupPosition.value = {
                    x: coords.x,
                    y: coords.y - 10,
                }
            }
        })
        paper.on('blank:pointerdown', function (event, x, y) {
            if (!paper) throw new Error('Paper not initialized')
            // we need to use the offset to local point function because x and y
            // are always related to grid size
            const coords = offsetToLocalPoint(
                event.originalEvent.offsetX,
                event.originalEvent.offsetY,
                paper
            )
            const scale = paper.scale().sx
            dragStartPosition = { x: coords.x * scale, y: coords.y * scale }
        })
        paper.on('cell:pointerup blank:pointerup', function () {
            dragStartPosition = null
        })
    }

    function registerLinkToolEvents(paper: dia.Paper) {
        paper.on('link:mouseenter', (linkView: dia.LinkView) => {
            // only if link is connected
            if (!linkView.model.source().id || !linkView.model.target().id)
                return
            linkView.addTools(editorLinkTools)
        })
        paper.on('link:mouseleave', (linkView: dia.LinkView) => {
            linkView.removeTools()
        })
    }

    function registerElementPointerEvents(paper: dia.Paper, graph: dia.Graph) {
        paper.on('element:pointerdown', (elementView) => {
            console.log('Element pointer down')

            if (elementView.model.prop('type') === 'cursor') {
                console.log('Cursor clicked')
                return
            }

            listenChange = false

            const position = elementView.model.getBBox()

            elementDragStartPosition = { x: position.x, y: position.y }
            console.log('Start drag')
        })
        paper.on('element:pointermove', (elementView) => {
            if (!elementDragStartPosition) return

            const position = elementView.model.getBBox()

            if (
                elementDragStartPosition.x !== position.x ||
                elementDragStartPosition.y !== position.y
            ) {
                graphEventHook.trigger({
                    x: position.x,
                    y: position.y,
                    id: elementView.model.id,
                    type: 'element-move',
                })
            }
        })
        paper.on('element:pointerup', (elementView) => {
            if (!elementDragStartPosition) return

            const position = elementView.model.getBBox()
            listenChange = true

            if (
                elementDragStartPosition.x === position.x &&
                elementDragStartPosition.y === position.y
            ) {
                console.log('Element not moved', elementView.model.id)
                if (highlightElement) {
                    if (highlightElement.model.id === elementView.model.id) {
                        console.log('Element already selected, deselecting')
                        unselectElement(elementView)
                        highlightElement = null
                    } else {
                        console.log('Another element selected, deselecting')
                        unselectElement(highlightElement)
                        highlightElement = null
                        selectElement(elementView)
                        highlightElement = elementView
                    }
                } else {
                    console.log('Selecting element')
                    selectElement(elementView)
                    highlightElement = elementView
                }
            } else {
                console.log('Element moved', position)
            }
            useRepo(Block).save({
                id: elementView.model.id,
                x: position.x,
                y: position.y,
            })
        })
        graph.on('change:position', function (cell: any) {
            // block position changed
            // reroute links
            // graph.getLinks().forEach((link) => {
            //     link.findView(paper).requestConnectionUpdate()
            // })
            const position = cell.getBBox()
        })
    }

    function registerElementDetailsEvents(paper: dia.Paper) {
        paper.on(
            'element:magnet:pointerdblclick',
            (magnetView, evt, magnetNode, x, y) => {
                evt.stopPropagation()
                // show modal popup to add and connect block
                const blockId = magnetView.model.id
                const portId = magnetNode.getAttribute('port')
                const portGroup = magnetNode.getAttribute('port-group')
                blocksPopupType.value =
                    magnetView.model.attributes.ports.items.find(
                        (p) => p.id === portId
                    )?.args.type

                const link = editorCustomLink.clone()
                link.source({
                    id: blockId,
                    port: portId,
                })
                const dx = portGroup === 'in' ? -100 : 100
                link.target({
                    x: x + dx,
                    y: y,
                })
                graph.addCell(link)
                blocksPopupPosition.value = {
                    x: evt.offsetX + dx,
                    y: evt.offsetY - 10,
                }
            }
        )
        paper.on('element:pointerdblclick', (elementView) => {
            const block = blocks.value.find(
                (block) => block.id === elementView.model.id
            )
            if (!block) return
            modal.show(
                'block-details',
                {
                    block: block,
                    method: method.value,
                },
                {
                    onClose: () => {
                        console.log('Block details closed')
                        listenChange = false
                        renderBlock(block)
                        listenChange = true
                    },
                }
            )

            // const asset = templateAssets.value.find((asset) => asset.block.id === block.block_id)
            // if (!asset) return
            // router.push({
            //   name: 'method',
            //   params: {
            //     project: route.value.params.project as string,
            //     method: block.block_id as string
            //   }
            // })
        })
    }

    function registerLinkHandlers(paper: dia.Paper, graph: dia.Graph) {
        paper.on('cell:pointerup blank:pointerup', function () {
            console.log('Pointer up')
            if (!blocksPopupOpen.value) {
                graph.getLinks().forEach((link) => {
                    if (!link.target().id) {
                        // link target not set, show modal to create new block
                        if (!localMousePosition)
                            throw new Error('Local mouse position not set')

                        blocksPopupType.value = graph
                            .getCell(link.source().id)
                            ?.attributes.ports.items.find(
                                (port) => port.id === link.source().port
                            )?.args.type

                        blocksPopupPosition.value = {
                            x: localMousePosition.x,
                            y: localMousePosition.y - 10,
                        }
                    }
                })
            }
        })
        graph.on('add', function (cell: dia.Cell) {
            if (blocksPopupOpen.value) closePopup(cell.id)
            if (cell.isLink()) {
                if (cell instanceof dia.Link) {
                    styleLink(
                        cell,
                        portRepo.value.find(cell.source().port as string)
                    )
                }
            }
        })
        graph.on('change:source change:target', function (link: any) {
            if (link.get('source').id && link.get('target').id) {
                // both ends of the link are connected.
                const source = link.get('source')
                const target = link.get('target')

                console.log(
                    `Link from ${source.id}:${source.port} to ${target.id}:${target.port} was created.`
                )

                const sourcePort = portRepo.value.find(source.port as string)
                const targetPort = portRepo.value.find(target.port as string)

                if (sourcePort && targetPort) {
                    Connection.store(
                        methodId,
                        source.id,
                        target.id,
                        source.port,
                        target.port
                    ).then((res) => {
                        graphEventHook.trigger({
                            x: target.x,
                            y: target.y,
                            id: res.id,
                            type: 'link',
                            options: res,
                        })
                    })
                }
            }
        })
    }

    function registerRemoveEvents(paper: dia.Paper, graph: dia.Graph) {
        graph.on('remove', function (cell: any) {
            if (silentRemove) return

            if (cell.isElement()) {
                void Block.destroy(cell.id).then(() => {
                    graphEventHook.trigger({
                        x: 0,
                        y: 0,
                        id: cell.id,
                        type: 'block:delete',
                    })
                })
            }

            if (cell.isLink()) {
                const source = cell.get('source')
                const target = cell.get('target')
                console.log(
                    `Link from ${source.id}:${source.port} to ${target.id}:${target.port} was removed.`
                )

                const sourcePort = portRepo.value.find(source.port as string)

                // // check if target id or target port is undefined
                // if (!target.id || !target.port) {
                //     console.log(localMousePosition)
                //
                //     if (!localMousePosition)
                //         throw new Error('Local mouse position not set')
                //     // Open modal at mouse position
                //     blocksModalPosition.value = {
                //         x: localMousePosition.x,
                //         y: localMousePosition.y,
                //     }
                //     blocksSearch.value = ''
                //     blocksModalOpen.value = true
                // }

                const targetPort = portRepo.value.find(target.port as string)
                if (targetPort && sourcePort) {
                    const connectionId = useRepo(Connection)
                        .where('from_method_block_id', source.id)
                        .where('to_method_block_id', target.id)
                        .where('from_port_id', source.port)
                        .where('to_port_id', target.port)
                        .first()?.id as string
                    Connection.destroy(connectionId).then((res) => {
                        graphEventHook.trigger({
                            id: connectionId,
                            type: 'link:delete',
                        })
                    })
                }
            }
        })
    }

    return {
        blocksPopupOpen,
        blocksPopupPosition,
        blocksPopupType,
        showFitButton,
        method,
        blocks,
        mouseMoveHook,
        graphEventHook,
        paperOffset,
        createPaper() {
            return new joint.dia.Paper({
                el: document.getElementById('container'),
                model: graph,
                async: true,
                // interactive: {
                //     labelMove: false,
                //     linkMove: false,
                // },
                interactive(cellView, event) {
                    console.log(cellView?.model?.prop('type'))
                    if (
                        ['cursor', 'standard.DoubleLink'].includes(
                            cellView?.model?.prop('type')
                        )
                    ) {
                        return false
                    }
                    // if this is a link label
                    console.log(cellView)
                    return true
                },

                // set paper dimensions
                width: '100%',
                height: '100%',

                // set grid size
                gridSize: 5,
                sorting: joint.dia.Paper.sorting.APPROX,
                drawGrid: {
                    name: 'mesh',
                    args: { color: '#f3dbf3', thickness: 0.1, scaleFactor: 5 },
                },

                // set paper styling
                background: {
                    color: '#1c2420',
                },

                // Prevent link being dropped in blank paper area
                // linkPinning: false,
                // Enable link snapping within 20px lookup radius
                snapLinks: { radius: 20 },
                snapLabels: true,
                // Mark all available magnets
                markAvailable: true,
                // Define default link
                defaultLink: editorCustomLink,
                //
                defaultConnectionPoint: { name: 'anchor' },
                defaultAnchor: (view, magnet, ...rest) => {
                    const group = view.findAttribute('port-group', magnet)
                    const anchorFn =
                        group === 'in'
                            ? joint.anchors.left
                            : joint.anchors.right
                    return anchorFn(view, magnet, ...rest)
                },
                defaultConnector: {
                    name: 'curve',
                    args: {
                        sourceDirection:
                            joint.connectors.curve.TangentDirections.RIGHT,
                        targetDirection:
                            joint.connectors.curve.TangentDirections.LEFT,
                    },
                },
                defaultRouter: {
                    name: 'normal',
                    args: {
                        padding: 100,
                    },
                },

                clickThreshold: 10,
                magnetThreshold: 'onleave',

                highlighting: {
                    connecting: {
                        name: 'mask',
                        options: {
                            layer: joint.dia.Paper.Layers.BACK,
                            attrs: {
                                stroke: '#0057FF',
                                'stroke-width': 3,
                            },
                        },
                    },
                },

                // Controls which link connections can be made
                validateConnection: validateConnection,

                // decide whether to create a link if the user clicks a magnet
                validateMagnet: validateMagnet,
            })
        },
        initializeElements() {
            blocks.value.forEach((block) => renderBlock(block, true))
            if (blocks.value.length !== graph.getElements().length) {
                console.error('Building blocks count mismatch')
            }
            renderLinks(method.value?.connections || [])
        },
        registerEvents(paper: dia.Paper) {
            registerCursorEvents(paper)
            registerLinkToolEvents(paper)
            registerElementPointerEvents(paper, graph)
            registerElementDetailsEvents(paper)
            registerLinkHandlers(paper, graph)
            registerRemoveEvents(paper, graph)
        },
        handleMouseMove(e: MouseEvent, paper: dia.Paper) {
            localMousePosition = { x: e.offsetX, y: e.offsetY }
            const relativePosition = offsetToLocalPoint(
                e.offsetX,
                e.offsetY,
                paper
            )
            void mouseMoveHook.trigger({
                x: relativePosition.x,
                y: relativePosition.y,
            })
            if (dragStartPosition) {
                const dx = e.offsetX - dragStartPosition.x
                const dy = e.offsetY - dragStartPosition.y

                movePaper(paper, dx, dy)

                if (blocksPopupOpen.value) {
                    blocksPopupPosition.value = {
                        x: blocksPopupPosition.value!.x + e.movementX,
                        y: blocksPopupPosition.value!.y + e.movementY,
                    }
                }
            }
        },
        handleWheelMove(e: WheelEvent, paper: dia.Paper) {
            if (!paper?.options?.origin) return

            if (e.ctrlKey) {
                const delta = Math.min(Math.max(e.wheelDelta, -1), 1) / 100
                const scale = paper.scale().sx + delta

                if (scale <= 0.25 || scale >= 2) return

                const p = offsetToLocalPoint(e.offsetX, e.offsetY, paper)

                const oldscale = paper.scale().sx

                const factor = oldscale / scale

                const ax = p.x - p.x * factor
                const ay = p.y - p.y * factor

                const origin = paper.options.origin

                const tx = origin.x - ax * scale
                const ty = origin.y - ay * scale

                movePaper(paper, tx, ty)

                const ctm = paper.matrix()

                ctm.a = scale
                ctm.d = scale

                paper.matrix(ctm)

                paper.drawGrid()
            } else {
                const dx = paper.options.origin.x - e.deltaX
                const dy = paper.options.origin.y - e.deltaY

                movePaper(paper, dx, dy)

                if (blocksPopupOpen.value) {
                    blocksPopupPosition.value = {
                        x: blocksPopupPosition.value!.x - e.deltaX,
                        y: blocksPopupPosition.value!.y - e.deltaY,
                    }
                }
            }
        },
        closePopup,
        addCursor(
            id: string,
            label: string,
            message: string,
            color: string,
            x: number,
            y: number
        ) {
            // cell with svg icon and text label
            let sh
            if (graph.getCell('cursor-' + id)) {
                sh = graph.getCell('cursor-' + id)
                // animate position change
                sh.transition(
                    'position',
                    {
                        x: x,
                        y: y,
                    },
                    {
                        delay: 0,
                        duration: 200,
                        valueFunction: joint.util.interpolate.object,
                    }
                )
            } else {
                sh = cursorShape.clone()
                sh.prop('id', 'cursor-' + id)
                graph.addCell(sh)
                sh.position(x, y)
            }
            sh.toFront()
            sh.prop('type', 'cursor')
            sh.attr('label/text', label)
            const textColor =
                chroma(color).luminance() > 0.5 ? 'black' : 'white'
            sh.attr('label/fill', textColor)
            sh.attr('message-text/text', message)
            sh.attr('message-text/fill', textColor)
            sh.attr('cursor/stroke', color)
            sh.attr('body/fill', color)
            sh.attr('message-rect/fill', color)
            sh.attr('body/ref-width', label.length * 7)

            if (message?.length) {
                sh.attr('message-text/text', message)
                const lineCount = Math.ceil(message.length / 21)
                sh.attr('message-rect/height', 12 * lineCount + 8)
            } else {
                sh.attr('message-text/text', '')
                sh.attr('message-rect/height', 0)
            }
        },
        handleEvent(event: Position & { id: string; type: string; options: any }) {
            console.log('Event received',event)
            switch (event.type) {
                case 'element-move':
                    graph.getCell(event.id)?.transition(
                        'position',
                        {
                            x: event.x,
                            y: event.y,
                        },
                        {
                            delay: 0,
                            duration: 200,
                            valueFunction: joint.util.interpolate.object,
                        }
                    )
                    break
                case 'link':
                    useRepo(Connection).save(event.options)
                    // console.log(connection)
                    // renderLinks([connection])
                    break
                case 'link:delete':
                    listenChange = false
                    clearLinks([
                        useRepo(Connection).find(event.id) as Connection,
                    ])
                    listenChange = true
                    break;
                case 'block':
                    console.log(event)
                    useRepo(Block).save(event.options)
                case 'block:delete':
                    silentRemove = true
                    graph.getCell(event.id)?.remove()
                    silentRemove = false
                    // listenChange = false
                    // listenChange = true
                    break
            }
        },
        addTemplateBlock(paper: dia.Paper, block: Block) {
            const connectionType = blocksPopupType.value
            const link = graph.getLinks().find((link) => !link.target().id)
            if (!link) {
                const { x, y } = blocksPopupPosition.value as Position
                Method.addBlock(methodId, block.id, x, y).then(res=> {
                    void graphEventHook.trigger({
                        x: 0,
                        y: 0,
                        id: res.id,
                        type: 'block',
                        options: res,
                    })
                })
                closePopup()
                return
            }
            closePopup()
            const { x, y } = link.target()
            if (!x || !y) return
            let sourceBlockId = link.source().id
            let sourcePortId = link.source().port
            let sourceBlockName = blockRepo.value.find(sourceBlockId)?.name
            const sourceDirection = portRepo.value.find(sourcePortId)?.direction

            Method.addBlock(methodId, block.id, x, y).then((res) => {
                void graphEventHook.trigger({
                    x: 0,
                    y: 0,
                    id: res.id,
                    type: 'block',
                    options: res,
                })
                let targetBlockId = res.id
                const possiblePorts = block.ports.filter(
                    (p) =>
                        p.direction ===
                        (sourceBlockName === 'end' ||
                        (sourceDirection === 'in' &&
                            sourceBlockName !== 'start')
                            ? 'out'
                            : 'in')
                )
                let targetPortId = null
                console.log(connectionType)
                if (connectionType === 'flow') {
                    targetPortId = possiblePorts.find(
                        (p) => p.type === connectionType
                    )?.id
                }
                else if (connectionType === 'any') targetPortId = possiblePorts.filter(p=>p.type !== 'flow')[0].id
                else {
                    const sameTypePort = possiblePorts.find(
                        (p) => p.type === connectionType
                    )
                    if (sameTypePort) targetPortId = sameTypePort.id
                    else {
                        const anyPort = possiblePorts.find(
                            (p) => p.type === 'any'
                        )
                        if (anyPort) targetPortId = anyPort.id
                    }
                }
                if (!targetPortId) return
                if (
                    (sourceDirection === 'in' && sourceBlockName !== 'start') ||
                    sourceBlockName === 'end'
                ) {
                    // swap source and target
                    const temp = sourceBlockId
                    sourceBlockId = targetBlockId
                    targetBlockId = temp
                    const tempPort = sourcePortId
                    sourcePortId = targetPortId
                    targetPortId = tempPort
                }

                Connection.store(
                    methodId,
                    sourceBlockId,
                    targetBlockId,
                    sourcePortId,
                    targetPortId
                ).then(res=>{
                    graphEventHook.trigger({
                        x: 0,
                        y: 0,
                        id: res.id,
                        type: 'link',
                        options: res,
                    })
                })
            })
        },
    }
}
