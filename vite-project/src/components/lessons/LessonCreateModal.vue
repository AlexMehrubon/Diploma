<script setup>
import { computed, ref, watch, nextTick } from "vue";
import Editor from "@/components/Editor.vue";
import { useModuleStore } from "@/stores/moduleStore.js";

const props = defineProps({
  lessonStore: Object,
  editedLesson: Object,
  mode: String
});

const moduleStore = useModuleStore();
const editorKey = ref(0);

const lesson = ref({
  content: "",
  name: "",
  order: 1,
  module_id: moduleStore.currentEditModule?.id || null
});

watch(() => props.editedLesson, (newVal) => {
  if (newVal) {
    lesson.value = {
      ...newVal,
      content: newVal.content || "", // Явно устанавливаем content
      module_id: newVal.module_id || moduleStore.currentEditModule?.id || null
    };
    editorKey.value++; // Принудительно обновляем редактор
  } else {
    lesson.value = {
      content: "",
      name: "",
      order: 1,
      module_id: moduleStore.currentEditModule?.id || null
    };
  }
}, { immediate: true, deep: true });

const isEditing = computed(() => props.mode === 'edit');
</script>

<template>
  <div id="edit-course-modal" tabindex="-1" aria-hidden="true"
       class="flex items-center justify-center overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-80 w-full md:inset-0 h-screen max-h-full bg-black/30">
    <div class="relative p-4 min-w-2xl  max-h-full">

      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow-sm">
        <!-- Modal header -->
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900">
            {{ isEditing ? 'Редактирование урока' : 'Добавление урока' }}
          </h3>
          <button @click="props.lessonStore.closeModal" type="button"
                  class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                  data-modal-hide="authentication-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Закрыть</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="py-4 px-7">
          <div class="mb-10">
            <label for="name" class="block mb-2 text-sm font-semibold text-gray-900">Заголовок
              урока</label>
            <input
              v-model="lesson.name"
              type="text"
              id="name"
              class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5 text-sm"
              placeholder="Введите название курса"
              required
            />
          </div>

          <div class="mb-10">
            <label for="name" class="block mb-2 text-sm font-semibold text-gray-900">В каком порядке отображать</label>
            <input
              v-model="lesson.order"
              type="text"
              id="name"
              class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5 text-sm"
              placeholder="Введите название курса"
              required
            />
          </div>
          <div>
            <label class="block mb-2 text-sm font-semibold text-gray-900">Содержимое урока</label>
            <Editor
              :key="editorKey"
              v-model="lesson.content"
              class="prose block max-w-none prose-xl"
            />
          </div>

          <div class="mb-10 mt-6 flex justify-center">
            <button
              @click="isEditing ? props.lessonStore.updateLesson(lesson) : props.lessonStore.createLesson(lesson)"
              type="button"
              class="w-1/2 text-white bg-blue-700 text-sm hover:bg-blue-800 font-semibold rounded-lg py-2.5"
            >
              {{ isEditing ? 'Сохранить изменения' : 'Сохранить' }}
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>
