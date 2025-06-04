<script setup>
import {ref, onMounted} from 'vue'
import {useRoute} from 'vue-router'
import {useCourseStore} from "@/stores/courseStore.js";
import Comments from "@/components/Comments.vue";

const route = useRoute()
const openModules = ref([0])
const selectedLesson = ref(null)


function isLessonCompleted(lesson) {
  return lesson.is_completed || false
}

async function toggleLessonCompletion(lesson) {
  try {
    const newStatus = !lesson.is_completed
    await courseStore.toggleLessonCompletion(lesson.id, newStatus)
    lesson.is_completed = newStatus
  } catch (error) {
    console.error('Ошибка при обновлении статуса урока:', error)
  }
}

const isLoading = ref(false)
const courseStore = useCourseStore()

onMounted(async () => {
  isLoading.value = true
  await courseStore.fetchById(route.params.id)
  course.value = courseStore.detailCourse
  if (course.value.modules.length > 0 && course.value.modules[0].lessons.length > 0) {
    selectedLesson.value = course.value.modules[0].lessons[0]
  }
  isLoading.value = false
})
const course = ref()

function toggleModule(index) {
  const idx = openModules.value.indexOf(index)
  if (idx > -1) {
    openModules.value.splice(idx, 1)
  } else {
    openModules.value.push(index)
  }
}

function selectLesson(lesson) {
  selectedLesson.value = lesson
}

function getModuleIndex(lesson) {
  return course.value?.modules?.findIndex(m =>
    m.lessons?.some(l => l.id === lesson.id)
  ) ?? -1
}

function getLessonIndex(lesson) {
  const module = course.value.modules.find(m =>
    m.lessons.some(l => l.id === lesson.id)
  )
  return module ? module.lessons.findIndex(l => l.id === lesson.id) : -1
}

function hasNextLesson(lesson) {
  const moduleIndex = getModuleIndex(lesson)
  const lessonIndex = getLessonIndex(lesson)

  if (lessonIndex < course.value.modules[moduleIndex].lessons.length - 1) {
    return true
  }

  return moduleIndex < course.value.modules.length - 1
}

function hasPreviousLesson(lesson) {
  const moduleIndex = getModuleIndex(lesson)
  const lessonIndex = getLessonIndex(lesson)

  return lessonIndex > 0 || moduleIndex > 0
}

function selectNextLesson(lesson) {
  const moduleIndex = getModuleIndex(lesson)
  const lessonIndex = getLessonIndex(lesson)

  if (lessonIndex < course.value.modules[moduleIndex].lessons.length - 1) {
    selectLesson(course.value.modules[moduleIndex].lessons[lessonIndex + 1])
  } else if (moduleIndex < course.value.modules.length - 1) {
    selectLesson(course.value.modules[moduleIndex + 1].lessons[0])
  }
}

function selectPreviousLesson(lesson) {
  const moduleIndex = getModuleIndex(lesson)
  const lessonIndex = getLessonIndex(lesson)

  if (lessonIndex > 0) {
    selectLesson(course.value.modules[moduleIndex].lessons[lessonIndex - 1])
  } else if (moduleIndex > 0) {
    const prevModule = course.value.modules[moduleIndex - 1]
    selectLesson(prevModule.lessons[prevModule.lessons.length - 1])
  }
}

</script>

<template>
  <div v-if="isLoading"
       class="flex flex-col justify-center items-center h-110 text-lg">
    <div role="status">
      <svg aria-hidden="true"
           class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
           viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
          fill="currentColor"/>
        <path
          d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
          fill="currentFill"/>
      </svg>
      <span class="sr-only">Загрузка...</span>
    </div>
  </div>
  <div v-else
       class="flex flex-col lg:flex-row  bg-gradient-to-br from-gray-50 to-blue-50">

    <aside
      class="bg-white lg:w-80 shadow-xl p-6 space-y-4 lg:h-screen lg:sticky lg:top-0 overflow-y-auto">
      <div class="flex items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ course?.name }}</h2>
        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Новинка</span>
      </div>

      <div class="relative mb-4">
        <input
          type="text"
          placeholder="Поиск по урокам..."
          class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>

      <div class="space-y-2">
        <div
          v-for="(module, i) in course?.modules"
          :key="module.id"
          class="bg-white rounded-lg overflow-hidden shadow-sm"
        >
          <button
            @click="toggleModule(i)"
            class="w-full flex justify-between items-center p-3 text-left font-medium text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
              {{ module.name }}
            </span>
            <svg
              class="w-5 h-5 text-gray-500 transform transition-transform"
              :class="{ 'rotate-90': openModules.includes(i) }"
              fill="none" viewBox="0 0 24 24" stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7"/>
            </svg>
          </button>

          <div v-show="openModules.includes(i)" class="px-3 pb-2 space-y-1">
            <a
              v-for="lesson in module.lessons"
              :key="lesson.id"
              @click="selectLesson(lesson)"
              class="block px-3 py-2 text-sm rounded-md transition-colors"
              :class="{
                'bg-blue-50 text-blue-700': selectedLesson?.id === lesson.id,
                'text-gray-700 hover:bg-gray-100': selectedLesson?.id !== lesson.id
              }"
            >
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ lesson.name }}
              </span>
            </a>
          </div>
        </div>
      </div>
    </aside>

    <div class="flex-1 flex flex-col h-flex">
      <main class="flex-1 p-6 lg:p-8">
        <div v-if="selectedLesson" class=" mx-auto bg-white rounded-xl shadow-md overflow-hidden">
          <div class="p-6 md:p-8">
            <div class="flex justify-between items-start mb-6">
              <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                  {{ selectedLesson.name }}</h1>
                <div class="flex items-center text-sm text-gray-500">
                  <span class="mr-3">Глава {{ getModuleIndex(selectedLesson) + 1 }}</span>
                  <span>Урок {{ getLessonIndex(selectedLesson) + 1 }}</span>
                </div>
              </div>
              <button class="p-2 text-gray-400 hover:text-blue-500 rounded-full hover:bg-blue-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
              </button>
            </div>


            <div class="prose max-w-none prose-xl text-black "
                 v-html="selectedLesson.content"></div>


            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
              <button
                v-if="hasPreviousLesson(selectedLesson)"
                @click="selectPreviousLesson(selectedLesson)"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7"/>
                </svg>
                Предыдущий урок
              </button>
              <div v-else></div>

              <div class="flex items-center gap-4 text-base">
                <label class="inline-flex items-center cursor-pointer">
                  <input
                    type="checkbox"
                    class="hidden peer"
                    :checked="isLessonCompleted(selectedLesson)"
                    @change="toggleLessonCompletion(selectedLesson)"
                  >
                  <div
                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                  <span class="ml-3 text-sm font-medium text-gray-700">Пройден</span>
                </label>

                <button
                  v-if="hasNextLesson(selectedLesson)"
                  @click="selectNextLesson(selectedLesson)"
                  class="inline-flex items-center px-6 py-4 border border-transparent text-lg font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                >
                  Следующий урок
                  <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-else
             class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Выберите урок</h3>
          <p class="mt-1 text-sm text-gray-500">Начните обучение, выбрав урок из списка слева</p>
        </div>
      </main>
      <div class="w-full px-6 lg:px-8 pb-8">
        <Comments v-if="selectedLesson" :lesson-id="selectedLesson.id" :key="selectedLesson.id"/>
      </div>
    </div>

  </div>
</template>

<style>

.prose .image-style-align-left {
  float: left;
  margin-right: 1rem;
  margin-bottom: 1rem;

}

.prose .image-style-align-right {
  float: right;
  margin-left: 1rem;
  margin-bottom: 1rem;
}


</style>
