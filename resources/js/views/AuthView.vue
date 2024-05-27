<template>
    <div class="relative h-full w-full gap-[2.5rem] py-16">
        <div class="noise absolute left-0 top-0 -z-20 h-full w-full">
            <gradient-background class="absolute left-0 top-0 -z-10" />
        </div>
        <div class="flex h-full flex-col items-center justify-between">
            <figure
                class="flex select-none items-center gap-[10px] font-header text-4xl font-bold"
            >
                <!-- <img src="@/assets/logo/logo-light.svg" alt="Bezy logo" class="w-[60px]" /> -->
                <svg-icon
                    class="w-[60px]"
                    name="logo-light"
                />
                <figcaption>Bezy</figcaption>
            </figure>
            <div
                class="h-auto w-11/12 max-w-[320px] rounded-3xl bg-dark bg-opacity-75 p-[1.25rem] drop-shadow-lg backdrop-blur-xl"
            >
                <slot>
                    <router-view v-slot="{ Component }">
                        <transition
                            mode="out-in"
                            name="slide"
                        >
                            <spinner-loader
                                v-if="spinner?.visible.value"
                                class="absolute inset-0"
                            />
                        </transition>
                        <transition
                            mode="out-in"
                            name="slide"
                        >
                            <component
                                :is="Component"
                                :class="{
                                    'opacity-0': spinner?.visible.value,
                                }"
                                class="transform-gpu duration-100"
                            />
                        </transition>
                    </router-view>
                </slot>
            </div>
            <p class="font-footer font-bold text-light">
                Made with ♥️ by
                <span class="font-header decoration-2 hover:underline">
                    Bezy
                </span>
            </p>
        </div>
    </div>
</template>
<script lang="ts" setup>
import GradientBackground from '@/components/GradientBackground.vue'
import SpinnerLoader from '@/components/SpinnerLoader.vue'
import { SpinnerKey } from '@/symbols'
import { inject } from 'vue'

const spinner = inject(SpinnerKey)
console.log(spinner?.visible.value)
</script>
<style scoped></style>
