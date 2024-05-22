<template>
  <canvas ref="backgroundCanvas" class="h-full w-full blur-3xl" />
</template>
<script setup lang="ts">
import { Application, Graphics } from 'pixi.js'
import { onMounted, ref } from 'vue'
import * as SimplexNoise from 'simplex-noise'

const backgroundCanvas = ref<HTMLCanvasElement>()
const simplex = SimplexNoise.createNoise2D()
const orbs: Orb[] = []
const colors: number[] = [0x37AE9D, 0x69e5f8, 0xffffff]
const elapsed = 0

onMounted(() => {
  const app = new Application({
    view: backgroundCanvas.value,
    width: window.innerWidth,
    height: window.innerHeight,
    backgroundColor: 0xff0000,
    backgroundAlpha: 0,
    antialias: false,
    resolution: window.devicePixelRatio || 1,
    autoDensity: false
  })
  // app.stage.filters = [new KawaseBlurFilter(30, 10, true)]
  app.ticker.maxFPS = 15

  // Create orbs
  for (let i = 0; i < 12; i++) {
    // create orb with a random color from the colors array
    const orb = new Orb(colors[i % colors.length])
    app.stage.addChild(orb.graphics)

    orbs.push(orb)
  }

  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    app.ticker.add((delta) => {
      // update and render each orb, each frame. app.ticker attempts to run at 60fps
      orbs.forEach((orb) => {
        orb.update(delta)
        orb.render()
      })
    })
  } else {
    // perform one update and render per orb, do not animate
    orbs.forEach((orb) => {
      orb.update(1)
      orb.render()
    })
  }
})

// return a random number within a range
function random(min: number, max: number) {
  return Math.random() * (max - min) + min
}

// map a number from 1 range to another
function map(n: number, start1: number, end1: number, start2: number, end2: number) {
  return ((n - start1) / (end1 - start1)) * (end2 - start2) + start2
}

class Orb {
  // The area an orb is "allowed" to move within
  bounds: { x: { min: number; max: number }; y: { min: number; max: number } }
  // The orb's current { x, y } position
  x: number
  y: number
  // How large the orb is vs it's original radius (this will modulate over time)
  scale: number
  // What color is the orb?
  fill: number
  // The original radius of the orb, set relative to window height
  radius: number
  // Starting points in "time" for the noise/self similar random values
  xOff: number
  yOff: number
  // How quickly the noise/self similar random values step through time
  inc: number
  // PIXI.Graphics is used to draw 2d primitives (in this case a circle) to the canvas
  graphics: Graphics

  // Pixi takes hex colors as hexidecimal literals (0x rather than a string with '#')
  constructor(fill = 0x000000) {
    this.bounds = this.setBounds()
    this.x = random(this.bounds['x'].min, this.bounds['x'].max)
    this.y = random(this.bounds['y'].min, this.bounds['y'].max)
    this.scale = 10
    this.fill = fill
    this.radius = random(window.innerHeight / 6, window.innerHeight / 3)
    this.xOff = random(0, 1000)
    this.yOff = random(0, 1000)
    this.inc = 0.0003
    this.graphics = new Graphics()
    // tell graphics to fill any shapes drawn after this with the orb's fill color
    this.graphics.beginFill(this.fill)
    // draw a circle at { 0, 0 } with it's size set by this.radius
    this.graphics.drawCircle(0, 0, this.radius)
    // let graphics know we won't be filling in any more shapes
    this.graphics.endFill()
    this.graphics.alpha = 0.75
    // window.addEventListener(
    //   'resize',
    //   debounce(() => {
    //     this.bounds = this.setBounds()
    //   }, 250)
    // )
  }

  setBounds() {
    // how far from the { x, y } origin can each orb move
    const maxDistX = window.innerWidth / 3
    const maxDistY = window.innerHeight / 3
    // the { x, y } origin for each orb (the center of the screen)
    const originX = window.innerWidth / 2
    const originY = window.innerHeight / 2

    // allow each orb to move x distance away from it's { x, y }origin
    return {
      x: {
        min: originX - maxDistX,
        max: originX + maxDistX
      },
      y: {
        min: originY - maxDistY,
        max: originY + maxDistY
      }
    }
    return {
      x: {
        min: 0,
        max: window.innerWidth
      },
      y: {
        min: 0,
        max: window.innerHeight
      }
    }
  }

  update(delta: number) {
    // self similar "psuedo-random" or noise values at a given point in "time"
    const xNoise = simplex(this.xOff, this.xOff)
    const yNoise = simplex(this.yOff, this.yOff)
    const scaleNoise = simplex(this.xOff, this.yOff)

    // map the xNoise/yNoise values (between -1 and 1) to a point within the orb's bounds
    this.x = map(xNoise, -1, 1, this.bounds['x'].min, this.bounds['x'].max)
    this.y = map(yNoise, -1, 1, this.bounds['y'].min, this.bounds['y'].max)
    // map scaleNoise (between -1 and 1) to a scale value somewhere between half of the orb's original size, and 100% of it's original size
    this.scale = map(scaleNoise, -1, 1, 0.5, 1)

    // step through "time"
    this.xOff += this.inc * delta
    this.yOff += this.inc * delta
  }

  render() {
    // update the PIXI.Graphics position and scale values
    this.graphics.transform.scale.set(this.scale)
    this.graphics.transform.position.set(this.x, this.y)
    // this.graphics.x = this.x
    // this.graphics.y = this.y
    // this.graphics.scale.set(this.scale)
  }
}
</script>
