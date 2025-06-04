<script setup>
const props = defineProps({
  course: Object
})
</script>

<template>
  <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <!-- Верхняя цветная полоса -->
    <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600"></div>

    <div class="p-6">
      <!-- Заголовок и рейтинг -->
      <div class="flex justify-between items-start mb-3">
        <RouterLink
          :to="{ name: 'course-detail', params: { id: props.course.id } }"
          class="group"
        >
          <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
            {{ props.course.name }}
          </h3>
        </RouterLink>
        <div class="flex items-center bg-blue-100 px-2 py-1 rounded-full">
          <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="text-xs font-semibold text-gray-800">{{ props.course.rating }}</span>
        </div>
      </div>

      <!-- Описание -->
      <p class="text-gray-600 text-sm mb-4 line-clamp-3">
        {{ props.course.description }}
      </p>

      <!-- Теги -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span
          v-for="tag in props.course.tags.slice(0, 3)"
          :key="tag.id"
          class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full"
        >
          {{ tag.name }}
        </span>
      </div>

      <!-- Детали курса -->
      <div class="flex justify-between items-center text-sm">
        <div class="flex items-center text-gray-500">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span>{{ props.course.duration }} ч.</span>
        </div>

        <div class="flex items-center text-gray-500">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <span>{{ props.course.difficulty_level }}</span>
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

/* Анимация при наведении */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>
