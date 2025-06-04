<script setup>
import { useRouter, useRoute } from 'vue-router';
import { ref } from 'vue';

const menuItems = [
  { id: '1', name: 'Курсы', url: '/admin/courses' },
  { id: '2', name: 'Пользователи', url: '/admin/users' },
];

const route = useRoute();
const router = useRouter();
const isMenuOpen = ref(false); // Для управления бургер-меню

function navigateTo(url) {
  router.push(url);
  if (window.innerWidth < 768) {
    isMenuOpen.value = false; // Закрываем меню на мобильных после выбора
  }
}
</script>

<template>
  <div class="flex flex-col gap-y-8 px-4 sm:px-6 md:px-8 lg:mx-20 mb-20 mt-6">
    <!-- Заголовок -->
    <p class="text-3xl sm:text-4xl font-bold">Администрирование</p>

    <!-- Контент -->
    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Мобильное меню -->
      <div class="lg:hidden relative z-20">
        <button
          @click="isMenuOpen = !isMenuOpen"
          class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md w-full flex justify-between items-center"
        >
          {{ isMenuOpen ? 'Скрыть меню' : 'Показать меню' }}
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              v-if="!isMenuOpen"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h7"
            ></path>
            <path
              v-else
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>

        <!-- Дропдаун меню -->
        <transition name="slide">
          <div
            v-show="isMenuOpen"
            class="absolute top-14 left-0 right-0 bg-white shadow-xl rounded-lg p-4 z-30"
          >
            <div
              v-for="item in menuItems"
              :key="item.id"
              @click="navigateTo(item.url)"
              class="py-2 cursor-pointer hover:text-blue-500 transition-colors"
              :class="{ 'font-bold text-blue-500': route.path === item.url }"
            >
              {{ item.name }}
            </div>
          </div>
        </transition>
      </div>

      <!-- Левое меню (на десктопе) -->
      <div class="hidden lg:block w-full lg:w-1/4 xl:w-1/5">
        <div class="bg-white rounded-s-3xl px-6 py-5 shadow-xl h-fit">
          <p class="text-xl font-semibold mb-4">Меню</p>
          <div
            v-for="item in menuItems"
            :key="item.id"
            @click="navigateTo(item.url)"
            class="flex items-center cursor-pointer py-3 px-2 my-2 rounded-md hover:bg-gray-100 transition-all duration-300"
            :class="{ 'font-bold text-blue-500 bg-blue-50': route.path === item.url }"
          >
            <p class="text-base sm:text-lg">{{ item.name }}</p>
          </div>
        </div>
      </div>

      <!-- Основной контент -->
      <div class="w-full">
        <div
          class="bg-white rounded-3xl px-6 py-5 shadow-xl min-h-[60vh]"
        >
          <RouterView />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
