<script setup>
import {ref, onMounted, computed} from "vue";
import Tooltip from "@/components/Tooltip.vue";
import LessonCreateModal from "@/components/lessons/LessonCreateModal.vue";
import {useLessonStore} from "@/stores/LessonStore.js";

const lessonStore = useLessonStore()

onMounted(async () => {

  if (!lessonStore.lessons)
    await lessonStore.fetch(1, 8, [], false)
  console.log(lessonStore.lessons)
})

const lessons = computed(() => lessonStore.lessons?.data ?? [])

const paddedLessons = computed(() => {
  return [...lessons.value].sort((a, b) => a.order - b.order)
})


function deleteLesson(lesson) {
  if (confirm(`Вы уверены, что хотите удалить урок "${lesson.name}"?`)) {
    lessonStore.deleteLesson(lesson.id)
      .catch(error => {
        console.error('Ошибка при удалении:', error);
      });
  }
}

function editLesson(lesson) {
  lessonStore.openModal('edit', lesson)
}
</script>

<template>


  <table class="w-full text-base text-left text-gray-500">
    <thead class="text-sm text-gray-700 uppercase bg-gray-50">
    <tr>
      <th scope="col" class="px-6 py-3 text-sm">№</th>
      <th scope="col" class="px-6 py-3">Название</th>
      <th scope="col" class="px-6 py-3">В каком порядке отображается</th>
    </tr>
    </thead>

    <tbody>
    <tr
      v-for="(lesson, index) in paddedLessons" :key="lesson.id ?? Math.random()"
      class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ index + 1 }}
      </th>
      <td class="px-6 py-4">{{ lesson.name }}</td>
      <td class="px-6 py-4">{{ lesson.order }}</td>
      <td class="px-6 py-4 flex flex-row gap-x-6">
        <template v-if="typeof lesson.id !== 'undefined'">
          <Tooltip>
            <template #trigger>
              <a @click="editLesson(lesson)">
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
              <a @click="deleteLesson(lesson)">
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


  <LessonCreateModal
    v-if="lessonStore.isModalOpen"
    :lesson-store="lessonStore"
    :mode="lessonStore.modalMode"
    :editedLesson="lessonStore.currentLesson"
    @close="lessonStore.closeModal"
  />
</template>

<style scoped></style>
