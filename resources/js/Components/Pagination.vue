<template>
  <div
    v-if="pagination && (pagination.total > 0 || (pagination.links && pagination.links.length > 3))"
    class="px-6 py-4 border-t border-slate-800/80 light:border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-950/40 light:bg-slate-50 text-xs"
  >
    <!-- Results summary -->
    <div class="text-slate-400 light:text-slate-600 flex items-center gap-2">
      <span>
        Showing
        <strong class="font-bold text-teal-400 light:text-teal-700">{{ pagination.from || 0 }}</strong>
        to
        <strong class="font-bold text-teal-400 light:text-teal-700">{{ pagination.to || 0 }}</strong>
        of
        <strong class="font-bold text-white light:text-slate-900">{{ pagination.total || 0 }}</strong>
        {{ itemName }}
      </span>

      <!-- Per Page Selector (if enabled) -->
      <div v-if="showPerPage" class="hidden md:flex items-center gap-1.5 ml-4 pl-4 border-l border-slate-800 light:border-slate-300">
        <span class="text-[0.7rem] text-slate-400 light:text-slate-500">Per page:</span>
        <select
          :value="currentPerPage"
          @change="onPerPageChange($event.target.value)"
          class="px-2 py-1 rounded-lg bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-white light:text-slate-900 text-xs focus:outline-none focus:border-teal-400"
        >
          <option v-for="opt in perPageOptions" :key="opt" :value="opt">
            {{ opt === 'all' ? 'All' : opt }}
          </option>
        </select>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div v-if="pagination.links && pagination.links.length > 3" class="flex items-center gap-1">
      <template v-for="(link, index) in pagination.links" :key="index">
        <!-- Disabled Link (No URL) -->
        <span
          v-if="!link.url"
          class="px-3 py-1.5 rounded-xl border border-slate-800 light:border-slate-200 text-slate-600 light:text-slate-300 select-none cursor-not-allowed opacity-50 flex items-center justify-center min-w-[2rem]"
          v-html="cleanLabel(link.label)"
        />

        <!-- Active Page -->
        <span
          v-else-if="link.active"
          class="px-3 py-1.5 rounded-xl bg-teal-500 text-slate-950 font-bold border border-teal-400 shadow-md shadow-teal-950/40 select-none flex items-center justify-center min-w-[2rem]"
          v-html="cleanLabel(link.label)"
        />

        <!-- Clickable Link -->
        <Link
          v-else
          :href="link.url"
          preserve-scroll
          preserve-state
          class="px-3 py-1.5 rounded-xl bg-slate-900/80 light:bg-white text-slate-300 light:text-slate-700 border border-slate-800 light:border-slate-300 hover:bg-slate-800 light:hover:bg-slate-100 hover:text-white light:hover:text-black transition-colors flex items-center justify-center min-w-[2rem] font-medium"
          v-html="cleanLabel(link.label)"
        />
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  pagination: {
    type: Object,
    required: true,
  },
  itemName: {
    type: String,
    default: 'results',
  },
  showPerPage: {
    type: Boolean,
    default: true,
  },
  perPageOptions: {
    type: Array,
    default: () => [10, 25, 50, 100, 'all'],
  },
});

const currentPerPage = computed(() => {
  if (props.pagination?.per_page >= 9999) return 'all';
  return props.pagination?.per_page || 25;
});

const cleanLabel = (label) => {
  if (label.includes('&laquo;') || label.toLowerCase().includes('previous')) {
    return '&larr; Prev';
  }
  if (label.includes('&raquo;') || label.toLowerCase().includes('next')) {
    return 'Next &rarr;';
  }
  return label;
};

const onPerPageChange = (val) => {
  const currentUrl = new URL(window.location.href);
  if (val === 'all') {
    currentUrl.searchParams.set('per_page', 'all');
  } else {
    currentUrl.searchParams.set('per_page', val);
  }
  currentUrl.searchParams.delete('page'); // Reset to page 1

  router.get(currentUrl.pathname + currentUrl.search, {}, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};
</script>
