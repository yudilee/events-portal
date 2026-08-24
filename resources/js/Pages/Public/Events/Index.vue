<template>
  <PublicLayout>
    <Head title="Events Calendar & Customer Gatherings" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="max-w-3xl mb-10 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Official Calendar</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight">
          Hartono Group Events
        </h1>
        <p class="text-sm sm:text-base text-slate-300 light:text-slate-600 font-light leading-relaxed">
          Explore upcoming customer gatherings, soft openings, automotive exhibitions, and exclusive dealer events.
        </p>
      </div>

      <!-- Filters & Search Bar -->
      <div class="glass-card rounded-2xl p-4 mb-10 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white flex flex-col md:flex-row gap-4 items-center justify-between shadow-md">
        <!-- Business Unit Filter Badges -->
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
          <button
            @click="filterByUnit(null)"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all',
              !selectedUnit
                ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-950/50'
                : 'bg-slate-800/80 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700/50 light:border-slate-200'
            ]"
          >
            All Units
          </button>
          <button
            v-for="unit in businessUnits"
            :key="unit.id"
            @click="filterByUnit(unit.slug)"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all',
              selectedUnit === unit.slug
                ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-950/50'
                : 'bg-slate-800/80 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700/50 light:border-slate-200'
            ]"
          >
            {{ unit.name }}
          </button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:w-72">
          <Search class="w-4 h-4 text-slate-400 light:text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Search events or venue..."
            class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-900/80 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 placeholder-slate-400 light:placeholder-slate-500 focus:outline-none focus:border-teal-400"
          />
        </div>
      </div>

      <!-- Events List -->
      <div v-if="events.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <EventCard
          v-for="event in events"
          :key="event.id"
          :event="event"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="glass-card rounded-3xl p-12 text-center border border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white max-w-lg mx-auto shadow-md">
        <CalendarX class="w-12 h-12 text-slate-500 light:text-slate-400 mx-auto mb-4" />
        <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading">No Events Found</h3>
        <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
          Try resetting your filters or search keywords to view other upcoming gatherings.
        </p>
        <button
          @click="resetFilters"
          class="mt-6 px-4 py-2 rounded-xl bg-slate-800 light:bg-slate-100 text-teal-400 light:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700 light:border-slate-300 text-xs font-semibold transition-colors"
        >
          Reset Filters
        </button>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import EventCard from '../../../Components/EventCard.vue';
import { Search, CalendarX } from 'lucide-vue-next';

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const selectedUnit = ref(props.filters.unit || null);
const searchQuery = ref(props.filters.search || '');

const filterByUnit = (slug) => {
  selectedUnit.value = slug;
  applyFilters();
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(applyFilters, 300);
};

const applyFilters = () => {
  router.get(route('public.events.index'), {
    unit: selectedUnit.value,
    search: searchQuery.value || undefined,
  }, {
    preserveState: true,
    replace: true,
  });
};

const resetFilters = () => {
  selectedUnit.value = null;
  searchQuery.value = '';
  router.get(route('public.events.index'));
};
</script>
