<template>
  <div class="flex flex-row h-screen">
    <div class="flex flex-col grow h-screen">
      <DOMRenderer
      :node="selectedComponent" 
      @pass-component-id="selectComponent"
      />
    </div>
    <FrontendElementEditor>
      <InputContainer>
        <Input
          label="W"
          :is-disabled="isDisabledInput.width"
          :model-value="selectedComponentStyle['width']"
          @update:model-value="newValue => updateStyle('width', newValue)"
        />
        <Input 
          label="H"
          :is-disabled="isDisabledInput.height"
          :model-value="selectedComponentStyle['height']"
          @update:model-value="newValue => updateStyle('height', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input
          label="WT"
          input-type="select"
          :select-options="[
            {
              value: 'fixed',
              text: 'Fixed width'
            },
            {
              value: 'hug',
              text: 'Hug contents'
            },
            {
              value: 'fill',
              text: 'Fill container'
            }
          ]"
          :model-value="selectedComponentStyle['cwidth']"
          @update:model-value="newValue => updateStyle('cwidth', newValue)"
        />
        <Input 
          label="HT"
          input-type="select"
          :select-options="[
            {
              value: 'fixed',
              text: 'Fixed height'
            },
            {
              value: 'hug',
              text: 'Hug contents'
            },
            {
              value: 'fill',
              text: 'Fill container'
            }
          ]"
          :model-value="selectedComponentStyle['cheight']"
          @update:model-value="newValue => updateStyle('cheight', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input 
          label="F"
          input-type="select"
          :select-options="[
            {
              value: 'row',
              text: 'Horizontal layout'
            },
            {
              value: 'column',
              text: 'Vertical layout'
            }
          ]"
          :model-value="selectedComponentStyle['flex-direction']"
          @update:model-value="newValue => updateStyle('flex-direction', newValue)"
        />
        <Input
          label="G"
          :model-value="selectedComponentStyle['gap']"
          @update:model-value="newValue => updateStyle('gap', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input
          label="FM"
          input-type="select"
          :select-options="[
            {
              value: 'flex-start',
              text: 'Start'
            },
            {
              value: 'flex-end',
              text: 'End'
            },
            {
              value: 'center',
              text: 'Center'
            }
          ]"
          :model-value="selectedComponentStyle['justify-content']"
          @update:model-value="newValue => updateStyle('justify-content', newValue)"
        />
        <Input 
          label="FS"
          input-type="select"
          :select-options="[
            {
              value: 'flex-start',
              text: 'Start'
            },
            {
              value: 'flex-end',
              text: 'End'
            },
            {
              value: 'center',
              text: 'Center'
            },
            {
              value: 'stretch',
              text: 'Stretch'
            }
          ]"
          :model-value="selectedComponentStyle['align-items']"
          @update:model-value="newValue => updateStyle('align-items', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input
          label="PX"
          :model-value="selectedComponentStyle['padding-x']"
          @update:model-value="newValue => updateStyle('padding-x', newValue)"
        />
        <Input
          label="PY"
          :model-value="selectedComponentStyle['padding-y']"
          @update:model-value="newValue => updateStyle('padding-y', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input 
          label="BG"
          :model-value="selectedComponentStyle['background-color']"
          @update:model-value="newValue => updateStyle('background-color', newValue)"
        />
        <Input 
          label="TC"
          :model-value="selectedComponentStyle['color']"
          @update:model-value="newValue => updateStyle('color', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <Input 
          label="B"
          :model-value="selectedComponentStyle['border-width']"
          @update:model-value="newValue => updateStyle('border-width', newValue)"
        />
        <Input 
          label="R"
          :model-value="selectedComponentStyle['border-radius']"
          @update:model-value="newValue => updateStyle('border-radius', newValue)"
        />
      </InputContainer>
      <InputContainer>
        <input
          type="text"
          class="w-full p-1 bg-transparent focus:outline-none overflow-scroll hover:border hover:rounded"
          v-model="selectedComponent.innerContent"
        />
      </InputContainer>
      <!-- <InputContainer>
        <Input 
          label="PL" 
          :model-value="selectedComponentStyle['padding-left']"
          @update:model-value="newValue => updateStyle(['padding-left', newValue])"/>
        <Input 
          label="PR" 
          :model-value="selectedComponentStyle['padding-right']"
          @update:model-value="newValue => updateStyle(['padding-right', newValue])"/>
      </InputContainer>
      <InputContainer>
        <Input 
            label="PT" 
            :model-value="selectedComponentStyle['padding-top']"
            @update:model-value="newValue => updateStyle(['padding-top', newValue])"/>
        <Input 
            label="PB" 
            :model-value="selectedComponentStyle['padding-bottom']"
            @update:model-value="newValue => updateStyle(['padding-bottom', newValue])"/>
      </InputContainer> -->
      <div class="grow flex flex-col gap-3 justify-end">
        <button 
          @click="addComponent" 
          class="bg-blue text-black w-full h-8 px-2 rounded"
        >
          Add component 
        </button>
        <!-- <button 
          @click="makeComponent" 
          class="bg-blue text-black w-full h-8 px-2 rounded"
        >
          Make component
        </button> -->
        <button 
          @click="removeComponent" 
          class="bg-blue text-black w-full h-8 px-2 rounded"
        >
          Remove component
        </button>
        <div class="flex flex-row gap-3">
          <button 
            @click="selectParent" 
            class="bg-blue text-black w-full h-8 px-2 rounded"
          >
            To parent
          </button>
          <button 
            @click="selectedComponent = dom" 
            class="bg-blue text-black w-full h-8 px-2 rounded"
          >
            To DOM
          </button>
        </div>
        <div class="flex flex-row gap-3">
          <button
            @click="showRendered"
            class="bg-blue text-black w-full h-8 px-2 rounded"
          >
            Show rendered
          </button>
          <!-- <button
            @click="saveDomJson"
            class="bg-blue text-black w-full h-8 px-2 rounded"
          >
            Save JSON
          </button> -->
        </div>
      </div>
    </FrontendElementEditor>
    <!-- <textarea v-model="selectedComponent.attrs.style" class="grow text-black resize-none outline-none"></textarea> -->
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import DOMRenderer from '@/components/DOMRenderer.vue'
import FrontendElementEditor from '@/components/FrontendElementEditor.vue'
import Input from '@/components/FrontendElementEditorInput.vue'
import InputContainer from '@/components/FrontendElementEditorInputContainer.vue'
import router from '@/router'
import domJson from '@/domJson' 
import { globalStates } from '@/global-states'
type Style = { [propKey: string]: string }
type BooleanValues = { [propKey: string]: boolean }

const dom = ref(domJson)

const customStyle = [
  'padding-x',
  'padding-y',
  'cwidth',
  'cheight'
]

const isDisabledInput = ref<BooleanValues>({
  'width': true,
  'height': true
})

const newDivId = ref(0)
const isNewSelected = ref(false)
const selectedComponent = ref(dom.value)
// const previousSelectedComponent = ref(dom.value)
const selectedComponentStyle = ref<Style>({})

onMounted(() => {
  getComponentStyle()
  globalStates.isDraggable = true
})

function addComponent() {
  addDiv()
}

function makeComponent() {

}

function showRendered() {
  router.push({name: 'renderedfrontend'})
}

function saveDomJson() {
  console.log(domJson)
}

function removeComponent() {
  let removeComponentId = selectedComponent.value.attrs.id
  selectParent()
  getComponentStyle()
  selectedComponent.value.children.forEach((el, index) => {
    if(el.attrs.id == removeComponentId) {
      selectedComponent.value.children.splice(index, 1)
    }
  })
}

function addDiv() {
  selectedComponent.value.children.push({
    tag: 'div', 
    innerContent: 'New div ' + newDivId.value.toString(),
    attrs: {
      id: 'newDiv' + newDivId.value.toString(), 
      style:
        'display:flex;'+
        'flex-direction:column;'+
        'gap:0px;'+
        'flex-grow:1;'+
        'width:auto;'+
        'height:auto;'+
        'cwidth:fill;'+
        'cheight:fill;'+
        'background-color:#69e5f8;'+
        'color:black;'+
        'border-style:solid;'+
        'border-width:1px;'+
        'border-radius:0px;'+
        'padding-left:5px;'+
        'padding-right:5px;'+
        'padding-top:5px;'+
        'padding-bottom:5px;'+
        'padding-x:5px;'+
        'padding-y:5px;'
    },
    children: []
  })
  newDivId.value++;
}

function selectComponent(id: string) {
  isNewSelected.value = false
  selectedComponent.value.children.forEach((el) => {
    // if(isNewSelected.value) {
    //   selectedComponent.value = el
    //   isNewSelected.value = false
    //   return
    // } else {
    findComponent(id, el)
    console.log(selectedComponent.value)
    // }
    if(isNewSelected.value) {
      // previousSelectedComponent.value = selectedComponent.value
      selectedComponent.value = el
      isNewSelected.value = false
    }
  })
  // if(isNewSelected.value) {
  getComponentStyle()
  console.log('selectedComponent id: ' + selectedComponent.value.attrs.id)
  // } else {
  //   console.log('No component was found.')
  // }
}

function selectParent() {
  findParent(selectedComponent.value.attrs.id ,dom.value, dom.value)
  getComponentStyle()
}

function findComponent(id: string, el: any) {
  if(el.attrs.id == id) {
    selectedComponent.value = el
    isNewSelected.value = true
  }
  if(el.children.length > 0) {
    el.children.forEach((child: any) => {
      findComponent(id, child)
    })
  }
}

function findParent(id: string, el: any, parent: any) {
  if(el.attrs.id == id) {
    selectedComponent.value = parent
    isNewSelected.value = true
  }
  if(el.children.length > 0) {
    el.children.forEach((child: any) => {
      findParent(id, child, el)
    })
  }
}

function getComponentStyle() {
  let start = 0
  let end = selectedComponent.value.attrs.style.indexOf(":")
  let key = selectedComponent.value.attrs.style.substring(start, end)
  start = end
  end = selectedComponent.value.attrs.style.indexOf(";", start)
  let value = selectedComponent.value.attrs.style.substring(start+1, end)
  selectedComponentStyle.value[key] = value
  while(true) {
    start = end
    end = selectedComponent.value.attrs.style.indexOf(":", start)
    if(end == -1) {
      break
    }
    key = selectedComponent.value.attrs.style.substring(start+1, end)
    start = end
    end = selectedComponent.value.attrs.style.indexOf(";", start)
    value = selectedComponent.value.attrs.style.substring(start+1, end)
    selectedComponentStyle.value[key] = value
  }
  console.log(selectedComponentStyle.value)
}

function updateStyle(key: string, value: string) {
  selectedComponentStyle.value[key] = value
  if(customStyle.includes(key)){
    updateCustomStyle(key, value)
  }
  updateAllStyles()
}

function updateCustomStyle(key: string, value: string) {
  switch(key) {
    case 'padding-x':
      selectedComponentStyle.value['padding-left'] = value
      selectedComponentStyle.value['padding-right'] = value
      break
    case 'padding-y':
      selectedComponentStyle.value['padding-top'] = value
      selectedComponentStyle.value['padding-bottom'] = value
      break
    case 'cwidth':
      updateCSize(key, value)
      break
    case 'cheight':
      updateCSize(key, value)
      break
  }
}

function updateCSize(key: string, value: string) {
  switch(value) {
    case 'fixed':
      isDisabledInput.value[key.substring(1)] = false
      if(key == 'cheight') {
        selectedComponentStyle.value['flex-grow'] = '0'
      }
      break
    case 'hug':
      isDisabledInput.value[key.substring(1)] = true
      selectedComponentStyle.value[key.substring(1)] = 'fit-content'
      if(key == 'cheight') {
        selectedComponentStyle.value['flex-grow'] = '0'
      }
      break
    case 'fill':
      isDisabledInput.value[key.substring(1)] = true
      selectedComponentStyle.value[key.substring(1)] = 'auto'
      if(key == 'cheight') {
        selectedComponentStyle.value['flex-grow'] = '1'
      }
      break
  }
}

function updateAllStyles() {
  let style = ""
  Object.keys(selectedComponentStyle.value).forEach((key: string) => {
    style += key + ":" + selectedComponentStyle.value[key] +";"
  })
  console.log(style)
  selectedComponent.value.attrs.style = style
}

function showDOMLog(){
  console.log(dom.value.children)
}
</script>
