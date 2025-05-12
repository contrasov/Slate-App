<script lang="ts" setup>
import { cn } from '@/lib/utils'
import { CalendarRoot, type CalendarRootEmits, type CalendarRootProps, useForwardPropsEmits } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import { CalendarCell, CalendarCellTrigger, CalendarGrid, CalendarGridBody, CalendarGridHead, CalendarGridRow, CalendarHeadCell, CalendarHeader, CalendarHeading, CalendarNextButton, CalendarPrevButton } from '.'

const props = defineProps<CalendarRootProps & { 
  class?: HTMLAttributes['class']; 
  availableDays?: number[];
  customHeadings?: string[];
  disableDatesBeforeToday?: boolean;
  customDayClass?: (date: any) => string | null;
}>()

const emits = defineEmits<CalendarRootEmits>()

const delegatedProps = computed(() => {
  const { class: _, customHeadings: __, availableDays: ___, disableDatesBeforeToday: ____, customDayClass: _____, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)

const weekDaysPtBr = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];

const getHeadings = (weekDays: string[]) => {
  if (props.customHeadings && props.customHeadings.length === 7) {
    return props.customHeadings;
  }
  
  return weekDays.map((day, index) => weekDaysPtBr[index]);
}

const isDateAvailable = (day: any) => {
  if (!props.availableDays) return true;
  
  const jsDate = new Date(day.toString());
  
  if (props.disableDatesBeforeToday && jsDate <= new Date()) {
    return false;
  }
  
  const weekday = jsDate.getDay();
  return props.availableDays.includes(weekday);
};

const getDayClass = (day: any) => {
  if (props.customDayClass) {
    return props.customDayClass(day);
  }
  
  if (props.availableDays) {
    return isDateAvailable(day) ? 'border-2 border-green-500/30' : '';
  }
  
  return '';
};
</script>

<template>
  <CalendarRoot
    v-slot="{ grid, weekDays }"
    :class="cn('p-3', props.class)"
    v-bind="forwarded"
  >
    <CalendarHeader>
      <CalendarPrevButton />
      <CalendarHeading />
      <CalendarNextButton />
    </CalendarHeader>

    <div class="flex flex-col h-[75vh]">
      <CalendarGrid v-for="month in grid" :key="month.value.toString()">
        <CalendarGridHead>
          <CalendarGridRow>
            <CalendarHeadCell
              v-for="day in getHeadings(weekDays)" :key="day"
            >
              {{ day }}
            </CalendarHeadCell>
          </CalendarGridRow>
        </CalendarGridHead>
        <CalendarGridBody>
          <CalendarGridRow v-for="(weekDates, index) in month.rows" :key="`weekDate-${index}`" class="justify-between flex w-full">
            <CalendarCell
              class="border h-[13vh] "
              v-for="weekDate in weekDates"
              :key="weekDate.toString()"
              :date="weekDate"
            >
              <CalendarCellTrigger
                :day="weekDate"
                :month="month.value"
                :availableDays="props.availableDays || []"
                :class="getDayClass(weekDate)"
                @click="emits('update:modelValue', weekDate)"
              />
            </CalendarCell>
          </CalendarGridRow>
        </CalendarGridBody>
      </CalendarGrid>
    </div>
  </CalendarRoot>
</template> 