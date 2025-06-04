<script setup>
import BlueButton from "@/components/buttons/BlueButton.vue";
import CourseCard from "@/components/courses/CourseCard.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {useUserStore} from "@/stores/userStore.js";

const isLoading = ref(false)
const courses = ref(null)
onMounted(async () => {
  isLoading.value = true
  courses.value = await axios.get(
    `http://localhost:8003/api/v1/user-courses`,
    {
      headers: {
        'Authorization': `Bearer ${useUserStore().token}`,
        'Accept': 'application/json'
      }
    }
  )
  isLoading.value = false
})

</script>

<template>
  <div class="container mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
      <!-- Боковая панель профиля -->
      <div class="lg:w-1/4">
        <div class="bg-gray-50 rounded-xl shadow-md p-8  top-8">
          <div class="flex flex-col items-center gap-6">
            <div class="relative">
              <img class="w-40 h-40 rounded-full object-cover border-4 border-blue-100"
                   :src="useUserStore().currentUser.image_url" alt="Фото профиля">
            </div>

            <div class="text-center">
              <h2 class="text-2xl font-bold text-gray-800">snikoloenak26@gmail.com</h2>
              <div class="mt-2 flex justify-center">
                <BlueButton>
                  <RouterLink :to="{name: 'settings'}">
                    Редактировать
                  </RouterLink>
                </BlueButton>
              </div>

              <div class="mt-6 space-y-2 text-gray-500">
                <div class="flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span>Регистрация: 14.03.2025</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Основное содержимое -->
      <div class="lg:w-3/4 space-y-12">
        <!-- Рекомендации -->
        <!--        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl   shadow-lg">-->
        <div class="bg-white rounded-xl p-6  shadow-lg border-1 border-purple-600 ">
          <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-1">
              <h3 class="text-2xl font-bold text-gray-800 mb-3">Присоединяйтесь в наш Telegram!</h3>
              <p class="text-gray-600 mb-4">
                В нашем Telegram-канале много интересного для программистов. Получайте уведомления о
                новых курсах, участвуйте в обсуждениях и будьте в курсе всех новостей!
              </p>
              <BlueButton>
                Присоединиться
              </BlueButton>
            </div>
            <div class="hidden md:block">
              <img src="@/assets/telegram.svg" class="w-40" alt="Telegram">
            </div>
          </div>
        </div>


        <!-- Все курсы пользователя -->
        <div>
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Ваши курсы</h3>
            <button class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
              Все курсы
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>


          <div>
            <div v-if="isLoading">Загрузка...</div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <template v-if="courses">
                <CourseCard
                  v-for="course in courses.data"
                  :key="course.id"
                  :course="course"
                />

              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Плавные переходы для кнопок */
button {
  transition: all 0.2s ease;
}

/* Анимация при наведении на карточки */
.bg-white {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bg-white:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
