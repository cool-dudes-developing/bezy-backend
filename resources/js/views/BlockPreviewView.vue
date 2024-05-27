<template>
    <method-editor :method-id="blockId"/>
</template>

<script lang="ts" setup>

import MethodEditor from '@/components/MethodEditor.vue'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const blockId = computed(() => route.params.block)
</script>

<style scoped>

</style>
