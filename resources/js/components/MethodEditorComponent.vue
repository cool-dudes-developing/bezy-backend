<template>
  <div
    class="canvas"
    @mousedown.self="onMouseDown"
    :style="{
      backgroundPositionX: offset.x + 'px',
      backgroundPositionY: offset.y + 'px'
    }"
  >
    <div
      class="block"
      style="top: 0; height: auto; width: 300px; z-index: 999; background: lightgray"
    >
      <div>mouse x: {{ mousePosition.x }} y: {{ mousePosition.y }}</div>
      <div>offset x: {{ offset.x }} y: {{ offset.y }}</div>
      <div v-if="drag.block">block x: {{ drag.x }} y: {{ drag.y }}</div>
      <div v-if="drag.canvas">canvas x: {{ drag.x }} y: {{ drag.y }}</div>
    </div>
    <div
      class="block"
      v-for="block in blocks"
      :key="block.id"
      :style="{
        top: block.top + offset.y + 'px',
        left: block.left + offset.x + 'px'
      }"
      @mousedown.prevent="onMouseDown($event, block)"
    >
      {{ block.name }}
      <p>x: {{ block.left }} y: {{ block.top }}</p>
      <div class="in">
        <div
          class="connector"
          v-for="(connector, key) in block.in"
          :key="key"
          @mousedown.prevent="onMouseDown($event, connector)"
        >
          {{ connector.name }}
        </div>
      </div>
      <div class="out">
        <div
          class="connector"
          v-for="(connector, key) in block.out"
          :key="key"
          @mousedown.prevent="onMouseDown($event, connector)"
        >
          {{ connector.name }}
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import Block from '@/models/Block'
import { defineProps, onMounted, ref } from 'vue'

const props = defineProps<{
  blocks: Block[]
}>()

const mousePosition = ref({ x: 0, y: 0 })

const drag = ref<{
  block: Block | null
  canvas: boolean
  x: number
  y: number
}>({
  block: null,
  canvas: false,
  x: 0,
  y: 0
})

const offset = ref<{
  x: number
  y: number
}>({
  x: 0,
  y: 0
})

onMounted(() => {
  // register mouse up event
  window.addEventListener('mouseup', onMouseUp)
  // register mouse move event
  window.addEventListener('mousemove', onMouseMove)

  // register scroll event
  window.addEventListener('wheel', onScroll)

  // register zoom event
  window.onresize = onZoom
})

const onZoom = (e: any) => {
  console.log('zoom', e)
}

const onScroll = (event: WheelEvent) => {
  offset.value.x -= event.deltaX
  offset.value.y -= event.deltaY
}

const onMouseDown = (e: MouseEvent, block?: Block) => {
  if (block) {
    drag.value.block = block
    drag.value.x = e.clientX - block.left
    drag.value.y = e.clientY - block.top
  } else {
    drag.value.canvas = true
    drag.value.x = e.clientX - offset.value.x
    drag.value.y = e.clientY - offset.value.y
  }
}

const onMouseUp = (e: MouseEvent) => {
  drag.value.block = null
  drag.value.canvas = false
}

const onMouseMove = (e: MouseEvent) => {
  mousePosition.value = {
    x: e.clientX,
    y: e.clientY
  }
  if (drag.value.block) {
    drag.value.block.left = e.clientX - drag.value.x
    drag.value.block.top = e.clientY - drag.value.y
  }
  if (drag.value.canvas) {
    offset.value.x = e.clientX - drag.value.x
    offset.value.y = e.clientY - drag.value.y
  }
}
</script>
<style scoped>
.canvas {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: whitesmoke;

  overflow: hidden;

  /* hand cursor */
  cursor: grab;

  /* block text selection */
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  /* grid cells background */
  background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
  background-repeat: repeat;
}
.block {
  position: absolute;
  width: 150px;
  height: 100px;
  background-color: white;
}
.in {
  position: absolute;
  top: 0;
  left: -20px;
  width: 20px;
  height: 100%;
  background-color: lightgray;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.out {
  position: absolute;
  top: 0;
  right: -20px;
  width: 20px;
  height: 100%;
  background-color: lightgray;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}
.connector {
  color: darkgray;
}
.connector:hover {
  color: green;
  background-color: green;
}

.connector::before {
  content: '';
  position: absolute;
  left: 0;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: inherit;
}
</style>
