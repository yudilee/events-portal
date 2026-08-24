<template>
  <div class="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-3 sm:before:left-4 before:top-3 before:bottom-3 before:w-0.5 before:bg-gradient-to-b before:from-teal-500 before:via-cyan-500 before:to-slate-800 light:before:to-slate-300">
    <div
      v-for="(item, index) in agendas"
      :key="item.id || index"
      class="relative group"
    >
      <!-- Timeline Node Indicator -->
      <div class="absolute -left-6 sm:-left-8 top-1.5 w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-slate-900 light:bg-white border-2 border-teal-500 flex items-center justify-center shadow-md shadow-teal-500/20 group-hover:scale-110 group-hover:border-cyan-400 transition-all">
        <div class="w-2 h-2 rounded-full bg-teal-400 light:bg-teal-600"></div>
      </div>

      <!-- Agenda Content Card -->
      <div class="glass-card rounded-2xl p-4 sm:p-5 border border-slate-800/90 light:border-slate-200 bg-slate-900/60 light:bg-white group-hover:border-teal-500/40 light:group-hover:border-teal-500 transition-all shadow-md light:shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
          <!-- Time Badge -->
          <div class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-300 light:text-teal-800 bg-teal-950/80 light:bg-teal-100 border border-teal-800/40 light:border-teal-300 px-3 py-1 rounded-full w-fit">
            <Clock class="w-3.5 h-3.5" />
            <span>{{ item.time_start }} - {{ item.time_end || 'End' }}</span>
          </div>

          <!-- Speaker Badge if present -->
          <div v-if="item.speaker_name" class="flex items-center gap-1.5 text-xs text-slate-300 light:text-slate-700 bg-slate-800/70 light:bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-700/50 light:border-slate-200">
            <UserCheck class="w-3.5 h-3.5 text-cyan-400 light:text-cyan-700 shrink-0" />
            <span class="font-medium text-white light:text-slate-900">{{ item.speaker_name }}</span>
            <span v-if="item.speaker_role" class="text-slate-400 light:text-slate-500 text-[0.7rem]">({{ item.speaker_role }})</span>
          </div>
        </div>

        <h4 class="text-base sm:text-lg font-bold text-white light:text-slate-900 font-heading mt-1">
          {{ item.title }}
        </h4>

        <p v-if="item.description" class="text-xs sm:text-sm text-slate-400 light:text-slate-600 mt-1.5 leading-relaxed font-light">
          {{ item.description }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Clock, UserCheck } from 'lucide-vue-next';

defineProps({
  agendas: {
    type: Array,
    default: () => [],
  },
});
</script>
