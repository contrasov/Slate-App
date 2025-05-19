<script lang="ts" setup>
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/components/ui/button'
import { CalendarCellTrigger, type CalendarCellTriggerProps, useForwardProps } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import { DateValue } from '@internationalized/date'

const props = defineProps<CalendarCellTriggerProps & { 
  class?: HTMLAttributes['class']; 
  availableDays: number[];
}>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)

const isAvailable = (day: DateValue) => {
  const jsDate = new Date(day.toString());
  const currentDate = new Date();
  
  if (jsDate <= currentDate){
    return false;
  }
  
  const weekday = jsDate.getDay();
  return props.availableDays.includes(weekday);
};

</script>

<template>
  <CalendarCellTrigger
    :class="cn(
      buttonVariants({ variant: 'ghost' }),
      'h-10 w-10 p-0 font-normal',
      { 'border-2 border-green-500/30': isAvailable(day) },
      '[&[data-today]:not([data-selected])]:bg-greenPrincipal1/20 [&[data-today]:not([data-selected])]:text-accent-foreground',
      // Selected
      'data-[selected]:bg-greenPrincipal1 data-[selected]:text-white data-[selected]:hover:bg-primary data-[selected]:hover:text-primary-foreground data-[selected]:focus:bg-greenPrincipal1 data-[selected]:focus:text-primary-foreground',
      // Disabled
      'data-[disabled]:text-muted-foreground data-[disabled]:opacity-50',
      // Unavailable
      'data-[unavailable]:text-destructive-foreground data-[unavailable]:line-through',
      // Outside months
      'data-[outside-view]:text-muted-foreground data-[outside-view]:opacity-50 [&[data-outside-view][data-selected]]:bg-accent/50 [&[data-outside-view][data-selected]]:text-muted-foreground [&[data-outside-view][data-selected]]:opacity-30',
      props.class,
    )"
    v-bind="forwardedProps"
  >
    <slot />
  </CalendarCellTrigger>
</template>
