<script setup>
import { computed, ref } from "vue";

const props = defineProps({
  coursesStore: Object,
});

const course = ref({
  id: null,
  name: '',
  description: '',
  duration: '',
  rating: 5,
  difficulty_level_id: 1,
  status_id: 1,
  average_salary: 0,
  about: '',
  tags: [] // Инициализируем массив для хранения выбранных тегов
});

const maxDescriptionLength = 458;
const maxDetailDescriptionLength = 1000;

const isDescriptionTooLong = computed(() => {
  return course.value.description.length > maxDescriptionLength;
});

const isAboutTooLong = computed(() => {
  return course.value.about.length > maxDetailDescriptionLength;
});

const checkDescriptionLength = () => {
  if (course.value.description.length > maxDescriptionLength) {
    course.value.description = course.value.description.slice(0, maxDescriptionLength);
  }
};

const checkAboutLength = () => {
  if (course.value.about.length > maxDetailDescriptionLength) {
    course.value.about = course.value.about.slice(0, maxDetailDescriptionLength);
  }
};

// Функция для обработки выбора тегов
const handleTagChange = (event) => {
  const options = event.target.options;
  const selectedTags = [];
  for (let i = 0; i < options.length; i++) {
    if (options[i].selected) {
      selectedTags.push(parseInt(options[i].value)); // Преобразуем в число
    }
  }
  course.value.tags = selectedTags;
};
</script>

<template>
  <div id="edit-course-modal" tabindex="-1" aria-hidden="true"
       class="fixed inset-0 z-50 flex items-center justify-center w-full h-screen max-h-full bg-black/30 backdrop-blur-sm">
    <div class="relative w-full max-w-2xl p-4 mx-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
          <h3 class="text-2xl font-bold text-gray-800">
            Добавление нового курса
          </h3>
          <button
            @click="props.coursesStore.switchCreateModal"
            type="button"
            class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="sr-only">Закрыть</span>
          </button>
        </div>

        <!-- Modal body -->
        <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
          <form class="space-y-6">
            <!-- Grid for first row of inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Course name -->
              <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                  Название курса <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  v-model="course.name"
                  class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Введите название курса"
                  required
                />
              </div>

              <!-- Duration -->
              <div>
                <label for="duration" class="block mb-2 text-sm font-medium text-gray-700">
                  Продолжительность (часы) <span class="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  name="duration"
                  id="duration"
                  v-model="course.duration"
                  class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Введите продолжительность"
                  required
                />
              </div>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                Описание курса (краткое) <span class="text-red-500">*</span>
              </label>
              <textarea
                name="description"
                id="description"
                v-model="course.description"
                @input="checkDescriptionLength"
                class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 min-h-32"
                placeholder="Краткое описание, которое будет отображаться на карточке курса"
                :maxlength="maxDescriptionLength"
                required
              ></textarea>
              <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-500">
                  Осталось символов: {{ maxDescriptionLength - course.description.length }}/{{ maxDescriptionLength }}
                </p>
                <p v-if="isDescriptionTooLong" class="text-xs text-red-500">
                  Превышено максимальное количество символов
                </p>
              </div>
            </div>

            <!-- Detailed description -->
            <div>
              <label for="about" class="block mb-2 text-sm font-medium text-gray-700">
                Подробное описание курса <span class="text-red-500">*</span>
              </label>
              <textarea
                name="about"
                id="about"
                v-model="course.about"
                @input="checkAboutLength"
                class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 min-h-40"
                placeholder="Полное описание курса для страницы деталей"
                :maxlength="maxDetailDescriptionLength"
                required
              ></textarea>
              <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-500">
                  Осталось символов: {{ maxDetailDescriptionLength - course.about.length }}/{{ maxDetailDescriptionLength }}
                </p>
                <p v-if="isAboutTooLong" class="text-xs text-red-500">
                  Превышено максимальное количество символов
                </p>
              </div>
            </div>

            <!-- Grid for second row of inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Average salary -->
              <div>
                <label for="average_salary" class="block mb-2 text-sm font-medium text-gray-700">
                  Средняя зарплата специалиста ($)
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                  <input
                    type="number"
                    name="average_salary"
                    id="average_salary"
                    v-model="course.average_salary"
                    class="w-full pl-8 pr-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder="Введите среднюю зарплату"
                  />
                </div>
              </div>

              <!-- Rating -->
              <div>
                <label for="rating" class="block mb-2 text-sm font-medium text-gray-700">
                  Рейтинг курса (1-5)
                </label>
                <input
                  type="number"
                  name="rating"
                  id="rating"
                  v-model="course.rating"
                  min="1"
                  max="5"
                  class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="От 1 до 5"
                />
              </div>
            </div>

            <!-- Grid for third row of inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Difficulty level -->
              <div>
                <label for="difficulty_level_id" class="block mb-2 text-sm font-medium text-gray-700">
                  Уровень сложности <span class="text-red-500">*</span>
                </label>
                <select
                  name="difficulty_level_id"
                  id="difficulty_level_id"
                  v-model="course.difficulty_level_id"
                  class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none"
                  required
                >
                  <option
                    v-for="difficultyLevel in props.coursesStore.difficultyLevels"
                    :key="difficultyLevel.id"
                    :value="difficultyLevel.id"
                  >
                    {{ difficultyLevel.name }}
                  </option>
                </select>
              </div>

              <!-- Status -->
              <div>
                <label for="status_id" class="block mb-2 text-sm font-medium text-gray-700">
                  Статус курса <span class="text-red-500">*</span>
                </label>
                <select
                  name="status_id"
                  id="status_id"
                  v-model="course.status_id"
                  class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none"
                  required
                >
                  <option
                    v-for="status in props.coursesStore.statuses"
                    :key="status.id"
                    :value="status.id"
                  >
                    {{ status.name }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Tags -->
            <div>
              <label for="tags" class="block mb-2 text-sm font-medium text-gray-700">
                Теги курса
              </label>
              <select
                id="tags"
                name="tags"
                multiple
                @change="handleTagChange"
                class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 min-h-[42px]"
              >
                <option
                  v-for="tag in props.coursesStore.categories"
                  :key="tag.id"
                  :value="tag.id"
                  :selected="course.tags.includes(tag.id)"
                >
                  {{ tag.name }}
                </option>
              </select>
              <p v-if="course.tags.length > 0" class="text-xs text-gray-500 mt-1">
                Выбрано тегов: {{ course.tags.length }}
              </p>
            </div>

            <!-- Submit button -->
            <div class="pt-4">
              <button
                @click.prevent="props.coursesStore.createCourse(course)"
                v-if="!props.coursesStore.isCreating"
                class="w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
              >
                Создать курс
              </button>
              <button
                v-else
                disabled
                class="w-full px-6 py-3 text-sm font-semibold text-white bg-blue-400 rounded-lg cursor-not-allowed"
              >
                <svg aria-hidden="true" class="inline w-4 h-4 mr-2 text-white animate-spin" viewBox="0 0 100 101" fill="none">
                  <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                  <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2"/>
                </svg>
                Создание курса...
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for modal content */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}
</style>
