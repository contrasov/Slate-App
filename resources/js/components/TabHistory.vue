<script setup lang="ts">
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { NotepadText, Clipboard } from 'lucide-vue-next';
</script>

<template>
  <div>
    <Tabs defaultValue="info">
      <TabsList>
        <TabsTrigger value="info">Histórico de consultas</TabsTrigger>
        <TabsTrigger value="address">Histórico de Anamnese</TabsTrigger>
      </TabsList>
      <TabsContent value="info">
        <div class="flex flex-col gap-2 w-full overflow-y-auto max-h-[70vh]">
          <div v-for="index in 10" :key="index" class="flex flex-col w-full gap-2">
            <div
              class="flex flex-row gap-2 justify-between items-center w-full p-3 border bg-white rounded-md"
            >
              <div class="flex flex-col">
                <h1>Consulta {{ index }}</h1>
                <div class="flex flex-row gap-2">
                <h2 class="text-gray-400 text-sm">Hora: 10:00</h2>
                <h1 class="text-gray-400 text-sm">Dia: 07/05/2025</h1>
                </div>
              </div>
              <Button variant="outline">
                <NotepadText class="w-4 h-4" />
                Ver anotação
              </Button>
            </div>
          </div>
        </div>
      </TabsContent>
      <TabsContent value="address">
        <div class="flex flex-col gap-2 w-full overflow-y-auto max-h-[500px]">
          <div v-for="index in 5" :key="index" class="flex flex-col w-full gap-2">
            <div
              class="flex flex-row gap-2 justify-between items-center w-full p-3 border bg-white rounded-md"
            >
              <div class="flex flex-col">
                <h1>Anamnese {{ index }}</h1>
                <h2 class="text-gray-400 text-sm">07/05/2025</h2>
              </div>
              <Button variant="outline">
                <Clipboard class="w-4 h-4" />
                Ver ficha
              </Button>
            </div>
          </div>
        </div>
      </TabsContent>
    </Tabs>
  </div>
</template>
