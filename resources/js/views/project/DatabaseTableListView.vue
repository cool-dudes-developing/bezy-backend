<template>
    <div class="flex flex-col gap-10 p-10">
        <v-card>
            <template #title>Tables</template>
            <template #subtitle>Database tables for this project.</template>
            <items-list
                :items="tables"
                @delete="
                    DatabaseTable.destroy(
                        route.params.project as string,
                        $event.id
                    )
                "
            >
                <template #item="{ item }">
                    <router-link
                        :to="{
                            name: 'table',
                            params: {
                                project: route.params.project,
                                table: item.id,
                            },
                        }"
                        class="w-full"
                    >
                        {{ item.name }}
                        <small class="text-xs text-light/75">
                            Rows: {{ item.rows_count }}
                        </small>
                    </router-link>
                </template>
                <template #empty>
                    <p class="max-w-prose">
                        You have not created any tables for this project. Create
                        new tables to store your data.
                    </p>
                </template>
            </items-list>
            <div
                class="flex w-1/2 flex-col items-start gap-1 rounded-lg bg-dark p-1 px-3 py-2"
            >
                <h3>Create new table</h3>
                <div class="flex w-full items-center gap-3">
                    <input
                        v-model="newTableName"
                        class="grow rounded border border-petronas bg-transparent p-1 text-black text-white"
                        placeholder="New table name"
                        type="text"
                    />
                    <button
                        class="rounded bg-blue p-1 px-3 text-black"
                        @click="
                            DatabaseTable.store(
                                route.params.project as string,
                                {
                                    name: newTableName,
                                }
                            ).then(() => {
                                newTableName = ''
                            })
                        "
                    >
                        Create
                    </button>
                </div>
            </div>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import DatabaseTable from '@/models/DatabaseTable'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useRepo } from 'pinia-orm'
import VCard from '@/components/VCard.vue'
import ItemsList from '@/components/ItemsList.vue'

const route = computed(() => useRoute())

const tables = computed(() => useRepo(DatabaseTable).all())

const newTableName = ref('')

onMounted(() => {
    DatabaseTable.fetchAll(route.value.params.project as string)
})
</script>

<style scoped></style>
