<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <thead :class="cn('[&_tr]:border-b text-[#687182] bg-[#F7F9FC]', props.class)">
    <slot />
  </thead>
</template>
