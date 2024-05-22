<template>
    <router-view />
</template>

<script lang="ts" setup>
import { RouterView } from 'vue-router'
import { provide } from 'vue'

provide('loading', false)
</script>
