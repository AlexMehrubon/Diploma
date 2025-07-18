<script setup>
import {onMounted, ref, watch} from "vue";
import Paginator from "@/components/navigation/Paginator.vue";


const props = defineProps({
  store:  Object,
  searchPlaceholder: String,
  searchField : String
})


onMounted(async () => {
  await props.store.fetch(1);
});

const searchText = ref('');

watch(searchText, async (text) => {
  const query = {};
  query[props.searchField] = text;
  await props.store.fetch(1, 8, query);
})

</script>

<template>
  <p class="text-xl mb-2 font-bold">
    <slot name="title"/>
  </p>
  <div class="pb-4 bg-white dark:bg-gray-900 flex flex-row justify-between">
    <label for="table-search" class="sr-only">Search</label>
    <div class="relative mt-1">
      <div
        class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
      </div>
      <input
        v-model="searchText"
        type="text" id="table-search"
        class="block pt-3 pb-2 ps-10 text-sm text-gray-900
         border border-gray-300 rounded-lg w-110 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
        :placeholder="searchPlaceholder">
    </div>

    <button
      v-if="$slots.createButtonText"
      @click="props.store.switchCreateModal"
      data-modal-hide="popup-modal"
      class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300
         dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center hover:scale-105 transition-transform duration-500"
    >
      <slot name="createButtonText"/>
    </button>
  </div>

  <div v-if="props.store.isLoading" class="flex flex-col justify-center items-center h-110 text-lg">
    <div role="status">
      <svg aria-hidden="true" class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
      </svg>
      <span class="sr-only">Загрузка...</span>
    </div>
  </div>

  <div v-else class="flex flex-col h-full pb-5 justify-between">
    <slot name="table"/>
    <Paginator :store="props.store"/>
  </div>
</template>

<style scoped></style>
