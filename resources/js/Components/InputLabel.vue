<script setup>
defineProps({
  value: String,
  optional: Boolean = false
});
</script>

<template>
  <label class="block font-medium text-sm text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else>
      <slot />
    </span>
    <span v-if="optional === true" class="text-gray-400">&nbsp;(Optional)</span>
  </label>
</template>
