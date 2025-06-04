<script setup>
import {useAuthModalStore} from "@/stores/authModalStore.js";
import {onBeforeMount, onMounted, ref} from "vue";
import {useUserStore} from "@/stores/userStore.js";
import {useRoute, useRouter} from "vue-router";
import AuthModal from "@/components/AuthModal.vue";
import ProfileDropDown from "@/components/dropdowns/ProfileDropDown.vue";

const authModalStore = useAuthModalStore()
const userStore = useUserStore()
const router = useRouter()
const route = useRoute()

const isLoading = ref(true);
const isMenuOpen = ref(false);

onBeforeMount(async () => {
  await userStore.fetchCurrentUser();
  isLoading.value = false;
});

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
  <div v-if="isLoading" class="flex justify-center items-center h-screen">
    Загрузка...
  </div>
  <div v-else>
    <template v-if="authModalStore.isShowedModal">
      <AuthModal/>
    </template>
    <header class="bg-white shadow-md sticky top-0 z-50">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <!-- Логотип -->
          <div
            class="text-2xl md:text-3xl font-bold cursor-pointer hover:scale-105 transition-transform duration-500"
            @click="router.push({name:'home'})">
            <span class="text-blue-600">Pro</span>Study
          </div>

          <!-- Мобильное меню (бургер) -->
          <div class="md:hidden">
            <button @click="toggleMenu" class="text-gray-800 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>

          <!-- Навигация (десктоп) -->
          <nav class="hidden md:flex space-x-4 lg:space-x-8">
            <RouterLink class="text-black text-base lg:text-lg hover:text-blue-600 transition-colors duration-200"
                        :to="{name: 'courses'}">Каталог курсов
            </RouterLink>
          </nav>

          <!-- Кнопки авторизации (десктоп) -->
          <div class="hidden md:flex space-x-2">
            <template v-if="!userStore.isAuthenticated">
              <button
                class="px-3 py-1 sm:px-4 sm:py-2 border border-gray-500 rounded hover:shadow-lg hover:scale-105 transition-transform duration-500 cursor-pointer text-sm sm:text-base"
                @click="authModalStore.showModal()">
                Войти
              </button>
              <button
                class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-800 text-white rounded hover:shadow-lg hover:scale-105 transition-transform duration-500 cursor-pointer text-sm sm:text-base"
                @click="authModalStore.showModal(authModalStore.pages.Register)">
                Регистрация
              </button>
            </template>
            <template v-else-if="userStore.isAuthenticated">
              <ProfileDropDown/>
              <RouterLink
                v-if="userStore.isAdmin"
                :to="{name:'admin'}"
                class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-800 text-white rounded hover:shadow-lg hover:scale-105 transition-transform duration-500 cursor-pointer text-sm sm:text-base">
                Админка
              </RouterLink>
            </template>
          </div>
        </div>

        <!-- Мобильное меню (раскрывающееся) -->
        <div v-if="isMenuOpen" class="md:hidden pb-4">
          <div class="flex flex-col space-y-3">
            <RouterLink class="text-black hover:text-blue-600 transition-colors duration-200"
                        :to="{name: 'courses'}">Каталог курсов
            </RouterLink>
          </div>

          <div class="mt-4 space-y-2">
            <template v-if="!userStore.isAuthenticated">
              <button
                class="w-full px-4 py-2 border border-gray-500 rounded hover:shadow-lg transition-colors duration-500 cursor-pointer"
                @click="authModalStore.showModal()">
                Войти
              </button>
              <button
                class="w-full px-4 py-2 bg-gray-800 text-white rounded hover:shadow-lg transition-colors duration-500 cursor-pointer"
                @click="authModalStore.showModal(authModalStore.pages.Register)">
                Регистрация
              </button>
            </template>
            <template v-else-if="userStore.isAuthenticated">
              <div class="flex justify-center">
                <ProfileDropDown/>
              </div>
              <RouterLink
                v-if="userStore.isAdmin"
                :to="{name:'admin'}"
                class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded hover:shadow-lg transition-colors duration-500 cursor-pointer">
                Панель администратора
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </header>
  </div>
</template>

<style scoped>

</style>
