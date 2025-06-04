<script setup>
import {useAuthModalStore} from "@/stores/authModalStore.js";
import {useUserStore} from "@/stores/userStore.js";
import {ref, watch} from "vue";

const authModalStore = useAuthModalStore();
const userStore = useUserStore();

const formData = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: ""
});

const errors = ref({
  name: [],
  email: [],
  password: [],
  password_confirmation: [],
  general: []
});

// Валидация email
const validateEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
};

// Валидация формы
const validateForm = () => {
  clearErrors();
  let isValid = true;

  if (authModalStore.currentPage === authModalStore.pages.Register) {
    if (!formData.value.name) {
      errors.value.name.push("Имя пользователя обязательно");
      isValid = false;
    } else if (formData.value.name.length < 3) {
      errors.value.name.push("Имя должно содержать минимум 3 символа");
      isValid = false;
    }
  }

  if (!formData.value.email) {
    errors.value.email.push("Email обязателен");
    isValid = false;
  } else if (!validateEmail(formData.value.email)) {
    errors.value.email.push("Введите корректный email");
    isValid = false;
  }

  if (!formData.value.password) {
    errors.value.password.push("Пароль обязателен");
    isValid = false;
  } else if (formData.value.password.length < 6) {
    errors.value.password.push("Пароль должен содержать минимум 6 символов");
    isValid = false;
  }

  if (authModalStore.currentPage === authModalStore.pages.Register) {
    if (!formData.value.password_confirmation) {
      errors.value.password_confirmation.push("Подтверждение пароля обязательно");
      isValid = false;
    } else if (formData.value.password !== formData.value.password_confirmation) {
      errors.value.password_confirmation.push("Пароли не совпадают");
      isValid = false;
    }
  }

  return isValid;
};

// Обработка входа
const handleLogin = async () => {
  if (!validateForm()) return;

  try {
    await userStore.login(formData.value, authModalStore);
  } catch (error) {
    handleApiError(error);
  }
};


const handleRegister = async () => {
  if (!validateForm()) return;

  try {
    await userStore.register(formData.value, authModalStore);
  } catch (error) {
    handleApiError(error);
  }
};


const handleApiError = (error) => {
  clearErrors();

  if (error.response?.status === 422) {
    const {errors: apiErrors} = error.response.data;
    Object.keys(apiErrors).forEach(field => {
      if (errors.value.hasOwnProperty(field)) {
        errors.value[field] = apiErrors[field];
      } else {

        errors.value.general = [...errors.value.general, ...apiErrors[field]];
      }
    });
  } else if (error.response?.status === 401) {
    errors.value.general = ["Неверный логин или пароль"];
  } else {
    const message = error.response?.data?.message || error.message || "Произошла ошибка. Пожалуйста, попробуйте позже.";
    errors.value.general = [message];
  }
};

const clearErrors = () => {
  errors.value = {
    name: [],
    email: [],
    password: [],
    password_confirmation: [],
    general: []
  };
};

watch(() => authModalStore.currentPage, () => {
  clearErrors();
  formData.value = {
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
  };
});
</script>

<template>
  <div id="authentication-modal" tabindex="-1" aria-hidden="true"
       class="flex items-center justify-center overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-screen max-h-full bg-black/30">
    <div class="relative p-4 w-full max-w-md max-h-full">

      <div v-if="authModalStore.currentPage === authModalStore.pages.Login"
           class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Вход</h3>
          <button @click="authModalStore.showModal" type="button"
                  class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Закрыть</span>
          </button>
        </div>


        <div class="p-4 md:p-5">
          <form class="space-y-4" @submit.prevent="handleLogin">
            <div>
              <label for="email"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
              <input v-model="formData.email" type="email" name="email" id="email"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     placeholder="name@company.com"
                     @input="errors.email = []">
              <div v-if="errors.email.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.email" :key="`email-${index}`">{{
                    error
                  }}
                </div>
              </div>
            </div>


            <div>
              <label for="password"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Пароль</label>
              <input v-model="formData.password" type="password" name="password" id="password"
                     placeholder="••••••••"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     @input="errors.password = []">
              <div v-if="errors.password.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.password" :key="`password-${index}`">{{
                    error
                  }}
                </div>
              </div>
            </div>


            <div v-if="errors.general.length" class="text-red-500 text-sm">
              <div v-for="(error, index) in errors.general" :key="`general-${index}`">{{
                  error
                }}
              </div>
            </div>
            <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Войти в аккаунт
            </button>


            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
              Не зарегистрированы?
              <button @click="authModalStore.replacePage()"
                      class="text-blue-700 hover:underline dark:text-blue-500">
                Создать аккаунт
              </button>
            </div>
          </form>
        </div>
      </div>


      <div v-else-if="authModalStore.currentPage === authModalStore.pages.Register"
           class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Регистрация</h3>
          <button @click="authModalStore.showModal" type="button"
                  class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Закрыть</span>
          </button>
        </div>


        <div class="p-4 md:p-5">
          <form class="space-y-4" @submit.prevent="handleRegister">
            <!-- General Errors -->

            <div>
              <label for="name"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Имя
                пользователя</label>
              <input v-model="formData.name" name="name" id="name"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     placeholder="Alexander Vasilkov"
                     @input="errors.name = []">
              <div v-if="errors.name.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.name" :key="`name-${index}`">{{ error }}</div>
              </div>
            </div>

            <div>
              <label for="email"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail</label>
              <input v-model="formData.email" type="email" name="email" id="email"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     placeholder="name@company.com"
                     @input="errors.email = []">
              <div v-if="errors.email.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.email" :key="`email-${index}`">{{
                    error
                  }}
                </div>
              </div>
            </div>

            <div>
              <label for="password"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Пароль</label>
              <input v-model="formData.password" type="password" name="password" id="password"
                     placeholder="••••••••"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     @input="errors.password = []">
              <div v-if="errors.password.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.password" :key="`password-${index}`">{{
                    error
                  }}
                </div>
              </div>
            </div>

            <div>
              <label for="password_confirmation"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Подтверждение пароля
              </label>
              <input v-model="formData.password_confirmation" type="password"
                     name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     @input="errors.password_confirmation = []">
              <div v-if="errors.password_confirmation.length" class="text-red-500 text-sm mt-1">
                <div v-for="(error, index) in errors.password_confirmation"
                     :key="`password-confirm-${index}`">{{ error }}
                </div>
              </div>
            </div>

            <div v-if="errors.general.length" class="text-red-500 text-sm">
              <div v-for="(error, index) in errors.general" :key="`general-${index}`">{{
                  error
                }}
              </div>
            </div>


            <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Зарегистрировать аккаунт
            </button>

            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
              Уже есть аккаунт?
              <button @click="authModalStore.replacePage()"
                      class="text-blue-700 hover:underline dark:text-blue-500">
                Войти
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Ваши стили */
</style>
