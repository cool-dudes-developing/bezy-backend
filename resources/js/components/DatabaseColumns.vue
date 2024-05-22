<template>
  <table v-if="columns" class="w-full border-collapse border border-border">
    <thead>
    <tr>
      <th class="border border-border w-8">
        #
      </th>
      <th class="border border-border w-1/6">
        Column Name
      </th>
      <th class="border border-border">
        Type
      </th>
      <th class="border border-border">
        Empty allowed
      </th>
      <th class="border border-border">
        Default value
      </th>
      <th class="border border-border">
        Comment
      </th>
    </tr>
    </thead>
    <tbody>
    <tr
      v-for="(col, index) in columns"
      :key="col.id" :class="[
        !originalColumns.map(c => c.id).includes(col.id)
         ? 'bg-green-600'
          : ''
      ]">
      <td class="text-center">
        {{ index }}
      </td>
      <td @dblclick="editColumn(index, 'name')">
        <input v-if="edit.index === index && edit.property === 'name'" :value="col.name"
               class="bg-transparent"
               type="text"
               @input="onUpdateTableColumn(col, 'name', $event.target.value)" />
        <span v-else>
          {{ col.name }}
        </span>
      </td>
      <td>
        <select v-model="col.type" class="bg-transparent">
          <option value="uuid">UUID</option>
          <option value="timestamp">Timestamp</option>
          <option value="string">String</option>
          <option value="text">Text</option>
          <option value="integer">Integer</option>
          <option value="boolean">Boolean</option>
        </select>
      </td>
      <td>
        {{ col.is_nullable ? 'Yes' : 'No' }}
      </td>
      <td :class="{
          'opacity-50 font-light':!col.default
        }">
        {{ col.default ?? 'NULL' }}
      </td>
      <td>
        {{ col.comment }}
      </td>
    </tr>
    <tr>
      <td class="border border-border" colspan="6">
        <button class="w-full" @click="addColumn">
          Add new column
        </button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useRepo } from 'pinia-orm'
import DatabaseTableColumn from '@/models/DatabaseTableColumn'

const props = defineProps({
  tableId: {
    type: String,
    required: true
  },
  columns: {
    type: Array
  },
  originalColumns: {
    type: Array
  }
})

const edit = ref({
  index: null,
  property: null
})

function addColumn() {
  useRepo(DatabaseTableColumn).save({
    table_id: props.tableId,
    name: '',
    type: 'string',
    is_nullable: true,
    default: null,
    comment: null
  })
}

function editColumn(index, property) {
  edit.value = {
    index: index,
    property: property
  }
}

function onUpdateTableColumn(column, property, value) {
  column[property] = value
  useRepo(DatabaseTableColumn).save(column)
}
</script>

<style scoped>

</style>