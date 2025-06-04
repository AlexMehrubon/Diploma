<template>
  <div class="w-full mx-auto mt-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                    clip-rule="evenodd"/>
            </svg>
            Комментарии
            <span
              class="ml-2 text-sm font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
              {{ totalComments }}
            </span>
          </h3>
          <button
            v-if="hasMoreComments"
            @click="loadMoreComments"
            :disabled="isLoading"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-700 disabled:opacity-50 transition-colors"
          >
            <span v-if="!isLoading">Показать все</span>
            <span v-else class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-600"
                   xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Загрузка...
            </span>
          </button>
        </div>
      </div>

      <div class="p-6 border-b border-gray-100">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <img
              v-if="currentUser.image_url"
              :src="currentUser.image_url"
              :alt="currentUser.name"
              class="w-10 h-10 rounded-full object-cover border border-gray-200"
            >
            <div
              v-else
              class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold border border-gray-200"
            >
              {{ userInitial }}
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <textarea
              v-model="newComment"
              placeholder="Напишите ваш комментарий..."
              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
              rows="3"
            ></textarea>
            <div class="mt-3 flex justify-end space-x-2">
              <button
                @click="cancelComment"
                v-if="newComment.trim()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
              >
                Отмена
              </button>
              <button
                @click="addComment"

                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                Отправить
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="comments.length > 0" class="divide-y divide-gray-100">
        <div
          v-for="comment in comments"
          :key="comment.id"
          class="p-6 hover:bg-gray-50 transition-colors"
        >
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 relative">
              <img
                v-if="comment.user.data[0].image_url"
                :src="comment.user.data[0].image_url"
                :alt="comment.user.data[0].name"
                class="w-10 h-10 rounded-full object-cover border border-gray-200"
              >
              <div
                v-else
                class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-blue-100 flex items-center justify-center text-gray-600 font-bold border border-gray-200"
              >
                {{ getUserInitial(comment.user.data[0]) }}
              </div>
              <span
                v-if="isAdmin(comment.user.data[0])"
                class="absolute -bottom-1 -right-1 bg-indigo-500 text-white text-xs p-0.5 rounded-full"
                title="Администратор"
              >
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                        d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                        clip-rule="evenodd"/>
                </svg>
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <span class="text-sm font-medium text-gray-900">{{
                      comment.user.data[0].name
                    }}</span>
                  <span
                    v-if="isCurrentUser(comment.user.data[0])"
                    class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded-full"
                  >
                    Вы
                  </span>
                </div>
                <span class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</span>
              </div>
              <p class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ comment.content }}</p>


              <div v-if="comment.replying" class="mt-4 pl-4 border-l-2 border-gray-200">
                <div class="flex items-start space-x-3">
                  <div class="flex-shrink-0">
                    <img
                      v-if="currentUser.image_url"
                      :src="currentUser.image_url"
                      :alt="currentUser.name"
                      class="w-8 h-8 rounded-full object-cover border border-gray-200"
                    >
                    <div
                      v-else
                      class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-xs border border-gray-200"
                    >
                      {{ userInitial }}
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <textarea
                      v-model="comment.replyText"
                      placeholder="Напишите ответ..."
                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                      rows="2"
                    ></textarea>
                    <div class="mt-2 flex justify-end space-x-2">
                      <button
                        @click="cancelReply(comment)"
                        class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                      >
                        Отмена
                      </button>
                      <button
                        @click="submitReply(comment)"
                        :disabled="!comment.replyText.trim()"
                        class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                      >
                        Ответить
                      </button>
                    </div>
                  </div>
                </div>
              </div>


              <div v-if="comment.replies && comment.replies.length > 0"
                   class="mt-4 space-y-4 pl-4 border-l-2 border-gray-200">
                <div
                  v-for="reply in comment.replies"
                  :key="reply.id"
                  class="pt-4 first:pt-0"
                >
                  <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 relative">
                      <img
                        v-if="reply.user.data[0].image_url"
                        :src="reply.user.data[0].image_url"
                        :alt="reply.user.data[0].name"
                        class="w-8 h-8 rounded-full object-cover border border-gray-200"
                      >
                      <div
                        v-else
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-blue-100 flex items-center justify-center text-gray-600 font-bold text-xs border border-gray-200"
                      >
                        {{ getUserInitial(reply.user.data[0]) }}
                      </div>
                      <span
                        v-if="isAdmin(reply.user.data[0])"
                        class="absolute -bottom-1 -right-1 bg-indigo-500 text-white text-[0.6rem] p-0.5 rounded-full"
                        title="Администратор"
                      >
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                clip-rule="evenodd"/>
                        </svg>
                      </span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                          <span class="text-xs font-medium text-gray-900">{{
                              reply.user.data[0].name
                            }}</span>
                          <span
                            v-if="isCurrentUser(reply.user.data[0])"
                            class="text-[0.6rem] px-1 py-0.5 bg-blue-100 text-blue-800 rounded-full"
                          >
                            Вы
                          </span>
                        </div>
                        <span class="text-[0.6rem] text-gray-500">{{
                            formatDate(reply.created_at)
                          }}</span>
                      </div>
                      <p class="mt-1 text-xs text-gray-700 whitespace-pre-wrap">{{
                          reply.content
                        }}</p>
                      <div class="mt-2 flex items-center space-x-3">
                        <button
                          class="text-[0.6rem] text-gray-500 hover:text-indigo-600 transition-colors flex items-center"
                          @click="toggleLike(reply)"
                        >
                          <svg
                            class="w-3 h-3 mr-0.5"
                            :class="{ 'text-red-500 fill-current': isLiked(reply), 'text-gray-400': !isLiked(reply) }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                          </svg>
                          {{ reply.likes_count || 0 }}
                        </button>
                        <button
                          v-if="canDeleteComment(reply)"
                          class="text-[0.6rem] text-gray-500 hover:text-red-600 transition-colors"
                          @click="deleteComment(reply)"
                        >
                          Удалить
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div v-else class="p-8 text-center">
        <div class="mx-auto h-24 w-24 text-gray-300">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
               xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
          </svg>
        </div>
        <h3 class="mt-4 text-sm font-medium text-gray-900">Нет комментариев</h3>
        <p class="mt-1 text-sm text-gray-500">Будьте первым, кто оставит комментарий</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'
import {useUserStore} from '@/stores/userStore'

const props = defineProps({
  lessonId: {
    type: Number,
    required: true
  }
})

const userStore = useUserStore()
const comments = ref([])
const newComment = ref('')
const currentPage = ref(1)
const totalComments = ref(0)
const isLoading = ref(false)
const userLikes = ref(new Set())

const currentUser = computed(() => {
  return userStore.currentUser || {}
})

const hasMoreComments = computed(() => {
  return comments.value.length < totalComments.value
})

const userInitial = computed(() => {
  return currentUser.value.name?.charAt(0)?.toUpperCase() || 'U'
})

onMounted(() => {
  fetchComments()
})

async function fetchComments() {
  try {
    isLoading.value = true
    const response = await axios.get(`http://localhost:8003/api/v1/lessons/${props.lessonId}/comments`, {
      params: {
        page: currentPage.value,
      },
      headers: {
        'Authorization': `Bearer ${useUserStore().token}`,
        'Accept': 'application/json'
      }
    })

    if (currentPage.value === 1) {
      comments.value = processComments(response.data.data)
    } else {
      comments.value = [...comments.value, ...processComments(response.data.data)]
    }

    totalComments.value = response.data.meta?.total || response.data.data.length
  } catch (error) {
    console.error('Ошибка при загрузке комментариев:', error)
  } finally {
    isLoading.value = false
  }
}

function processComments(commentsData) {
  return commentsData.map(comment => {
    return {
      ...comment,
      replying: false,
      replyText: '',
      replies: comment.replies ? processComments(comment.replies) : []
    }
  })
}

async function addComment() {
  if (!newComment.value.trim()) return

  try {
    const response = await axios.post(`http://localhost:8003/api/v1/lessons/${props.lessonId}/comments`, {
      user_id: currentUser.value.id,
      content: newComment.value
    }, {
      headers: {
        'Authorization': `Bearer ${useUserStore().token}`,
        'Accept': 'application/json'
      }
    })

    const newCommentData = {
      ...response.data,
      user: {
        data: [currentUser.value]
      },
      replies: []
    }

    comments.value = [newCommentData, ...comments.value]
    totalComments.value += 1
    newComment.value = ''
  } catch (error) {
    console.error('Ошибка при добавлении комментария:', error)
  }
}

function cancelComment() {
  newComment.value = ''
}

function startReply(comment) {
  comments.value.forEach(c => {
    c.replying = false
  })
  comment.replying = true
  comment.replyText = ''
}

function cancelReply(comment) {
  comment.replying = false
  comment.replyText = ''
}

async function submitReply(comment) {
  if (!comment.replyText.trim()) return

  try {
    const response = await axios.post(`http://localhost:8003/api/v1/comments/${comment.id}/replies`, {
      user_id: currentUser.value.id,
      content: comment.replyText
    }, {
      headers: {
        'Authorization': `Bearer ${useUserStore().token}`,
        'Accept': 'application/json'
      }
    })

    const newReply = {
      ...response.data,
      user: {
        data: [currentUser.value]
      }
    }

    if (!comment.replies) {
      comment.replies = []
    }

    comment.replies.push(newReply)
    comment.replying = false
    comment.replyText = ''
  } catch (error) {
    console.error('Ошибка при добавлении ответа:', error)
  }
}

async function deleteComment(comment) {
  if (!confirm('Вы уверены, что хотите удалить этот комментарий?')) return

  try {
    const endpoint = comment.parent_id
      ? `http://localhost:8003/api/v1/comments/${comment.parent_id}/replies/${comment.id}`
      : `http://localhost:8003/api/v1/comments/${comment.id}`

    await axios.delete(endpoint)

    if (comment.parent_id) {
      const parentComment = comments.value.find(c => c.id === comment.parent_id)
      if (parentComment && parentComment.replies) {
        parentComment.replies = parentComment.replies.filter(r => r.id !== comment.id)
      }
    } else {
      comments.value = comments.value.filter(c => c.id !== comment.id)
    }

    totalComments.value -= 1
  } catch (error) {
    console.error('Ошибка при удалении комментария:', error)
  }
}

async function toggleLike(comment) {
  try {
    if (isLiked(comment)) {
      await axios.delete(`http://localhost:8003/api/v1/comments/${comment.id}/like`, [], {
        headers: {
          'Authorization': `Bearer ${useUserStore().token}`,
          'Accept': 'application/json'
        }
      })
      userLikes.value.delete(comment.id)
      if (comment.likes_count) comment.likes_count -= 1
    } else {
      await axios.post(`http://localhost:8003/api/v1/comments/${comment.id}/like`, [], {
        headers: {
          'Authorization': `Bearer ${useUserStore().token}`,
          'Accept': 'application/json'
        }
      })
      userLikes.value.add(comment.id)
      if (comment.likes_count) comment.likes_count += 1
      else comment.likes_count = 1
    }
  } catch (error) {
    console.error('Ошибка при лайке комментария:', error)
  }
}

function loadMoreComments() {
  currentPage.value += 1
  fetchComments()
}

function formatDate(dateString) {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.abs(now - date) / 36e5

  if (diffInHours < 24) {
    return date.toLocaleTimeString('ru-RU', {hour: '2-digit', minute: '2-digit'})
  } else {
    return date.toLocaleDateString('ru-RU', {
      day: 'numeric',
      month: 'short',
      year: diffInHours > 8760 ? 'numeric' : undefined,
      hour: '2-digit',
      minute: '2-digit'
    }).replace(' г.', '')
  }
}

function getUserInitial(user) {
  return user?.name?.charAt(0)?.toUpperCase() || 'U'
}

function isAdmin(user) {
  return user?.roles?.some(role => role.code === 'admin')
}

function isCurrentUser(user) {
  return user?.id === currentUser.value.id
}

function isLiked(comment) {
  return userLikes.value.has(comment.id)
}

function canDeleteComment(comment) {
  return isCurrentUser(comment.user.data[0]) || currentUser.value.roles?.some(role => role.code === 'admin')
}
</script>
