<script setup>
import {ref, onMounted, watch} from 'vue'
import {useRoute} from 'vue-router'
import {useCourseStore} from "@/stores/courseStore.js"
import {useUserStore} from "@/stores/userStore.js";
import axios from 'axios'
import {useAuthModalStore} from "@/stores/authModalStore.js";

const route = useRoute()
const courseStore = useCourseStore()
const userStore = useUserStore()
const authModalStore = useAuthModalStore()
const isEnrolled = ref(false)
const progress = ref(0)
const moduleProgress = ref([])
const isLoading = ref(false)


onMounted(async () => {
  isLoading.value = true
  await courseStore.fetchById(route.params.id)

  if (userStore.isAuthenticated) {
    await checkEnrollmentStatus()
  }
  isLoading.value = false
})

const checkEnrollmentStatus = async () => {
  try {
    const token = userStore.token
    const response = await axios.get(
      `http://localhost:8003/api/v1/courses/${route.params.id}/enrollment-status`,
      {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      }
    )
    isEnrolled.value = response.data.is_enrolled
    console.log(isEnrolled.value)
    if (isEnrolled.value) {
      progress.value = response.data.progress
      moduleProgress.value = response.data.module_progress
    }
  } catch (error) {
    console.error('Error checking enrollment status:', error)
  }
}
const tryEnroll = async () => {
  if (!userStore.isAuthenticated) {
    authModalStore.showModal()
    return
  }
  await enrollCourse()
}

const enrollCourse = async () => {
  try {
    const token = userStore.token
    await axios.post(
      `http://localhost:8003/api/v1/courses/${route.params.id}/enroll`,
      {},
      {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      }
    );
    isEnrolled.value = true
    progress.value = 0
    moduleProgress.value = []
  } catch (error) {
    console.error('Error enrolling to course:', error)
  }
}

watch(() => userStore.isAuthenticated, async (newVal) => {
    isLoading.value = true
    if (newVal) {
      await courseStore.fetchById(route.params.id)
      await checkEnrollmentStatus()
    } else
      isEnrolled.value = false
    isLoading.value = false
  }
)
</script>

<template>
  <div v-if="!isLoading"
       class="min-h-screen pb-20 bg-gradient-to-b from-blue-600 via-purple-600 to-blue-500">
    <!-- Шапка курса -->
    <div class="pt-16 pb-32">
      <div class="container mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-6xl mx-auto">
          <div class="p-8 md:p-10">
            <div class="flex flex-col md:flex-row gap-8">
              <!-- Основная информация -->
              <div class="flex-1">
                <div class="flex items-center mb-4 flex-wrap gap-3">
                  <span
                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ courseStore.detailCourse?.difficulty_level }}
                  </span>
                  <span v-if="courseStore.detailCourse?.rating"
                        class="flex items-center text-yellow-600">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ courseStore.detailCourse?.rating }}
                  </span>
                </div>

                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                  {{ courseStore.detailCourse?.name }}
                </h1>

                <p class="text-lg text-gray-600 mb-6">
                  {{ courseStore.detailCourse?.description }}
                </p>

                <div class="flex flex-wrap gap-2 mb-6">
                  <span
                    v-for="tag in courseStore.detailCourse?.tags"
                    :key="tag.id"
                    class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm"
                  >
                    {{ tag.name }}
                  </span>
                </div>

                <div v-if="isEnrolled" class="mb-6">
                  <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">Прогресс: {{ progress }}%</span>
                    <span class="text-xs text-gray-500">{{
                        courseStore.detailCourse?.duration
                      }} ч.</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div
                      class="bg-gradient-to-r from-blue-500 to-purple-600 h-2.5 rounded-full"
                      :style="{ width: progress + '%' }"
                    ></div>
                  </div>
                </div>


                <RouterLink v-if="isEnrolled"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700
                  hover:to-purple-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg
                  transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            :to="{ name: 'course-lesson', params: { id: route.params.id } }">
                  Перейти к урокам
                </RouterLink>

                <button
                  v-else
                  @click="tryEnroll"
                  class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Попробовать бесплатно
                </button>
              </div>

              <!-- Доп. информация -->
              <div class="md:w-80 space-y-4">
                <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                  <h3 class="font-semibold text-gray-800 mb-2">Средняя зарплата</h3>
                  <p class="text-2xl font-bold text-blue-600">
                    {{ courseStore.detailCourse?.average_salary }} BYN
                  </p>
                  <p class="text-sm text-gray-500 mt-1">по данным за 2024</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                  <h3 class="font-semibold text-gray-800 mb-3">Детали курса</h3>
                  <div class="space-y-3">
                    <div class="flex items-center text-gray-700">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ courseStore.detailCourse?.duration }} часов обучения
                    </div>
                    <div class="flex items-center text-gray-700">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                      </svg>
                      {{ courseStore.detailCourse?.modules?.length }} модулей
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- О курсе -->
    <div class="container mx-auto px-4 -mt-20">
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 max-w-6xl mx-auto">
        <div class="p-8 md:p-10">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">О курсе</h2>
          <div class="prose max-w-none text-gray-700">
            <p class="whitespace-pre-line">{{ courseStore.detailCourse?.about }}</p>
          </div>
        </div>
      </div>

      <!-- Программа курса -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-16 max-w-6xl mx-auto">
        <div class="p-8 md:p-10">
          <h2 class="text-2xl font-bold text-gray-900 mb-8">Программа курса</h2>
          <div class="space-y-4">
            <div
              v-for="(module, index) in courseStore.detailCourse?.modules"
              :key="module.id"
              class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm"
            >
              <div class="flex items-start gap-4">
                <div
                  class="bg-gradient-to-tr from-blue-500 to-purple-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg">
                  {{ index + 1 }}
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ module.name }}</h3>
                  <p class="text-gray-600">{{ module.description }}</p>

                  <div v-if="isEnrolled" class="mt-4">
                    <div class="flex justify-between items-center mb-1 text-sm text-gray-500">
                      <span>Прогресс модуля: {{ module.progress?.value }}%</span>
                      <span>{{ module.progress?.completed_lessons }}/{{ module.progress?.total_lessons }} уроков</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-2">
                      <div
                        class="bg-gradient-to-r from-blue-500 to-purple-600 h-2.5 rounded-full"
                        :style="{ width: module?.progress?.value + '%' }"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Призыв к действию -->
      <div class="text-center max-w-6xl mx-auto">
        <button
          @click="enrollCourse"
          :disabled="isEnrolled"
          class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-10 rounded-full text-lg transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ isEnrolled ? 'Продолжить обучение' : 'Начать обучение бесплатно' }}
        </button>
      </div>
    </div>
  </div>
  <div v-else class="flex flex-col justify-center items-center h-110 text-lg">
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
</template>

<style scoped>
.prose {
  line-height: 1.6;
}

.prose p {
  margin-bottom: 1.25em;
}
</style>
