<template>
  <PublicLayout>
    <Head title="Press Room & Corporate News" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="max-w-3xl mb-12 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700">Media Center</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight">
          News & Press Releases
        </h1>
        <p class="text-sm sm:text-base text-slate-300 light:text-slate-600 font-light leading-relaxed">
          Official announcements, media briefings, event recaps, and corporate milestones from Hartono Group.
        </p>
      </div>

      <!-- Articles Grid -->
      <div v-if="news.data && news.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <article
          v-for="article in news.data"
          :key="article.id"
          class="glass-card rounded-3xl p-7 border border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white hover:border-cyan-500/40 light:hover:border-cyan-500 transition-all flex flex-col justify-between group shadow-lg light:shadow-sm"
        >
          <div class="space-y-4">
            <div class="flex items-center justify-between text-xs">
              <span class="text-[0.65rem] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-cyan-950/80 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 border border-cyan-800/40 light:border-cyan-300">
                {{ article.business_unit?.name || 'Corporate' }}
              </span>
              <span class="text-slate-400 light:text-slate-500">{{ new Date(article.published_at).toLocaleDateString() }}</span>
            </div>

            <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading group-hover:text-cyan-300 light:group-hover:text-cyan-700 transition-colors leading-snug">
              <Link :href="route('public.news.show', article.slug)">
                {{ article.title }}
              </Link>
            </h3>

            <p class="text-xs text-slate-300 light:text-slate-600 line-clamp-3 font-light leading-relaxed">
              {{ article.summary || article.content }}
            </p>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between">
            <Link
              :href="route('public.news.show', article.slug)"
              class="text-xs font-semibold text-cyan-400 light:text-cyan-700 hover:text-cyan-300 flex items-center gap-1"
            >
              <span>Read Full Release</span>
              <ChevronRight class="w-3.5 h-3.5" />
            </Link>

            <span class="text-[0.7rem] text-slate-400 light:text-slate-500">
              {{ article.views_count }} views
            </span>
          </div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="glass-card rounded-3xl p-12 text-center border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white max-w-md mx-auto shadow-md">
        <Newspaper class="w-12 h-12 text-slate-500 light:text-slate-400 mx-auto mb-3" />
        <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading">No Press Releases Yet</h3>
        <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
          Check back soon for latest corporate announcements.
        </p>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import { Newspaper, ChevronRight } from 'lucide-vue-next';

defineProps({
  news: {
    type: Object,
    required: true,
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
});
</script>
