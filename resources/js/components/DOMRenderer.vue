<template>
  <DOMElementRenderer 
    :node="node" 
    @pass-component-id="passComponentId"
  >
    <DraggableComponent
      :style="
        node.attrs.style +
        'border-style:none;'+
        'display:flex;'+
        'justify-items:stretch;'+
        'height:100%;'+
        'width:100%;'+
        'padding:0;'+
        globalStates.draggableComponentStyles"
      v-model="node.children"
      item-key="id"
      @start="startDrag" 
      @end="endDrag"
      :disabled="!globalStates.isDraggable"
      >
      <template #item="{element}">
        <DOMRenderer 
          :node="element" 
          v-if="element.tag === 'div'" 
          @pass-component-id="passComponentId"
        />
        <DOMElementRenderer 
          :node="element" 
          v-else
        />
      </template>
    </DraggableComponent>
  </DOMElementRenderer>
</template>
<script setup lang="ts">
import DOMElementRenderer from './DOMElementRenderer.vue'
import DraggableComponent from 'vuedraggable'
import { ref } from 'vue'
import { globalStates } from '@/global-states'

const props = defineProps({
  node: {
    type: Object,
    required: true
  }
})

const emit = defineEmits([
  "passComponentId"
])

const drag = ref(false)

function passComponentId(id: string) {
  // console.log("component id received and passed with value " + id)
  emit("passComponentId", id)
}

function startDrag() {
  drag.value = true
  globalStates.draggableComponentStyles =
    ''
    // 'padding:10px;'
    // 'border-width:1px;'+
    // 'border-style:dashed;'+
    // 'border-color:black;'
}

function endDrag() {
  drag.value = false
  globalStates.draggableComponentStyles = ''
}
</script>
