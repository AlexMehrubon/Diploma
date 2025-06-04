<template>
  <div class="relative inline-block">
    <div
      @mouseenter="showTooltip"
      @mouseleave="hideTooltip"
      class="cursor-pointer"
    >
      <slot name="trigger"></slot>
    </div>


    <div
      v-if="isVisible"
      class="absolute z-10 p-2 text-sm text-white bg-gray-800 rounded shadow-lg"
      :class="tooltipPosition"
    >
      <slot name="content"></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isVisible = ref(false)

const showTooltip = () => {
  isVisible.value = true
}

const hideTooltip = () => {
  isVisible.value = false
}

const tooltipPosition = ref('bottom-full mb-2')
</script>

<style scoped>

</style>
