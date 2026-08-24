<template>
  <div class="glass-card glass-card-hover rounded-3xl overflow-hidden flex flex-col justify-between border border-slate-800/80 light:border-slate-200 group shadow-lg light:shadow-sm">
    <!-- Card Top Header with Visual Glow -->
    <div class="p-6 sm:p-7 relative">
      <!-- Gradient Accent line on top of card -->
      <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 via-cyan-400 to-transparent"></div>

      <div class="flex items-center justify-between gap-3 mb-4">
        <span
          v-if="event.business_unit"
          class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-teal-950/80 light:bg-teal-50 text-teal-300 light:text-teal-700 border border-teal-800/40 light:border-teal-200"
        >
          {{ event.business_unit.name }}
        </span>
        <span
          v-else
          class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700"
        >
          Corporate Gathering
        </span>

        <!-- Dress Code Badge -->
        <span class="text-xs font-medium px-2.5 py-0.5 rounded-md bg-slate-800/80 light:bg-slate-100 text-slate-300 light:text-slate-700 border border-slate-700/50 light:border-slate-200">
          👔 {{ event.dress_code }}
        </span>
      </div>

      <!-- Title & Subtitle -->
      <h3 class="text-xl font-bold text-white light:text-slate-900 group-hover:text-teal-400 light:group-hover:text-teal-700 transition-colors font-heading leading-snug mb-2">
        <Link :href="route('public.events.show', event.slug)">
          {{ event.title }}
        </Link>
      </h3>
      <p v-if="event.subtitle" class="text-xs text-slate-400 light:text-slate-600 line-clamp-2 leading-relaxed mb-6 font-light">
        {{ event.subtitle }}
      </p>

      <!-- Event Details Grid -->
      <div class="space-y-2.5 text-xs text-slate-300 light:text-slate-700 border-t border-slate-800/60 light:border-slate-200 pt-4">
        <div class="flex items-center gap-2.5">
          <Calendar class="w-4 h-4 text-teal-400 light:text-teal-700 shrink-0" />
          <span class="font-semibold text-slate-200 light:text-slate-900">{{ formattedDate }}</span>
          <span class="text-slate-500 light:text-slate-400">•</span>
          <Clock class="w-4 h-4 text-teal-400 light:text-teal-700 shrink-0" />
          <span>{{ event.start_time }} {{ event.timezone }}</span>
        </div>

        <div class="flex items-start gap-2.5">
          <MapPin class="w-4 h-4 text-teal-400 light:text-teal-700 shrink-0 mt-0.5" />
          <span class="line-clamp-1 text-slate-300 light:text-slate-600">{{ event.venue_name }}</span>
        </div>
      </div>
    </div>

    <!-- Card Action Bottom Bar -->
    <div class="p-6 bg-slate-950/40 light:bg-slate-50 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between gap-3">
      <Link
        :href="route('public.events.show', event.slug)"
        class="text-xs font-semibold text-slate-300 light:text-slate-700 hover:text-teal-400 light:hover:text-teal-700 flex items-center gap-1 transition-colors"
      >
        View Schedule
        <ArrowRight class="w-3.5 h-3.5" />
      </Link>

      <Link
        v-if="event.is_registration_enabled && !event.is_full"
        :href="route('public.events.register', event.slug)"
        class="px-4 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-md shadow-teal-950/50 transition-all flex items-center gap-1.5"
      >
        <span>RSVP Now</span>
        <ChevronRight class="w-3.5 h-3.5" />
      </Link>
      <span
        v-else
        class="text-xs font-medium px-3 py-1.5 rounded-lg bg-slate-800 light:bg-slate-200 text-slate-400 light:text-slate-600"
      >
        RSVP Closed
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Calendar, Clock, MapPin, ArrowRight, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
});

const formattedDate = computed(() => {
  if (!props.event?.date) return '';
  const dateObj = new Date(props.event.date);
  return dateObj.toLocaleDateString('en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
});
</script>
