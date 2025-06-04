<script setup>
import BlueButton from "@/components/buttons/BlueButton.vue";
import {useUserStore} from "@/stores/userStore.js";
import {computed, ref} from "vue";
import {storeToRefs} from "pinia";
import {RouterLink} from 'vue-router';

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const formData = ref({
  ...currentUser.value
})

const fileInput = ref(null);
const isLoading = ref(false);
const errorMessage = ref('');
const avatarPreviewUrl = ref('');

const avatarUrl = computed(() => {
  return avatarPreviewUrl.value || currentUser.value?.image_url || '@/assets/default.png';
});

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleAvatarChange = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (!file.type.match('image.*')) {
    alert('Пожалуйста, выберите изображение');
    return;
  }

  if (file.size > 2 * 1024 * 1024) {
    alert('Изображение должно быть меньше 2MB');
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    avatarPreviewUrl.value = e.target.result;
  };
  reader.readAsDataURL(file);

};

const submitForm = async () => {
  try {
    isLoading.value = true;
    errorMessage.value = '';
    const avatarFile = fileInput.value.files[0];

    await userStore.updateProfile(formData.value, avatarFile);
    console.log("d")
  } catch (error) {
    console.error('Ошибка при обновлении профиля:', error);
    errorMessage.value = error.response?.data?.message || 'Произошла ошибка при сохранении';
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="container mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
      <!-- Боковая панель профиля -->
      <div class="lg:w-1/4">
        <div class="bg-gray-50 rounded-xl shadow-md p-8 top-8">
          <div class="flex flex-col items-center gap-6">
            <div class="relative">
              <img class="w-40 h-40 rounded-full object-cover border-4 border-blue-100"
                   :src="avatarUrl" alt="Фото профиля">

              <div class="absolute bottom-2 right-2 bg-blue-500 rounded-full p-2 cursor-pointer"
                   @click="triggerFileInput">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>

              <!-- Скрытый input для загрузки файла -->
              <input type="file"
                     ref="fileInput"
                     class="hidden"
                     accept="image/*"
                     @change="handleAvatarChange">


            </div>

            <div class="text-center">
              <h2 class="text-2xl font-bold text-gray-800">
                {{ currentUser.email }}
              </h2>
              <div class="mt-2 flex justify-center">
                <BlueButton>
                  <RouterLink :to="{name: 'profile'}">
                    Личный кабинет
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

      <!-- Основное содержимое - Форма настроек -->
      <div class="lg:w-3/4">
        <div class="bg-white rounded-xl shadow-md p-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-8">Настройки профиля</h1>

          <form class="space-y-8" @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Первый ряд -->
              <div>
                <label class="block text-gray-700 font-medium mb-2" for="name">Имя</label>
                <input
                  id="name"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                  placeholder="Введите ваше имя"
                  v-model="formData.name">
              </div>

              <div>
                <label class="block text-gray-700 font-medium mb-2" for="lastName">Фамилия</label>
                <input
                  id="lastName"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                  placeholder="Введите вашу фамилию"
                  v-model="formData.lastName">
              </div>

              <!-- Второй ряд -->
              <div>
                <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                <input
                  id="email"
                  type="email"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                  placeholder="Введите ваш email"
                  v-model="formData.email">
              </div>

              <div>
                <label class="block text-gray-700 font-medium mb-2" for="phone">Телефон</label>
                <input
                  id="phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                  placeholder="+375 (__) ___-____"
                  v-model="formData.phone">
              </div>


            </div>

            <!-- Отдельный блок для текстового поля -->
            <div>
              <label class="block text-gray-700 font-medium mb-2" for="about">О себе</label>
              <textarea
                v-model="formData.about"
                id="about"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="Расскажите немного о себе...">Frontend разработчик с опытом работы 3 года. Люблю Vue.js и создавать красивые интерфейсы.</textarea>
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="isLoading"
                class="inline-flex items-center justify-center p-0.5 mb-2 me-2
    hover:shadow-lg
    overflow-hidden font-medium rounded-lg bg-gray-800 text-white hover:scale-105 transition-transform duration-500 cursor-pointer">
                <span class="px-5 py-2 rounded-md">
                  {{ isLoading ? 'Загрузка...' : 'Сохранить изменения' }}
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<style scoped>

input, select, textarea {
  transition: all 0.2s ease;
}
</style>
