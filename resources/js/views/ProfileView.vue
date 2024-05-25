<template>
    <div class="p-10">
        <h1 class="text-xl font-bold text-electricBlue">Profile</h1>
        <p>Welcome, {{ User.currentUser?.name }}!</p>
        <v-card disable-create>
            <template #title>Sessions</template>
            <template #subtitle>
                Manage your active sessions. You can revoke access to your
                account by deleting the session.
            </template>
            <items-list
                :items="sessions"
                @delete="deleteSession"
            >
                <template #item="{ item }">
                    {{ item.name }}
                </template>
            </items-list>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import User from '@/models/User'
import VCard from '@/components/VCard.vue'
import { onMounted, ref } from 'vue'
import { del, get } from '@/utils/api'
import ItemsList from '@/components/ItemsList.vue'

const sessions = ref([])

onMounted(() => {
    get('/auth/tokens').then((response) => {
        sessions.value = response.data.tokens
    })
})

function deleteSession(session) {
    del(`/auth/tokens/${session.id}`).then(() => {
        sessions.value = sessions.value.filter((s) => s.id !== session.id)
        if (!session.value.length) User.logout()
    })
}
</script>

<style scoped></style>
