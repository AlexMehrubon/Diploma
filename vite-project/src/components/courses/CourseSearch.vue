<script setup>
import { ref, watch } from 'vue'
import { useCourseStore } from '@/stores/courseStore'
import { useRouter } from 'vue-router'

const router = useRouter()
const courseStore = useCourseStore()
const searchText = ref('')
const isDropdownOpen = ref(false)

let debounceTimer = null
const debounceDelay = 300

watch(searchText, (newValue) => {
  clearTimeout(debounceTimer)
  if (newValue.trim().length === 0) {
    courseStore.searchResults.value = []
    isDropdownOpen.value = false
    return
  }

  debounceTimer = setTimeout(() => {
    courseStore.searchCourses(newValue)
    isDropdownOpen.value = true
  }, debounceDelay)
})

const handleSearch = () => {
  if (searchText.value.trim()) {
    courseStore.searchCourses(searchText.value)
  }
}

const selectCourse = (course) => {
  searchText.value = course.name
  isDropdownOpen.value = false
  router.push(`/courses/${course.id}`)
}

const closeDropdown = () => {
  setTimeout(() => {
    isDropdownOpen.value = false
  }, 200)
}
</script>

<template>
  <div class="relative max-w-2xl w-full">
    <section class="flex flex-col sm:flex-row gap-3 lg:gap-4 w-full">
      <input
        v-model="searchText"
        type="text"
        placeholder="Ищите какой-то определённый курс?"
        class="flex-1 border border-gray-300 rounded-lg lg:rounded-xl px-4 py-2 lg:px-6 lg:py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm lg:text-base"
        @focus="isDropdownOpen = searchText.length > 0"
        @blur="closeDropdown"
        @keyup.enter="handleSearch"
      >
      <button
        class="px-4 py-2 lg:px-6 lg:py-3 bg-gray-800 text-white rounded-lg lg:rounded-xl hover:bg-gray-700 transition-all duration-300 text-sm lg:text-base whitespace-nowrap"
        @click="handleSearch"
      >
        Найти
      </button>
    </section>

    <!-- Выпадающий список с результатами -->
    <div
      v-if="isDropdownOpen"
      class="absolute z-50 mt-2 w-full bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200"
    >
      <div v-if="courseStore.isSearching" class="p-4 text-center text-gray-500">
        <div class="animate-pulse">Поиск курсов...</div>
      </div>

      <div v-else-if="courseStore.searchResults.length === 0 && searchText" class="p-4 text-center text-gray-500">
        Курсы не найдены
      </div>

      <div v-else class="max-h-96 overflow-auto">
        <div
          v-for="course in courseStore.searchResults"
          :key="course.id"
          class="p-4 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0"
          @mousedown.prevent="selectCourse(course)"
        >
          <!-- Карточка курса в стиле CourseCard -->
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Левая часть (текст) -->
            <div class="flex-1">
              <!-- Заголовок и рейтинг -->
              <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-bold text-gray-900 line-clamp-2">
                  {{ course.name }}
                </h3>
                <div v-if="course.rating" class="flex items-center bg-blue-100 px-2 py-1 rounded-full shrink-0">
                  <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <span class="text-xs font-semibold text-gray-800">{{ course.rating }}</span>
                </div>
              </div>

              <!-- Описание -->
              <p v-if="course.description" class="text-gray-600 text-sm mb-3 line-clamp-2">
                {{ course.description }}
              </p>

              <!-- Теги -->
              <div v-if="course.tags?.length" class="flex flex-wrap gap-1 mb-3">
                <span
                  v-for="tag in course.tags.slice(0, 3)"
                  :key="tag.id"
                  class="px-2 py-0.5 bg-gray-100 text-gray-800 text-xs rounded-full"
                >
                  {{ tag.name }}
                </span>
              </div>

              <!-- Детали курса -->
              <div class="flex flex-wrap gap-4 text-sm">
                <div v-if="course.duration" class="flex items-center text-gray-500">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span>{{ course.duration }} ч.</span>
                </div>

                <div v-if="course.difficulty_level" class="flex items-center text-gray-500">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                  <span>{{ course.difficulty_level }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>
