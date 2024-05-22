<template>
  <component 
    :is="node.tag" 
    v-bind="node.attrs" 
    @dblclick.stop="getComponentId"
  >
    {{ node.innerContent }}
    <slot>
    </slot>
  </component>
</template>
<script setup lang="ts">
const props = defineProps({
  node: {
    type: Object,
    required: true
  }
})

const emit = defineEmits([
  "passComponentId"
])

function getComponentId() {
  if(props.node.attrs.id == 'Dom'){
    return
  }
  // console.log('component id emmited with value ' + props.node.attrs.id)
  emit("passComponentId", props.node.attrs.id)
}
</script>
