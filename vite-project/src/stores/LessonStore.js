import {defineStore} from "pinia";
import {ref} from "vue";
import axios from "axios";




export const useLessonStore = defineStore('lessonStore', () => {
  const isCreating = ref(false)
  const isShowedCreateModal = ref(false)
  const isLoading = ref(false)
  const paginator = ref()
  const lessons = ref([])
  function switchCreateModal() {
    isShowedCreateModal.value = !isShowedCreateModal.value
  }

  const isModalOpen = ref(false)
  const modalMode = ref("create")
  const currentLesson = ref(null)

  function openModal(mode = 'create', lesson = null) {
    isModalOpen.value = true
    modalMode.value = mode
    currentLesson.value = lesson
  }

  function closeModal() {
    isModalOpen.value = false
    currentLesson.value = null
    modalMode.value = 'create'
  }

  async function deleteLesson(id) {
    try {
      await axios.delete(`http://localhost:8003/api/v1/lessons/${id}`);
      // Обновляем список уроков после удаления
      const currentModuleId = lessons.value.data[0]?.module_id; // Получаем module_id из первого урока
      if (currentModuleId) {
        await fetch(1, 8, {"filter[module_id]": currentModuleId}, false);
      }
    } catch (error) {
      console.error('Ошибка при удалении урока:', error);
      throw error; // Можно обработать ошибку в компоненте
    }
  }


  async function createLesson(fields) {
    isCreating.value = true
    try {
      await axios.post(`http://localhost:8003/api/v1/lessons`, fields)
      const filter = {
        "filter[module_id]": fields['module_id']
      }
      await fetch(1, 8, filter, false)
      isModalOpen.value=false
    } catch (error) {
      console.error('Ошибка при добавлении модуля:', error)
    } finally {
      isCreating.value = false
    }
  }

  async function fetch(page = 1, perPage = 8, filter = {}, paginate = true) {
    isLoading.value = true;
    try {
      let params = {};

      if (paginate) {
        params.page = page;
        params.per_page = perPage;
      }

      for (const [key, value] of Object.entries(filter)) {
        if (Array.isArray(value)) {
          value.forEach(val => {
            if (!params[key]) params[key] = [];
            params[key].push(val);
          });
        } else if (value !== '' && value !== null && value !== undefined) {
          params[key] = value;
        }
      }

      const response = await axios.get('http://localhost:8003/api/v1/lessons', {
        params,
      });


      lessons.value = response.data;
      paginator.value = response.data.meta;
    } catch (error) {
      console.error('Ошибка при загрузке уроков:', error);
    } finally {
      isLoading.value = false;
    }
  }


  async function updateLesson(fields) {
    try {
      await axios.put(`http://localhost:8003/api/v1/lessons/${fields.id}`, fields);
      const filter = {
        "filter[module_id]": fields['module_id']
      }
      await fetch(1, 8, filter, false);
      isShowedCreateModal.value = false;
    } catch (error) {
      console.error('Ошибка при обновлении урока:', error);
    }
  }

  return {
    isCreating,
    isShowedCreateModal,
    isModalOpen,
    currentLesson,
    modalMode,
    closeModal,
    openModal,
    deleteLesson,
    switchCreateModal,
    createLesson,
    updateLesson,
    lessons,
    fetch,
    paginator
  }

})
