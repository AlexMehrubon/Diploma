import {defineStore} from "pinia";
import {ref} from "vue";
import axios from "axios";


export const useModuleStore = defineStore('moduleStore', () => {
  const isCreating = ref(false)
  const isLoading = ref(false)
  const isDeleting = ref(false)
  const isUpdating = ref(false)

  const isShowedEditModal = ref(false)
  const currentEditModule = ref(false)
  const isShowedDeleteModal = ref(false)

  const isShowedCreateModal = ref(false)
  const paginator = ref()

  const modules = ref([])
  const moduleDeleteId = ref(false)

  const currentCourse = ref(1)


  function switchEditModal(module) {
    isShowedEditModal.value = !isShowedEditModal.value
    console.log(isShowedEditModal.value)
    if (isShowedEditModal.value) {
      currentEditModule.value = module
    }

    else
      currentEditModule.value = false
  }

  function switchCreateModal() {
    isShowedCreateModal.value = !isShowedCreateModal.value
  }

  async function createModule(fields) {
    isCreating.value = true
    try {
      await axios.post(`http://localhost:8003/api/v1/modules`, fields)
      const filter = {
        "filter[course_id]": fields['course_id']
      }
      await fetch(1, 8, filter, false)
      isShowedCreateModal.value = false
    } catch (error) {
      console.error('Ошибка при добавлении модуля:', error)
    } finally {
      isCreating.value = false
    }
  }


  function switchDeleteModal(id) {
    isShowedDeleteModal.value = !isShowedDeleteModal.value
    if (isShowedDeleteModal.value)
      moduleDeleteId.value = id
    else
      moduleDeleteId.value = false
  }

  async function destroy() {
    isDeleting.value = true
    try {
      await axios.delete(`http://localhost:8003/api/v1/modules/${moduleDeleteId.value}`)
      const filter = {
        "filter[course_id]": currentCourse.value
      }
      await fetch(1, 8, filter, false)
      isShowedDeleteModal.value = false
    } catch (error) {
      console.error('Ошибка при удалении курса:', error);
    } finally {
      isDeleting.value = false
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

      const response = await axios.get('http://localhost:8003/api/v1/modules', {
        params,
      });


      modules.value = response.data;
      paginator.value = response.data.meta;
    } catch (error) {
      console.error('Ошибка при загрузке курсов:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function updateModule(id, fields) {
    isUpdating.value = true
    try {
      let response = await axios.patch(`http://localhost:8003/api/v1/modules/${id}`, fields)
      const filter = {
        "filter[course_id]": fields['course_id']
      }
      await fetch(1, 8, filter, false)
      isShowedEditModal.value = false
    } catch (error) {
      console.error('Ошибка при загрузке модулей:', error);
    } finally {
      isUpdating.value = false
    }
  }


  return {
    isCreating,
    isLoading,
    isShowedCreateModal,
    isShowedEditModal,
    currentEditModule,
    modules,
    paginator,
    isShowedDeleteModal,
    moduleDeleteId,
    isDeleting,
    currentCourse,
    isUpdating,

    updateModule,
    switchEditModal,
    switchCreateModal,
    switchDeleteModal,
    fetch,
    createModule,
    destroy

  }
})
