<script setup>
import {useCourseStore} from "@/stores/courseStore.js";
import CourseCard from "@/components/courses/CourseCard.vue";
import {onMounted} from "vue";
import Paginator from "@/components/navigation/Paginator.vue";

const courseStore = useCourseStore()
onMounted(() => {
  courseStore.fetch()
})


</script>

<template>
  <div v-if="courseStore.isLoading">Загрузка...</div>
  <div v-else-if="courseStore.error">Ошибка: {{ courseStore.error }}</div>

  <div>
    <div v-if="courseStore.isLoading">Загрузка...</div>
    <div v-else-if="courseStore.error">Ошибка: {{ courseStore.error }}</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <template v-if="courseStore.courses && courseStore.courses.data">
        <CourseCard
          v-for="course in courseStore.courses.data.slice(0, 6)"
          :key="course.id"
          :course="course"
        />

      </template>
    </div>
  </div>
</template>

<style scoped>

</style>
