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
  const dateString = day.toString();
  const [year, month, date] = dateString.split('-').map(Number);
  const jsDate = new Date(year, month - 1, date, 12);
  const currentDate = new Date();

  const today = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
  if (jsDate < today) {
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
      'data-[disabled]:border-transparent data-[unavailable]:border-transparent data-[outside-view]:border-transparent',
      '[&[data-today]:not([data-selected])]:bg-greenPrincipal1/20 [&[data-today]:not([data-selected])]:text-accent-foreground',
      // Selected
      'data-[selected]:bg-greenPrincipal1 data-[selected]:text-white data-[selected]:hover:bg-primary data-[selected]:hover:text-primary-foreground data-[selected]:focus:bg-greenPrincipal1 data-[selected]:focus:text-primary-foreground',
      // Disabled
      'disabled:opacity-100 data-[disabled]:text-slate-400 data-[disabled]:opacity-100',
      // Unavailable
      'data-[unavailable]:text-slate-500 data-[unavailable]:opacity-100',
      // Outside months
      'data-[outside-view]:text-muted-foreground data-[outside-view]:opacity-50 [&[data-outside-view][data-selected]]:bg-accent/50 [&[data-outside-view][data-selected]]:text-muted-foreground [&[data-outside-view][data-selected]]:opacity-30',
      props.class,
    )"
    v-bind="forwardedProps"
  >
    <slot />
  </CalendarCellTrigger>
</template>
