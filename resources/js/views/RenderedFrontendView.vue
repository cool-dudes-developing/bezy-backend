<template>
  <div class="flex h-full">
    <DOMRenderer :node="domJson"/>
  </div>
</template>

<script setup lang="ts">
import DOMRenderer from '@/components/DOMRenderer.vue';
import domJson from '@/domJson';
import { onMounted } from 'vue';
import { globalStates } from '@/global-states';

onMounted(() => {
  globalStates.isDraggable = false
})
</script>