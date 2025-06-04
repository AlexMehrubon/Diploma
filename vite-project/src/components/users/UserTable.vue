<script setup>
import Tooltip from "@/components/Tooltip.vue";
import CourseEditModal from "@/components/courses/CourseEditModal.vue";
import {onMounted, ref} from "vue";
import DeleteModal from "@/components/modals/DeleteModal.vue";
import CourseCreateModal from "@/components/courses/CourseCreateModal.vue";

const props = defineProps({
  userStore: Object,
});

const courses = ref([]);


onMounted(() => {
  courses.value = props.userStore?.users['data'] ?? []

  if (courses.value.length > 0) {
    let perPage = props.userStore.paginator.per_page
    let countMissingItems = 0
    if (perPage > courses.value.length) {
      countMissingItems = perPage - courses.value.length
      for (let i = 0; i < countMissingItems; i++) {
        courses.value.push({})
      }

    }
  }
})


</script>

<template>
<!--<CourseEditModal
    :courses-store="props.userStore"
    v-if="props.userStore.isShowedEditModal"
  />
  <CourseCreateModal
    :courses-store="props.userStore"
    v-if="props.userStore.isShowedCreateModal"
  />-->
  <DeleteModal :store="userStore">
    Вы уверены что хотите удалить данного пользователя?
  </DeleteModal>

  <table class="w-full text-base text-left text-gray-500">
    <thead class="text-sm text-gray-700 uppercase bg-gray-50">
    <tr>
      <th scope="col" class="px-6 py-3 text-sm">№</th>
      <th scope="col" class="px-6 py-3">Аватар</th>
      <th scope="col" class="px-6 py-3">Имя</th>
      <th scope="col" class="px-6 py-3">Почта</th>
      <th scope="col" class="px-6 py-3">Дата регистрации</th>
      <th scope="col" class="px-6 py-3">Роли</th>
      <th scope="col" class="px-6 py-3">Действие</th>
    </tr>
    </thead>

    <tbody>
    <tr
      v-for="user in props.userStore.users['data']"
      :key="user.id"
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ user.id }}
      </th>
      <td class="px-6 py-4"><img class="w-6 h-6" v-if="user.image_url" :src="user.image_url" alt="user avatar"/></td>
      <td class="px-6 py-4">{{ user.name }}</td>
      <td class="px-6 py-4">{{ user.email }}</td>
      <td class="px-6 py-4">{{ user.created_at }}</td>
      <td class="px-6 py-4" v-html="user.roles?.map(role => role.name).join('<br>')"></td>
      <td class="px-6 py-4">
        <template v-if="typeof user.id !== 'undefined'">
          <Tooltip class="mr-6">
            <template #trigger>
              <a @click="props.coursesStore.switchEditModal(user)">
                <img src="@/assets/pencil.png "
                     class="h-6 w-6 hover:scale-105 transition-transform duration-500" alt="edit">
              </a>
            </template>
            <template #content>
              Редактировать
            </template>
          </Tooltip>

          <Tooltip>
            <template #trigger>
              <a @click="props.userStore.switchDeleteModal(user.id)">

                <img src="@/assets/recycle-bin.png"
                     class="h-6 w-6 hover:scale-105 transition-transform duration-500" alt="delete">
              </a>
            </template>
            <template #content>
              Удалить
            </template>
          </Tooltip>
        </template>
        <div v-else class="w-6 h-6">

        </div>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<style scoped></style>
