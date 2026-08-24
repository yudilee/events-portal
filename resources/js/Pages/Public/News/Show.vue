<template>
  <PublicLayout>
    <Head :title="article.title" />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500 mb-8">
        <Link :href="route('public.news.index')" class="hover:text-cyan-400 light:hover:text-cyan-700">Press Center</Link>
        <span>/</span>
        <span class="text-slate-200 light:text-slate-800 line-clamp-1">{{ article.title }}</span>
      </div>

      <!-- Article Header -->
      <header class="space-y-4 mb-10 pb-8 border-b border-slate-800 light:border-slate-200">
        <div class="flex items-center gap-3">
          <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-cyan-950/80 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 border border-cyan-800/40 light:border-cyan-300">
            {{ article.business_unit?.name || 'Official Press Release' }}
          </span>
          <span class="text-xs text-slate-400 light:text-slate-500">{{ new Date(article.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</span>
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-white light:text-slate-900 font-heading leading-tight">
          {{ article.title }}
        </h1>

        <p v-if="article.summary" class="text-base sm:text-lg text-slate-300 light:text-slate-700 font-light leading-relaxed">
          {{ article.summary }}
        </p>
      </header>

      <!-- Article Content Body -->
      <div class="glass-card rounded-3xl p-6 sm:p-10 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 text-sm sm:text-base text-slate-200 light:text-slate-800 leading-relaxed font-light whitespace-pre-line shadow-lg light:shadow-sm">
        {{ article.content }}
      </div>

      <!-- Press Inquiries & Media Kit Link -->
      <div class="mt-12 glass-card rounded-3xl p-6 sm:p-8 border border-cyan-500/30 light:border-cyan-300 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-900/90 light:bg-white shadow-md">
        <div>
          <h4 class="text-base font-bold text-white light:text-slate-900 font-heading">Press & Editorial Inquiries</h4>
          <p class="text-xs text-slate-400 light:text-slate-600 mt-1 font-light">
            Download high-res brand logos and media assets from our official Media Kit.
          </p>
        </div>
        <Link
          :href="route('public.media-kit')"
          class="px-5 py-3 rounded-xl font-bold text-xs bg-cyan-500 hover:bg-cyan-400 text-slate-950 shadow-md transition-all shrink-0 flex items-center gap-2"
        >
          <Download class="w-4 h-4" />
          <span>Download Media Assets</span>
        </Link>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import { Download } from 'lucide-vue-next';

defineProps({
  article: {
    type: Object,
    required: true,
  },
  relatedNews: {
    type: Array,
    default: () => [],
  },
});
</script>
