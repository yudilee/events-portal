<template>
  <PublicLayout>
    <Head title="Media Press Room — News Agency Portal" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Media Header Banner -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-cyan-500/30 light:border-cyan-300 mb-10 bg-gradient-to-r from-cyan-950/60 via-slate-900 to-slate-950 light:from-cyan-50 light:via-white light:to-slate-50 relative overflow-hidden shadow-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-teal-600 flex items-center justify-center text-slate-950 font-extrabold text-2xl font-heading shadow-lg shadow-cyan-500/30">
              <Newspaper class="w-8 h-8" />
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h1 class="text-xl sm:text-2xl font-bold text-white light:text-slate-900 font-heading">
                  {{ $page.props.auth?.user?.name }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-extrabold uppercase tracking-widest bg-cyan-400 text-slate-950">
                  Accredited Press
                </span>
              </div>
              <p class="text-xs text-slate-400 light:text-slate-600 mt-1 font-light">
                {{ $page.props.auth?.user?.company || 'Media Editorial Desk' }} • Official Media Portal
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <Link
              :href="route('public.media-kit')"
              class="px-4 py-2.5 rounded-xl text-xs font-bold bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition-colors flex items-center gap-1.5 shadow-md"
            >
              <Download class="w-3.5 h-3.5" />
              <span>Media Kit</span>
            </Link>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Embargoed & Exclusive Releases -->
        <div class="lg:col-span-8 space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white light:text-slate-900 font-heading">Official Press Releases</h2>
            <Link :href="route('media.press-releases')" class="text-xs text-cyan-400 light:text-cyan-700 font-semibold hover:underline">
              View All &rarr;
            </Link>
          </div>

          <div class="space-y-4">
            <div
              v-for="release in pressReleases"
              :key="release.id"
              class="glass-card rounded-2xl p-6 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-3 shadow-md"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span
                    v-if="release.is_embargoed"
                    class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase tracking-wider bg-rose-950 light:bg-rose-100 text-rose-300 light:text-rose-800 border border-rose-800 light:border-rose-300"
                  >
                    🔒 Embargoed Until {{ new Date(release.embargo_until).toLocaleDateString() }}
                  </span>
                  <span
                    v-else
                    class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase tracking-wider bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300"
                  >
                    Public Release
                  </span>
                </div>
                <span class="text-xs text-slate-400 light:text-slate-500">{{ new Date(release.published_at).toLocaleDateString() }}</span>
              </div>

              <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
                {{ release.title }}
              </h3>

              <p class="text-xs text-slate-300 light:text-slate-600 font-light leading-relaxed">
                {{ release.summary || release.content }}
              </p>

              <div class="pt-2 flex items-center justify-between">
                <Link
                  :href="route('public.news.show', release.slug)"
                  class="text-xs font-bold text-cyan-400 light:text-cyan-700 hover:text-cyan-300 flex items-center gap-1"
                >
                  <span>Full Briefing</span>
                  <ChevronRight class="w-3.5 h-3.5" />
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Media Accreditations -->
        <div class="lg:col-span-4 space-y-6">
          <div class="glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-4 shadow-md">
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">My Press Accreditations</h3>

            <div v-if="myAccreditations.length > 0" class="space-y-3">
              <div
                v-for="acc in myAccreditations"
                :key="acc.id"
                class="p-4 rounded-2xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-2"
              >
                <div class="flex items-center justify-between text-xs">
                  <span class="font-mono text-cyan-400 light:text-cyan-700 font-bold">{{ acc.registration_code }}</span>
                  <span class="px-2 py-0.5 rounded-full bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 text-[0.65rem] uppercase">Press Pass</span>
                </div>
                <div class="text-xs font-bold text-white light:text-slate-900">{{ acc.event.title }}</div>
                <Link
                  :href="route('public.events.ticket', { slug: acc.event.slug, code: acc.registration_code })"
                  class="text-xs text-cyan-400 light:text-cyan-700 hover:underline block pt-1 font-semibold"
                >
                  View QR Media Pass &rarr;
                </Link>
              </div>
            </div>

            <div v-else class="text-xs text-slate-400 light:text-slate-500 py-4 text-center">
              No press accreditations currently requested.
            </div>

            <!-- Upcoming Events to request press pass -->
            <div class="pt-4 border-t border-slate-800 light:border-slate-200">
              <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 light:text-slate-600 mb-3">Request Event Coverage Pass</h4>
              <div class="space-y-2.5">
                <div
                  v-for="event in upcomingEvents"
                  :key="event.id"
                  class="p-3 rounded-xl bg-slate-900 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex items-center justify-between gap-2 text-xs"
                >
                  <span class="text-white light:text-slate-900 font-medium line-clamp-1">{{ event.title }}</span>
                  <Link
                    :href="route('media.accreditation', event.id)"
                    method="post"
                    as="button"
                    class="px-2.5 py-1 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-slate-950 font-bold shrink-0 text-[0.7rem] shadow-sm"
                  >
                    Request Pass
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import { Newspaper, Download, ChevronRight } from 'lucide-vue-next';

defineProps({
  pressReleases: {
    type: Array,
    default: () => [],
  },
  myAccreditations: {
    type: Array,
    default: () => [],
  },
  upcomingEvents: {
    type: Array,
    default: () => [],
  },
});
</script>
