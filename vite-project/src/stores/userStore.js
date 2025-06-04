import {defineStore} from "pinia";
import {computed, ref} from "vue";
import axios from 'axios'

export const useUserStore = defineStore('userStore', () => {
  const token = ref(localStorage.getItem('token') || null)
  const isAuthenticated = computed(() => !!token.value)

  const paginator = ref()

  const currentUser = ref(null)
  const users = ref([])
  const deleteId = ref(null)

  const isShowedEditModal = ref(false)
  const isShowedDeleteModal = ref(false)
  const isShowedCreateModal = ref(false)

  const isLoading = ref(false)
  const isUpdating = ref(false)
  const isDeleting = ref(false)
  const isCreating = ref(false)

  const isAdmin = computed(() => {
    return currentUser.value?.roles?.some(role => role.code === "admin")
  })

  function switchDeleteModal(id) {
    isShowedDeleteModal.value = !isShowedDeleteModal.value
    if (isShowedDeleteModal.value)
      deleteId.value = id
    else
      deleteId.value = false
  }

  async function updateProfile(data, avatarFile) {
    try {
      const formData = new FormData();

      formData.append('name', data.name);
      formData.append('lastName', data.lastName);
      formData.append('email', data.email);
      formData.append('phone', data.phone);
      formData.append('country', data.country);
      formData.append('city', data.city);
      formData.append('about', data.about);

      if (avatarFile) {
        formData.append('avatar', avatarFile);
      }

      const response = await axios.post(`http://localhost:8003/api/v1/users/${useUserStore().currentUser.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      this.currentUser = fetchCurrentUser();
      console.log('Профиль обновлен', response.data);
      return response.data;
    } catch (error) {
      console.error('Ошибка при обновлении профиля:', error);
      throw error;
    }
  }

  async function fetch(page = 1, perPage = 8, filter = {}) {
    isLoading.value = true;
    try {

      const params = {
        page,
        per_page: perPage,
      };


      for (const [key, value] of Object.entries(filter)) {
        if (value) {
          params[`filter[${key}]`] = value;
        }
      }


      const response = await axios.get('http://localhost:8003/api/v1/users', {
        params,
      });


      users.value = response.data
      paginator.value = response.data.meta
    } catch (error) {
      console.error('Ошибка при загрузке курсов:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchCurrentUser() {

    if (!token.value) return
    const response = await axios.get('http://localhost:8003/api/v1/users/current', {
      headers: {Authorization: `Bearer ${token.value}`}
    })
    currentUser.value = response.data
  }

  async function register(formData, authModalStore) {
    const response = await axios.post('http://localhost:8003/api/v1/register', formData)
    authModalStore.showModal();
  }

  async function login(formData, authModalStore) {
      const response = await axios.post('http://localhost:8003/api/v1/login', formData)
      token.value = response.data.token
      localStorage.setItem('token', token.value)
      authModalStore.showModal()
      await fetchCurrentUser()
  }

  async function destroy() {
    isDeleting.value = true
    try {
      await axios.delete(`http://localhost:8003/api/v1/users/${deleteId.value}`, {
          headers: {Authorization: `Bearer ${token.value}`}
        }
      )
      await fetch(paginator.value.current_page)
      isShowedDeleteModal.value = false
    } catch (error) {
      console.error('Ошибка при удалении пользователя:', error);
    } finally {
      isDeleting.value = false
    }
  }

  const logout = () => {
    token.value = null
    currentUser.value = null
    localStorage.removeItem('token')
  }

  return {
    token,
    currentUser,
    isAuthenticated,
    isAdmin,
    isLoading,
    isUpdating,
    isDeleting,
    isCreating,
    paginator,
    users,
    updateProfile,
    isShowedEditModal,
    isShowedDeleteModal,
    isShowedCreateModal,
    deleteId,
    switchDeleteModal,
    fetch,
    login,
    fetchCurrentUser,
    logout,
    register,
    destroy
  }
})
