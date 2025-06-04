<script setup>
import CourseList from "@/components/courses/CourseList.vue";
import {useCourseStore} from "@/stores/courseStore.js";
import {onMounted, ref, watch} from "vue";
import CourseSearch from "@/components/courses/CourseSearch.vue";


const courseStore = useCourseStore()

onMounted(() => {
  courseStore.fetchCategories()
})

const searchText = ref('');

watch([courseStore.selectedCategories, searchText], async ([categories, name]) => {
  const filter = {
    name,
    'filter[tags][id][]': categories
  };
  await courseStore.fetch(1, 8, filter);
});
</script>

<template>
  <div class="text-gray-900">
    <main>
      <div class="m-4 lg:m-20 flex flex-col lg:flex-row justify-between">
        <div class="flex flex-col justify-between">
          <section class="flex flex-col gap-y-1">
            <h1 class="text-3xl lg:text-6xl font-bold">Изучайте новые технологии</h1>
            <span class="text-3xl lg:text-6xl font-bold text-blue-600">вместе с нами!</span>
          </section>

          <!-- Categories -->
          <section class="flex flex-wrap gap-2 lg:gap-4 mb-6 lg:mb-10 w-full lg:w-3/4">
            <router-link
              v-for="category in courseStore.categories"
              :key="category.id"
              :to="{ name: 'courses' }"
              @click.prevent="courseStore.toggleCategory(category.id)"
            >
              <button
                class="px-3 py-2 lg:px-6 lg:py-3 rounded-lg lg:rounded-xl transition-all duration-300 shadow-sm hover:shadow-md text-sm lg:text-base"
                :class="{
                  'bg-gradient-to-r from-blue-600 to-purple-600 text-white': courseStore.selectedCategories.includes(category.id),
                  'bg-white text-gray-800 border border-gray-200 hover:border-blue-300': !courseStore.selectedCategories.includes(category.id)
                }"
              >
                {{ category.name }}
              </button>
            </router-link>
          </section>

          <CourseSearch />
        </div>

        <!-- Hero Image (hidden on mobile) -->
        <img src="@/assets/programmer.svg" class="hidden lg:block"/>
      </div>

      <!-- Stats Section -->
      <section class="bg-gradient-to-r from-blue-600 to-emerald-500 py-3">
        <div class="container mx-auto px-4 lg:px-6">
          <div class="flex flex-col md:flex-row justify-center items-center gap-3 lg:gap-6 bg-white rounded-xl lg:rounded-3xl px-4 py-3 lg:px-4 lg:py-2 shadow-md max-w-4xl mx-auto">
            <div class="flex items-center">
              <div class="p-1 lg:p-2 rounded-full bg-blue-100 mr-2 lg:mr-3">
                <svg class="w-4 lg:w-5 h-4 lg:h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs lg:text-base text-gray-800 font-semibold">Свыше 100 пользователей</p>
              </div>
            </div>

            <div class="flex items-center">
              <div class="p-1 lg:p-2 rounded-full bg-purple-100 mr-2 lg:mr-3">
                <svg class="w-4 lg:w-5 h-4 lg:h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div>
                <p class="text-xs lg:text-base text-gray-800 font-semibold">Более 10 курсов</p>
              </div>
            </div>

            <div class="flex items-center">
              <div class="p-1 lg:p-2 rounded-full bg-green-100 mr-2 lg:mr-3">
                <svg class="w-4 lg:w-5 h-4 lg:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs lg:text-base font-semibold text-gray-800">Средняя оценка курсов 4.0+</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Courses Sections -->
      <section class="py-8 lg:py-12 px-4 lg:px-50">
        <h2 class="text-3xl lg:text-5xl font-semibold mb-8 lg:mb-14">Программа обучения для каждого</h2>

        <!-- Adult Courses -->
        <div class="bg-black text-white p-6 lg:p-12 rounded-2xl lg:rounded-4xl mb-6 flex flex-col lg:flex-row justify-between h-auto lg:h-105 relative shadow-xl">
          <div class="flex flex-col justify-between">
            <div>
              <h3 class="text-2xl lg:text-3xl font-bold mb-2 lg:mb-3">Для взрослых</h3>
              <p class="text-base lg:text-xl pb-2">Смените профессию, получите новые навыки, запустите карьеру</p>

              <div class="mt-3 flex flex-col      lg:grid  lg:grid-cols-2 lg:grid-flow-row gap-5">
                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-blue-400 to-purple-400">
                  <button class=" bg-black text-white rounded-[calc(0.5rem-2px)] py-3 px-4">
                    Людям которые хотят попробовать себя в чем-то новом
                  </button>
                </div>
                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-green-400 to-yellow-400">
                  <button class="w-full bg-black text-white rounded-[calc(0.5rem-2px)] py-3 px-4">
                    Для начинающих
                  </button>
                </div>

                <!-- Кнопка 3 -->
                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-purple-400 to-pink-400">
                  <button class="bg-black text-white rounded-[cal12c(0.5rem-2px)] py-3 px-4">
                    Для тех, кто хочет улучшить свои знания
                  </button>
                </div>
              </div>
            </div>
            <button class="w-fit hover:scale-105 transition-transform duration-500  hover:shadow-lg  mt-4 lg:mt-0 py-2 px-10 lg:py-3 bg-white text-black border-white border rounded-lg lg:rounded-xl text-sm lg:text-lg font-bold">
              <RouterLink class="flex flex-row items-center justify-center" :to="{name: 'courses'}">
                <p>Выбрать из 11 курсов</p>
                <img class="ml-2 w-4 lg:w-7" src="@/assets/arrow.svg"/>
              </RouterLink>
            </button>
          </div>
          <img src="@/assets/man.svg" class="hidden lg:block w-sm h-sm absolute right-20 bottom-0">
        </div>

        <!-- Kids Courses -->
        <div class="bg-black text-white p-6 lg:p-12 rounded-2xl lg:rounded-4xl mt-6 lg:mt-20 mb-6 flex flex-col lg:flex-row justify-between h-auto lg:h-105 relative shadow-xl">
          <div class="flex flex-col justify-between">
            <div>
              <h3 class="text-2xl lg:text-3xl font-bold mb-2 lg:mb-3">Для детей</h3>
              <p class="text-base lg:text-xl pb-2">Откройте новые горизонты, раскройте скрытые таланты</p>

              <div class="mt-3 flex flex-col lg:grid  lg:grid-cols-2 lg:grid-flow-row gap-5">
                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-green-400 to-yellow-400">
                  <button class="w-full bg-black text-white rounded-[calc(0.5rem-2px)] py-3 px-4">
                    Тем, кто увлекается программированием
                  </button>
                </div>
                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-blue-400 to-purple-400">
                  <button class="bg-black text-white rounded-[calc(0.5rem-2px)] py-3 px-4">
                    Тем, кого увлекают точные науки
                  </button>
                </div>

                <div class="lg:w-fit p-[2px] rounded-lg bg-gradient-to-r from-purple-400 to-pink-400">
                  <button class=" bg-black text-white rounded-[calc(0.5rem-2px)] py-3 px-4">
                    Для открытия новых увлечений
                  </button>
                </div>
              </div>
            </div>
            <button class="w-fit hover:scale-105 transition-transform duration-500  hover:shadow-lg  mt-4 lg:mt-0 py-2 px-10 lg:py-3 bg-white text-black border-white border rounded-lg lg:rounded-xl text-sm lg:text-lg font-bold">
              <RouterLink class="flex flex-row items-center justify-center" :to="{name: 'courses'}">
                <p>Выбрать из 11 курсов</p>
                <img class="ml-2 w-4 lg:w-7" src="@/assets/arrow.svg"/>
              </RouterLink>
            </button>
          </div>
          <img src="@/assets/child.svg" class="hidden lg:block w-sm h-sm absolute right-20 bottom-0">
        </div>

        <!-- All Courses -->
        <h2 class="text-3xl lg:text-5xl font-semibold mb-8 lg:mb-14">Наши курсы</h2>
        <div class="flex flex-col items-center">
          <CourseList/>
          <RouterLink  :to="{name: 'courses'}" class="mt-8 lg:mt-15 w-full lg:w-fit text-white bg-gradient-to-r from-blue-600 to-emerald-500 font-medium rounded-lg text-base lg:text-xl px-5 lg:px-7 py-2 lg:py-3 text-center shadow-md hover:shadow-lg hover:scale-105 transition-transform duration-500">
            Все курсы
          </RouterLink>
        </div>
      </section>
    </main>
  </div>
</template>

<style scoped>
/* Добавлены медиа-запросы для px-50 */
@media (min-width: 1024px) {
  .lg\:px-50 {
    padding-left: 12.5rem;
    padding-right: 12.5rem;
  }
}
</style>
