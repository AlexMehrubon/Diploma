<script setup>
import AdminPanel from "@/components/layouts/AdminPanel.vue";
import {useUserStore} from "@/stores/userStore.js";

import UserTable from "@/components/users/UserTable.vue";

const userStore = useUserStore()

</script>

<template>
  <AdminPanel
    :store="userStore"
    search-placeholder="e-mail пользователя..."
    search-field="email"
  >
    <template #title>
      Управление пользователями
    </template>
    <template #table>
      <UserTable :user-store="userStore"/>
    </template>

  </AdminPanel>
</template>

<style scoped>

</style>
