<script setup lang="ts">
import { ChevronRight, ChevronLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  appointments: Array<{ 
    id: number, 
    patient: 
    {id: number; name: string}, 
    appointment_time: string, 
    appointment_date: string, 
    patient_id: number, 
    status: string, 
    phone: string }>;
}>();

const formatTime = (time: string) => {
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}h`;
}

console.log(props.appointments);

const currentDate = ref(new Date(2025, 4, 1))

const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']

const month = computed(() =>
  currentDate.value.toLocaleDateString('pt-BR', { month: 'long' })
)

const year = computed(() => currentDate.value.getFullYear())

const daysInMonth = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  return new Date(year, month + 1, 0).getDate()
})

const startDayOfWeek = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  return new Date(year, month, 1).getDay()
})

const daysGrid = computed(() => {
  const totalDays = daysInMonth.value
  const offset = startDayOfWeek.value
  const grid = []

  for (let i = 0; i < offset; i++) {
    grid.push(null)
  }

  for (let d = 1; d <= totalDays; d++) {
    grid.push(d)
  }

  return grid
})

const prevMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() - 1,
    1
  )
}

const nextMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() + 1,
    1
  )
}

const isSameDay = (dateStr: string, day: number | null) => {
  if (!day) return false

  const [year, month, date] = dateStr.split('-').map(Number)
  return (
    year === currentDate.value.getFullYear() &&
    month - 1 === currentDate.value.getMonth() &&
    date === day
  )
}
</script>

<template>
  <div class="flex flex-col gap-2 w-full">
    <div class="flex items-center justify-between gap-4 text-gray-700 p-4">
      <ChevronLeft class="w-5 h-5 cursor-pointer hover:text-black" @click="prevMonth" />
      <h1 class="text-lg font-semibold capitalize">
        {{ month }} {{ year }}
      </h1>
      <ChevronRight class="w-5 h-5 cursor-pointer hover:text-black" @click="nextMonth" />
    </div>

    <div class="grid grid-cols-7 text-center text-sm font-medium">
      <div v-for="(day, index) in weekdays" :key="index" class="pt-2">
        {{ day }}
      </div>
    </div>

    <div class="grid grid-cols-7 px-4">
      <div
        v-for="(day, index) in daysGrid"
        :key="index"
        class="bg-white h-24 p-2 text-sm border border-sidebar-border"
      >
        <span v-if="day">{{ day }}</span>

        <div class="h-16 overflow-y-auto scrollbar-hide">
          <template v-for="appointment in appointments" :key="appointment.id">
            <div
              @click="() => { router.visit (route('patients.service', { id: appointment.patient_id}) + '?from=schedule') }"
              v-if="isSameDay(appointment.appointment_date, day)"
              class="bg-[#4299e1] text-white rounded px-1 py-[2px] mb-1 text-xs truncate cursor-pointer"
            >
              <p>
              {{ formatTime(appointment.appointment_time) }} - {{ appointment.patient.name.split(' ')[0] }}
              </p>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
