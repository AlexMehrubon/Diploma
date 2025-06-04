<script setup>
import {onMounted, ref} from "vue";
import {useLessonStore} from "@/stores/LessonStore.js";
import LessonCreateModal from "@/components/lessons/LessonCreateModal.vue";
import LessonTable from "@/components/lessons/LessonTable.vue";


const props = defineProps({
  coursesStore: Object,
  moduleStore: Object,
});

const lessonStore = useLessonStore()

const module = ref({
  id: props.moduleStore.currentEditModule.id,
  name: props.moduleStore.currentEditModule.name,
  description: props.moduleStore.currentEditModule.description,
  order: props.moduleStore.currentEditModule.order,
  course_id: props.moduleStore.currentEditModule.course_id
})

onMounted(async () => {
  lessonStore.lessons.data = props.moduleStore.currentEditModule.lessons
})

</script>

<template>
  <LessonCreateModal
    v-if="lessonStore.isModalOpen"
    :lesson-store="lessonStore"
    :mode="lessonStore.modalMode"
  :editedLesson="lessonStore.currentLesson"
  @close="lessonStore.closeModal"
  />


  <div id="create-module-modal" tabindex="-1" aria-hidden="true"
       class="flex items-center justify-center overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-60 md:inset-0 h-screen max-h-full bg-black/30">
    <div class="relative p-4 w-1/2 max-h-full">

      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow-sm">
        <!-- Modal header -->
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900">
            Редактирование модуля
          </h3>
          <button @click="props.moduleStore.switchEditModal" type="button"
                  class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                  data-modal-hide="authentication-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Закрыть</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="py-4 px-7">
          <form class="space-y-4" @submit.prevent="props.moduleStore.updateModule(module.id, module)">
            <!-- Поле для названия курса -->
            <div>
              <label for="name"
                     class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                Название модуля
              </label>
              <input
                type="text"
                name="name"
                v-model="module.name"
                id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                placeholder="Введите название курса"
                required
              />
            </div>

            <div>
              <label for="description" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                Описание модуля
              </label>
              <textarea
                name="description"
                id="description"
                v-model="module.description"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 min-h-40"
                placeholder="Введите описание курса"
                :maxlength="256"
                required
              ></textarea>

            </div>


            <div>
              <label for="courses"
                     class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Курс модуля</label>
              <select id="courses" name="tags"
                      v-model="module.course_id"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
              >
                <template v-for="course in props.coursesStore.courses.data" :key="course.id">
                  <option :value="course.id">
                    {{ course.name }}
                  </option>
                </template>
              </select>
            </div>

            <div>
              <label for="rating"
                     class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                Порядок
              </label>
              <input
                type="number"
                name="order"
                id="order"
                v-model="module.order"
                min="0"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                placeholder="Введите порядок в котором должен отображаться модуль"
                required
              />
            </div>

            <div
              @click="lessonStore.openModal('create')"
              type="button"
              class="flex flex-row justify-between py-5"
            >
              <label for="tags"
                     class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Уроки</label>
              <button
                type="button"
                class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300
         dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center hover:scale-105 transition-transform duration-500"
              >
                Добавить урок
              </button>
            </div>

            <LessonTable/>

            <div class="mt-10 mb-10 mx-30 px-40">
              <button
                type="button"
                @click="props.moduleStore.updateModule(module.id,module)"
                v-if="!props.moduleStore.isUpdating"
                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
              >
                Сохранить
              </button>
              <button
                v-else
                disabled
                type="button" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                  <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                </svg>
                Сохранение
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>
