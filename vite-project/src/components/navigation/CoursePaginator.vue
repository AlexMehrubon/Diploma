<script setup>

import {computed} from "vue";

const props = defineProps(
  {
    store: Object
  }
)

const currentPage = computed(() => {
  return props.store?.paginator?.current_page;
})

const isShow = computed(() => {
  return !(props.store?.paginator?.last_page === 1)
})

function getNextPage() {
  console.log(props.store)
  if (currentPage.value >= props.store.paginator.last_page)
    return
  props.store.fetch(currentPage.value + 1)
}

function getPreviousPage()
{
  if( currentPage.value <= props.store.paginator.first_page)
    return
  props.store.fetch(currentPage.value - 1)
}



</script>

<template>

  <nav v-if="isShow" class=" flex items-center justify-center w-full flex-column flex-wrap md:flex-row  pt-4"
       aria-label="Table navigation">
    <ul class="inline-flex -space-x-px rtl:space-x-reverse text-xl h-15">
      <li>
        <a @click="getPreviousPage"
           class="flex items-center justify-center px-5 h-15 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
          <img src="@/assets/left-arrow.png" class="h-4 w-4" alt="left array"/>
        </a>
      </li>
      <li v-for="page in props.store?.paginator?.last_page">
        <a @click="props.store.fetch(page)"
           class="flex items-center text-base justify-center px-5 h-15 leading-tight bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
           :class="{'font-bold text-blue-500': currentPage === page,  'text-gray-500': currentPage !== page}"
        >
          {{ page }}
        </a>
      </li>
      <li>
        <a @click="getNextPage"
           class="flex items-center justify-center px-5 h-15 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
          <img src="@/assets/right-arrow.png" class="h-4 w-4" alt="right array"/>
        </a>
      </li>
    </ul>
  </nav>
</template>

<style scoped>

</style>
