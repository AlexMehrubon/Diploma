<script setup>
import Tooltip from "@/components/Tooltip.vue";
import DeleteModal from "@/components/modals/DeleteModal.vue";
import ModuleEditModal from "@/components/modules/ModuleEditModal.vue";
import Editor from "@/components/Editor.vue";



const props = defineProps({
  moduleStore: Object,
  courseStore: Object
});


</script>

<template>

  <ModuleEditModal
    :module-store="props.moduleStore"
    :courses-store="props.courseStore"
    v-if="props.moduleStore.isShowedEditModal"
  />
  <DeleteModal :store="props.moduleStore">
    Вы уверены что хотите удалить данный модуль?
  </DeleteModal>

  <table class="w-full text-base text-left text-gray-500 overflow-hidden">
    <thead class="text-sm text-gray-700 uppercase bg-gray-50">
    <tr>
      <th scope="col" class="px-6 py-3 text-sm">№</th>
      <th scope="col" class="px-6 py-3">Название</th>
      <th scope="col" class="px-6 py-3">Описание</th>
      <th scope="col" class="px-6 py-3">Порядок</th>
      <th scope="col" class="px-6 py-3">Действие</th>
    </tr>
    </thead>

    <tbody>
    <tr
      v-for="(module, index) in props.moduleStore.modules.data"
      :key="module.id"
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ index + 1 }}
      </th>
      <td class="px-6 py-4">{{ module.name }}</td>
      <td class="px-6 py-4">{{ module.description }}</td>
      <td class="px-6 py-4">{{ module.order }}</td>
      <td class="px-6 py-4 flex flex-row gap-x-6">
        <template v-if="typeof module.id !== 'undefined'">
          <Tooltip>
            <template #trigger>
              <a @click="props.moduleStore.switchEditModal(module)">
                <img src="@/assets/pencil.png"
                     class="h-6 w-6 hover:scale-105 transition-transform duration-500" alt="edit">
              </a>
            </template>
            <template #content>
              Редактировать
            </template>
          </Tooltip>

          <Tooltip>
            <template #trigger>
              <a @click="props.moduleStore.switchDeleteModal(module.id)">
                <img src="@/assets/recycle-bin.png"
                     class="h-6 w-6 hover:scale-105 transition-transform duration-500" alt="delete">
              </a>
            </template>
            <template #content>
              Удалить
            </template>
          </Tooltip>
        </template>
        <div v-else class="w-6 h-6"></div>
      </td>
    </tr>
    </tbody>
  </table>

</template>

<style scoped>
</style>
