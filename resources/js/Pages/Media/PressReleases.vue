<template>
  <PublicLayout>
    <Head title="Press Releases — Media Portal" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-cyan-400">Media Portal</span>
        <h1 class="text-3xl font-extrabold text-white font-heading mt-1">Press Releases Repository</h1>
      </div>

      <div v-if="releases.data && releases.data.length > 0" class="space-y-4">
        <div
          v-for="rel in releases.data"
          :key="rel.id"
          class="glass-card rounded-2xl p-6 border border-slate-800 space-y-2"
        >
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-cyan-400">
              {{ rel.business_unit?.name || 'Hartono Group' }}
            </span>
            <span class="text-xs text-slate-400">{{ new Date(rel.published_at).toLocaleDateString() }}</span>
          </div>

          <h3 class="text-base font-bold text-white font-heading">{{ rel.title }}</h3>
          <p class="text-xs text-slate-300 font-light">{{ rel.summary || rel.content }}</p>

          <div class="pt-2 flex items-center justify-between">
            <Link
              :href="route('public.news.show', rel.slug)"
              class="text-xs font-semibold text-cyan-400 hover:underline"
            >
              Open Full Press Release &rarr;
            </Link>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

defineProps({
  releases: {
    type: Object,
    required: true,
  },
});
</script>
