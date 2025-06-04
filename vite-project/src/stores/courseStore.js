import {defineStore} from "pinia";
import {computed, ref} from "vue";
import axios from 'axios'
import {useUserStore} from "@/stores/userStore.js";

export const useCourseStore = defineStore('courseStore', () => {
  const isLoading = ref(false);
  const isUpdating = ref(false)
  const isDeleting = ref(false)
  const isCreating = ref(false)
  const isSearching = ref(false)

  const detailCourse = ref(null)
  const error = ref(null);
  const courses = ref([]);
  const searchResults = ref([])

  const categories = ref([])
  const selectedCategories = ref([]);

  const paginator = ref()
  const currentEditCourse = ref(false);

  const statuses = ref([])
  const difficultyLevels = ref([])

  const isShowedEditModal = ref(false)
  const isShowedDeleteModal = ref(false)
  const isShowedCreateModal = ref(false)

  const courseDeleteId = ref(false)


  const filteredSearchResults = computed(() => {
    return searchResults.value
  })


  async function searchCourses(query) {
    if (!query.trim()) {
      searchResults.value = []
      return
    }

    try {
      isSearching.value = true
      error.value = null
      const response = await axios.get(`http://localhost:8003/api/v1/courses?filter[name]=${encodeURIComponent(query)}`)
      searchResults.value = response.data.data
    } catch (err) {
      error.value = 'Ошибка при загрузке курсов'
      searchResults.value = []
    } finally {
      isSearching.value = false
    }
  }

  // Остальные существующие функции остаются без изменений
  function toggleCategory(categoryId) {
    const index = selectedCategories.value.indexOf(categoryId);
    if (index === -1) {
      selectedCategories.value.push(categoryId);
    } else {
      selectedCategories.value.splice(index, 1);
    }
  }

  async function fetchById(id) {
    try {
      let params = {
        'filter[id]': id
      };

      let userStore = useUserStore()
      const headers = {};
      if (userStore.token) {
        headers.Authorization = `Bearer ${userStore.token}`;
      }

      const response = await axios.get('http://localhost:8003/api/v1/courses', {
        params,
        headers,
      });

      detailCourse.value = response.data.data[0]

    } catch (error) {
      console.error('Ошибка при загрузке курса:', error);
    }
  }


  async function toggleLessonCompletion(lessonId, completed) {
    const response = await axios.patch(`http://localhost:8003/api/v1/lessons/${lessonId}/complete`,
      {
        is_completed: completed
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${useUserStore().token}`
        },
      });
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

      const response = await axios.get('http://localhost:8003/api/v1/courses', {
        params,
      });

      await fetchStatuses();
      await fetchDifficultyLevels();

      courses.value = response.data;
      paginator.value = response.data.meta;
    } catch (error) {
      console.error('Ошибка при загрузке курсов:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchCategories() {
    isLoading.value = true
    try {
      const response = await axios.get('http://localhost:8003/api/v1/courses/tags');
      categories.value = response.data.data
    } catch (error) {
      console.error('Ошибка при загрузке тегов:', error);
    } finally {
      isLoading.value = false;
    }
  }

  function switchEditModal(course) {
    isShowedEditModal.value = !isShowedEditModal.value
    if (isShowedEditModal.value)
      currentEditCourse.value = course
    else
      currentEditCourse.value = false
  }

  function switchDeleteModal(id) {
    isShowedDeleteModal.value = !isShowedDeleteModal.value
    if (isShowedDeleteModal.value)
      courseDeleteId.value = id
    else
      courseDeleteId.value = false
  }

  function switchCreateModal() {
    isShowedCreateModal.value = !isShowedCreateModal.value
  }

  async function fetchStatuses() {
    const response = await axios.get('http://localhost:8003/api/v1/courses/statuses');
    statuses.value = response.data
  }

  async function fetchDifficultyLevels() {
    const response = await axios.get('http://localhost:8003/api/v1/courses/difficulty-levels');
    difficultyLevels.value = response.data
  }

  async function updateCourse(id, fields) {
    isUpdating.value = true
    try {
      let response = await axios.patch(`http://localhost:8003/api/v1/courses/${id}`, fields)
      await fetch(paginator.value.current_page)
      isShowedCreateModal.value = false
    } catch (error) {
      console.error('Ошибка при загрузке курсов:', error);
    } finally {
      isUpdating.value = false
    }
  }

  async function destroy() {
    isDeleting.value = true
    try {
      await axios.delete(`http://localhost:8003/api/v1/courses/${courseDeleteId.value}`)
      await fetch(paginator.value.current_page)
      isShowedDeleteModal.value = false
    } catch (error) {
      console.error('Ошибка при удалении курса:', error);
    } finally {
      isDeleting.value = false
    }
  }

  async function createCourse(fields) {
    isCreating.value = true
    try {
      await axios.post('http://localhost:8003/api/v1/courses', fields)
      await fetch(paginator.value.current_page)
      isShowedCreateModal.value = false
    } catch (error) {
      console.error('Ошибка при добавлении куса:', error)
    } finally {
      isCreating.value = false
    }
  }

  return {
    isLoading,
    isSearching,
    error,
    paginator,

    fetchById,
    detailCourse,

    isShowedEditModal,
    isShowedDeleteModal,
    isShowedCreateModal,
    currentEditCourse,
    statuses,
    difficultyLevels,
    isUpdating,
    isDeleting,
    isCreating,

    fetch,
    courses,

    searchResults: filteredSearchResults,
    searchCourses,

    categories,
    selectedCategories,
    toggleCategory,

    fetchCategories,
    switchEditModal,
    switchDeleteModal,
    switchCreateModal,
    fetchStatuses,
    fetchDifficultyLevels,
    destroy,
    updateCourse,
    createCourse,
    toggleLessonCompletion
  }
})
