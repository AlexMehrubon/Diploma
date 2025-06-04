<script setup>

</script>

<template>
  <button
    class="inline-flex  items-center justify-center p-0.5 mb-2 me-2
     hover:shadow-lg
     overflow-hidden font-medium  rounded-lg bg-blue-600 text-white hover:scale-105 transition-transform duration-500 cursor-pointer">
    <span class="px-5 py-2 rounded-md ">
      <slot/>
    </span>
  </button>
</template>


<style scoped>

</style>
