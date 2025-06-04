<script setup>
import { ref, watch, onMounted } from 'vue'
import { useCourseStore } from '@/stores/courseStore'
import CourseCard from '@/components/courses/CourseCard.vue'
import CoursePaginator from '@/components/navigation/CoursePaginator.vue'

const courseStore = useCourseStore()
const searchText = ref('')
const isLoading = ref(false)

const loadData = async () => {
  try {
    isLoading.value = true
    await courseStore.fetchCategories()
    if (courseStore.courses.length < 1) {
      await courseStore.fetch()
    }
  } finally {
    isLoading.value = false
  }
}

const handleFilterChange = async ([categories, name]) => {
  try {
    isLoading.value = true
    const filter = {
      'filter[name]': name,
      'filter[tags][id][]': categories
    }
    await courseStore.fetch(1, 8, filter)
  } finally {
    isLoading.value = false
  }
}

watch([courseStore.selectedCategories, searchText], handleFilterChange)
onMounted(loadData)
</script>

<template>
  <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
    <div class="container mx-auto px-4 py-12">
      <!-- Заголовок -->
      <header class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
          Изучайте новые технологии вместе с нами!
        </h1>

        <!-- Категории -->
        <div class="flex flex-wrap justify-center gap-3 max-w-4xl mx-auto">
          <button
            v-for="category in courseStore.categories"
            :key="category.id"
            @click="courseStore.toggleCategory(category.id)"
            class="px-6 py-3 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md"
            :class="{
              'bg-gradient-to-r from-blue-500 to-purple-600 text-white': courseStore.selectedCategories.includes(category.id),
              'bg-white text-gray-800 border border-gray-200 hover:border-blue-300': !courseStore.selectedCategories.includes(category.id)
            }"
          >
            {{ category.name }}
          </button>
        </div>
      </header>

      <!-- Основной контент -->
      <main class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Фильтры -->
        <aside class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-md p-6 top-6 border border-gray-100">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Поиск курсов</h2>
            <div class="relative">
              <input
                v-model="searchText"
                type="text"
                placeholder="Название курса..."
                class="w-full flex-1 border border-gray-300 rounded-lg lg:rounded-xl pr-4 py-3 lg:pr-6 lg:py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm lg:text-base pl-10"
              >
              <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>

          </div>

          <!-- Статистика -->
          <div class="bg-white rounded-xl shadow-md p-6 mt-4 border border-gray-100">
            <div class="space-y-3">
              <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 mr-3">
                  <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Студентов</p>
                  <p class="font-semibold text-gray-800">100+</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 mr-3">
                  <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Курсов</p>
                  <p class="font-semibold text-gray-800">14+</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 mr-3">
                  <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Средний рейтинг</p>
                  <p class="font-semibold text-gray-800">4.0+</p>
                </div>
              </div>
            </div>
          </div>
        </aside>

        <!-- Список курсов -->
        <section class="lg:col-span-3">
          <div v-if="isLoading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
          </div>

          <template v-else>
            <div v-if="courseStore.courses?.data?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <CourseCard
                v-for="course in courseStore.courses.data"
                :key="course.id"
                :course="course"
              />
            </div>

            <div v-else class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <h3 class="mt-4 text-xl font-medium text-gray-900">Курсы не найдены</h3>
              <p class="mt-2 text-gray-600">Попробуйте изменить параметры поиска</p>
            </div>

            <CoursePaginator
              v-if="courseStore.courses?.data?.length > 0"
              :store="courseStore"
              class="mt-8"
            />
          </template>
        </section>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Анимации */
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}

/* Градиент для фона */
.bg-gradient-to-b {
  background-image: linear-gradient(to bottom, var(--tw-gradient-stops));
}
</style>
