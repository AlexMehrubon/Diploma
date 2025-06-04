<script setup>
import Tooltip from "@/components/Tooltip.vue";
import CourseEditModal from "@/components/courses/CourseEditModal.vue";
import {onMounted, ref} from "vue";
import DeleteModal from "@/components/modals/DeleteModal.vue";
import CourseCreateModal from "@/components/courses/CourseCreateModal.vue";

const props = defineProps({
  coursesStore: Object,
});

const courses = ref([]);


onMounted(() => {
  courses.value = props.coursesStore?.courses['data'] ?? []

  if (courses.value.length > 0) {
    let perPage = props.coursesStore.paginator.per_page
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
  <CourseEditModal
    :courses-store="props.coursesStore"
    v-if="props.coursesStore.isShowedEditModal"
  />
  <CourseCreateModal
    :courses-store="props.coursesStore"
    v-if="props.coursesStore.isShowedCreateModal"
  />
  <DeleteModal :store="coursesStore">
    Вы уверены что хотите удалить данный курс?
  </DeleteModal>

  <table class="w-full text-base text-left text-gray-500">
    <thead class="text-sm text-gray-700 uppercase bg-gray-50">
    <tr>
      <th scope="col" class="px-6 py-3 text-sm">№</th>
      <th scope="col" class="px-6 py-3">Название</th>
      <th scope="col" class="px-6 py-3">Сложность</th>
      <th scope="col" class="px-6 py-3">Продолжительность (ч.)</th>
      <th scope="col" class="px-6 py-3">Рейтинг</th>
      <th scope="col" class="px-6 py-3">Статус</th>
      <th scope="col" class="px-6 py-3">Дата создания</th>
      <th scope="col" class="px-6 py-3">Действие</th>
    </tr>
    </thead>

    <tbody>
    <tr
      v-for="course in props.coursesStore.courses['data']"
      :key="course.id"
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ course.id }}
      </th>
      <td class="px-6 py-4">{{ course.name }}</td>
      <td class="px-6 py-4">{{ course.difficulty_level }}</td>
      <td class="px-6 py-4">{{ course.duration }}</td>
      <td class="px-6 py-4">{{ course.rating }}</td>
      <td class="px-6 py-4">{{ course.status }}</td>
      <td class="px-6 py-4">{{ course.created_at }}</td>
      <td class="px-6 py-4 flex flex-row gap-x-6">
        <template v-if="typeof course.id !== 'undefined'">
          <Tooltip>
            <template #trigger>
              <a @click="props.coursesStore.switchEditModal(course)">
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
              <a @click="props.coursesStore.switchDeleteModal(course.id)">

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
