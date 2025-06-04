<script setup>
import {ref, onMounted, onUnmounted, watch} from "vue";
import {useUserStore} from "@/stores/userStore.js";

const userStore = useUserStore();
const isHidden = ref(true);
const dropdownRef = ref(null);

const toggleHide = () => {
  isHidden.value = !isHidden.value;
};

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isHidden.value = true;
  }
};
const avatar = ref('')
onMounted(async () => {
  document.addEventListener("click", handleClickOutside);
  avatar.value = userStore.currentUser?.image_url
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});

watch(() => userStore.currentUser, async (newVal) => {
    if (newVal)
      avatar.value = userStore.currentUser?.image_url
  }
)


</script>

<template>
  <div class="relative" ref="dropdownRef">
    <button @click="toggleHide" class="flex items-center justify-center text-sm pe-1 font-medium">
      <span class="sr-only">Open user menu</span>
      <img class="w-10 h-10 me-2 rounded-full" :src="avatar" alt="user photo">
    </button>
    <div v-if="!isHidden" class="absolute mt-2 min-w-46 bg-white shadow-lg rounded-lg text-base">
      <div class="px-4 py-2 text-gray-900">
        <div class="font-medium">{{userStore.currentUser.email}}</div>
      </div>
      <ul class="py-1">
        <li>
          <RouterLink class="block px-4 py-2 hover:bg-gray-100" :to="{ name: 'profile' }">
            Личный кабинет
          </RouterLink>
        </li>
        <li>
          <RouterLink class="block px-4 py-2 hover:bg-gray-100" :to="{name:'settings'}">Настройки</RouterLink>
        </li>
      </ul>
      <div class="py-1" @click="userStore.logout()">
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Выйти</a>
      </div>
    </div>
  </div>
</template>
