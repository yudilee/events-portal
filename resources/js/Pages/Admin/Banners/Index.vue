<template>
  <AdminLayout>
    <Head title="Hero Carousel & Banners — Organizer Back-Office" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Front Page Showcase</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Hero Carousel & Banners
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Manage interactive showcase slides, live event countdown banners, and promo highlights displayed on the homepage.
          </p>
        </div>

        <Link
          :href="route('admin.banners.create')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all self-start sm:self-auto"
        >
          <Plus class="w-4 h-4" />
          <span>Add New Slide</span>
        </Link>
      </div>

      <!-- Slides Grid / Table -->
      <div class="glass-card rounded-3xl border border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white overflow-hidden shadow-xl">
        <div v-if="slides.length === 0" class="p-12 text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-teal-950/60 light:bg-teal-50 border border-teal-800/40 light:border-teal-300 text-teal-400 light:text-teal-700 mx-auto flex items-center justify-center">
            <Sliders class="w-8 h-8" />
          </div>
          <div>
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">No Custom Slides Configured</h3>
            <p class="text-xs text-slate-400 light:text-slate-500 max-w-md mx-auto mt-1">
              The front page is currently showing default high-resolution brand slides. Create your first slide to feature specific corporate gatherings or announcements.
            </p>
          </div>
          <Link
            :href="route('admin.banners.create')"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-teal-500 text-slate-950 font-bold text-xs"
          >
            <Plus class="w-4 h-4" />
            <span>Create First Slide</span>
          </Link>
        </div>

        <div v-else class="divide-y divide-slate-800/80 light:divide-slate-200">
          <div
            v-for="(slide, index) in slides"
            :key="slide.id"
            class="p-5 sm:p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors"
          >
            <!-- Left: Thumbnail & Info -->
            <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
              <!-- Thumbnail -->
              <div class="relative w-28 sm:w-36 h-20 sm:h-24 rounded-2xl overflow-hidden shrink-0 border border-slate-700/60 light:border-slate-300 bg-slate-900 light:bg-slate-100">
                <img
                  :src="slide.image_path"
                  :alt="slide.title"
                  class="w-full h-full object-cover"
                />
                <span
                  v-if="slide.badge_text"
                  class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[0.6rem] font-bold bg-slate-950/90 light:bg-white text-teal-300 light:text-teal-800 border border-teal-500/30 shadow-sm"
                >
                  {{ slide.badge_text }}
                </span>
              </div>

              <!-- Details -->
              <div class="space-y-1 min-w-0 flex-1">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-mono font-bold text-teal-400 light:text-teal-700">#{{ index + 1 }}</span>
                  <h3 class="text-sm sm:text-base font-bold text-white light:text-slate-900 font-heading truncate">
                    {{ slide.title }}
                  </h3>
                </div>

                <p v-if="slide.subtitle" class="text-xs text-slate-400 light:text-slate-600 line-clamp-1">
                  {{ slide.subtitle }}
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-1 text-[0.7rem] text-slate-400 light:text-slate-500">
                  <span v-if="slide.event" class="text-teal-300 light:text-teal-700 font-medium flex items-center gap-1">
                    <Calendar class="w-3 h-3" />
                    Linked: {{ slide.event.title }}
                  </span>

                  <span v-if="slide.show_countdown" class="text-cyan-300 light:text-cyan-700 font-mono flex items-center gap-1">
                    <Clock class="w-3 h-3" />
                    Countdown: {{ slide.countdown_date }} {{ slide.countdown_time }}
                  </span>

                  <span v-if="slide.cta_text" class="text-slate-300 light:text-slate-700">
                    CTA: <strong>{{ slide.cta_text }}</strong> &rarr; {{ slide.cta_url }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Right: Status Toggle & Actions -->
            <div class="flex items-center gap-3 shrink-0 self-end lg:self-center">
              <button
                type="button"
                @click="toggleStatus(slide.id)"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors border',
                  slide.is_active
                    ? 'bg-emerald-950/80 light:bg-emerald-100 text-emerald-300 light:text-emerald-800 border-emerald-800/40 light:border-emerald-300'
                    : 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 border-slate-700 light:border-slate-300'
                ]"
              >
                <span class="w-2 h-2 rounded-full" :class="slide.is_active ? 'bg-emerald-400' : 'bg-slate-500'"></span>
                <span>{{ slide.is_active ? 'Active' : 'Inactive' }}</span>
              </button>

              <Link
                :href="route('admin.banners.edit', slide.id)"
                class="p-2 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-teal-300 light:hover:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors"
                title="Edit Slide"
              >
                <Edit3 class="w-4 h-4" />
              </Link>

              <button
                type="button"
                @click="deleteSlide(slide.id)"
                class="p-2 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 hover:text-rose-400 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors"
                title="Delete Slide"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import {
  Sliders,
  Plus,
  Calendar,
  Clock,
  Edit3,
  Trash2
} from 'lucide-vue-next';

defineProps({
  slides: {
    type: Array,
    default: () => [],
  },
});

const toggleStatus = (id) => {
  router.post(route('admin.banners.toggle', id), {}, { preserveScroll: true });
};

const deleteSlide = (id) => {
  if (confirm('Are you sure you want to remove this hero slide?')) {
    router.delete(route('admin.banners.destroy', id));
  }
};
</script>
