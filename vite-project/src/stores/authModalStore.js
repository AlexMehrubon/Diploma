import {defineStore} from "pinia";
import {ref} from "vue";

export const useAuthModalStore = defineStore('authModalStore', () => {
  const isShowedModal = ref(false);
  const pages = Object.freeze({
    Login: 'Login',
    Register: 'Register'
  })
  const currentPage = ref(pages.Login)


  function showModal(page = null) {
    isShowedModal.value = !isShowedModal.value
    if (page)
      currentPage.value = page

    if (!isShowedModal.value)
      currentPage.value = pages.Login
  }

  function replacePage() {
    if (currentPage.value === pages.Login)
      currentPage.value = pages.Register
    else
      currentPage.value = pages.Login
  }

  return {isShowedModal, pages, currentPage, showModal, replacePage}
})

