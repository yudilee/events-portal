<template>
  <PublicLayout>
    <Head :title="`Media Gallery — ${event.title}`" />

    <!-- Gallery Hero Header -->
    <section class="relative pt-10 pb-12 lg:pt-14 lg:pb-16 border-b border-slate-800/80 light:border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500 mb-6">
          <Link :href="route('public.events.index')" class="hover:text-teal-400 light:hover:text-teal-700">Events</Link>
          <span>/</span>
          <Link :href="route('public.events.show', event.slug)" class="hover:text-teal-400 light:hover:text-teal-700 truncate max-w-[200px]">{{ event.title }}</Link>
          <span>/</span>
          <span class="text-slate-200 light:text-slate-800 font-semibold">Media Gallery</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div class="space-y-3 max-w-3xl">
            <div class="flex items-center gap-3">
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-950/80 light:bg-teal-50 text-teal-300 light:text-teal-700 border border-teal-800/40 light:border-teal-200">
                {{ event.business_unit?.name || 'Corporate Event' }}
              </span>
              <span class="text-xs text-slate-400 light:text-slate-500">
                📍 {{ event.venue_name }}
              </span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight">
              Official Media Gallery
            </h1>

            <p class="text-sm text-slate-300 light:text-slate-600 font-light leading-relaxed">
              Explore high-resolution event photographs, behind-the-scenes moments, and video highlights from <strong class="text-white light:text-slate-900">{{ event.title }}</strong>.
            </p>
          </div>

          <!-- Shortcuts -->
          <div class="flex items-center gap-3 self-start md:self-auto">
            <Link
              v-if="event.is_post_event_enabled"
              :href="route('public.events.recap', event.slug)"
              class="px-5 py-3 rounded-2xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-lg shadow-teal-950/50 transition-all flex items-center gap-2"
            >
              <Sparkles class="w-4 h-4" />
              <span>View Post-Event Recap</span>
            </Link>

            <Link
              :href="route('public.events.show', event.slug)"
              class="px-5 py-3 rounded-2xl font-semibold text-xs glass-card bg-slate-900/60 light:bg-white hover:bg-slate-800 light:hover:bg-slate-100 text-white light:text-slate-800 border border-slate-700 light:border-slate-300 transition-all flex items-center gap-2 shadow-sm"
            >
              <ArrowLeft class="w-4 h-4" />
              <span>Back to Event</span>
            </Link>
          </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mt-8 pt-6 border-t border-slate-800/60 light:border-slate-200 flex items-center gap-3 text-xs font-semibold overflow-x-auto pb-2">
          <button
            type="button"
            @click="activeFilter = 'all'"
            :class="[
              'px-4 py-2 rounded-xl transition-all',
              activeFilter === 'all'
                ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-950/40'
                : 'glass-card bg-slate-900/60 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-200'
            ]"
          >
            All Media ({{ media.length }})
          </button>

          <button
            type="button"
            @click="activeFilter = 'image'"
            :class="[
              'px-4 py-2 rounded-xl transition-all',
              activeFilter === 'image'
                ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-950/40'
                : 'glass-card bg-slate-900/60 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-200'
            ]"
          >
            Photos ({{ photosCount }})
          </button>

          <button
            type="button"
            @click="activeFilter = 'video'"
            :class="[
              'px-4 py-2 rounded-xl transition-all',
              activeFilter === 'video'
                ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-950/40'
                : 'glass-card bg-slate-900/60 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-200'
            ]"
          >
            Videos ({{ videosCount }})
          </button>
        </div>
      </div>
    </section>

    <!-- Masonry Gallery Section -->
    <section class="py-12 sm:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Empty State -->
        <div v-if="filteredMedia.length === 0" class="p-16 text-center space-y-4 glass-card rounded-3xl border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-md">
          <div class="w-16 h-16 rounded-2xl bg-teal-950/60 light:bg-teal-50 border border-teal-800/40 light:border-teal-200 text-teal-400 light:text-teal-700 mx-auto flex items-center justify-center">
            <Camera class="w-8 h-8" />
          </div>
          <div>
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">No Media Uploaded Yet</h3>
            <p class="text-xs text-slate-400 light:text-slate-500 max-w-sm mx-auto mt-1">
              Event organizers will publish official photographs and videos here shortly after the gathering.
            </p>
          </div>
        </div>

        <!-- Masonry Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div
            v-for="(item, index) in filteredMedia"
            :key="item.id || index"
            class="group relative rounded-3xl overflow-hidden glass-card border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white hover:border-teal-500/50 light:hover:border-teal-500 shadow-xl cursor-pointer transition-all duration-300 hover:-translate-y-1"
            @click="openLightbox(index)"
          >
            <!-- Photo Item -->
            <template v-if="item.type !== 'video_url'">
              <div class="aspect-4/3 overflow-hidden bg-slate-900 light:bg-slate-100 relative">
                <img
                  :src="item.url"
                  :alt="item.caption || event.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                  <div class="text-xs font-bold text-white leading-tight line-clamp-2">
                    {{ item.caption || 'View Full Resolution' }}
                  </div>
                  <div class="mt-1 text-[0.65rem] text-teal-300 flex items-center gap-1 font-semibold">
                    <ZoomIn class="w-3.5 h-3.5" />
                    <span>Click to expand</span>
                  </div>
                </div>
              </div>
            </template>

            <!-- Video Embed Item -->
            <template v-else>
              <div class="aspect-4/3 overflow-hidden bg-slate-950 light:bg-slate-100 relative flex flex-col items-center justify-center p-6 text-center group-hover:bg-slate-900 light:group-hover:bg-slate-200 transition-colors">
                <div class="w-14 h-14 rounded-2xl bg-cyan-500 text-slate-950 flex items-center justify-center shadow-lg shadow-cyan-950/80 group-hover:scale-110 transition-transform mb-3">
                  <Play class="w-6 h-6 ml-0.5" />
                </div>
                <h4 class="text-xs font-bold text-white light:text-slate-900 font-heading line-clamp-2">
                  {{ item.caption || 'Event Video Highlight' }}
                </h4>
                <span class="mt-2 text-[0.65rem] font-mono text-cyan-400 light:text-cyan-700 uppercase tracking-wider font-bold">
                  Watch Video Reel &rarr;
                </span>
              </div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Full-Screen Lightbox Modal -->
    <div
      v-if="lightboxOpen"
      class="fixed inset-0 z-50 bg-black/95 backdrop-blur-2xl flex items-center justify-center select-none"
      @keydown.esc="closeLightbox"
      @keydown.left="prevLightbox"
      @keydown.right="nextLightbox"
      tabindex="0"
    >
      <!-- Close Button -->
      <button
        type="button"
        @click="closeLightbox"
        class="absolute top-6 right-6 p-3 rounded-full bg-slate-900/80 text-slate-300 hover:text-white hover:bg-slate-800 border border-slate-700 transition-colors z-50"
        title="Close (Esc)"
      >
        <X class="w-6 h-6" />
      </button>

      <!-- Navigation Arrows -->
      <button
        type="button"
        @click="prevLightbox"
        class="absolute left-6 top-1/2 -translate-y-1/2 p-3 rounded-full bg-slate-900/80 text-slate-300 hover:text-teal-400 hover:bg-slate-800 border border-slate-700 transition-colors z-50"
        title="Previous Image (Left Arrow)"
      >
        <ChevronLeft class="w-6 h-6" />
      </button>

      <button
        type="button"
        @click="nextLightbox"
        class="absolute right-6 top-1/2 -translate-y-1/2 p-3 rounded-full bg-slate-900/80 text-slate-300 hover:text-teal-400 hover:bg-slate-800 border border-slate-700 transition-colors z-50"
        title="Next Image (Right Arrow)"
      >
        <ChevronRight class="w-6 h-6" />
      </button>

      <!-- Center Media View -->
      <div class="max-w-5xl max-h-[85vh] p-4 flex flex-col items-center justify-center space-y-4">
        <!-- Photo -->
        <template v-if="activeLightboxMedia?.type !== 'video_url'">
          <img
            :src="activeLightboxMedia?.url"
            :alt="activeLightboxMedia?.caption || 'Event image'"
            class="max-w-full max-h-[72vh] object-contain rounded-2xl shadow-2xl border border-slate-800"
          />
        </template>

        <!-- Video Player / Embed -->
        <template v-else>
          <div class="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden border border-slate-800 shadow-2xl bg-black">
            <iframe
              v-if="getVideoEmbedUrl(activeLightboxMedia?.file_path)"
              :src="getVideoEmbedUrl(activeLightboxMedia?.file_path)"
              class="w-full h-full"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
            <video
              v-else
              :src="activeLightboxMedia?.url"
              controls
              autoplay
              class="w-full h-full object-contain"
            ></video>
          </div>
        </template>

        <!-- Bottom Caption & Actions -->
        <div class="w-full text-center space-y-1">
          <p v-if="activeLightboxMedia?.caption" class="text-sm font-semibold text-white">
            {{ activeLightboxMedia.caption }}
          </p>
          <div class="text-xs text-slate-400 font-mono">
            {{ lightboxIndex + 1 }} of {{ filteredMedia.length }}
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import {
  Sparkles,
  ArrowLeft,
  Camera,
  ZoomIn,
  Play,
  X,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  media: {
    type: Array,
    default: () => [],
  },
});

const activeFilter = ref('all');
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

const photosCount = computed(() => props.media.filter((m) => m.type !== 'video_url').length);
const videosCount = computed(() => props.media.filter((m) => m.type === 'video_url').length);

const filteredMedia = computed(() => {
  if (activeFilter.value === 'image') {
    return props.media.filter((m) => m.type !== 'video_url');
  }
  if (activeFilter.value === 'video') {
    return props.media.filter((m) => m.type === 'video_url');
  }
  return props.media;
});

const activeLightboxMedia = computed(() => {
  return filteredMedia.value[lightboxIndex.value] || null;
});

const openLightbox = (index) => {
  lightboxIndex.value = index;
  lightboxOpen.value = true;
};

const closeLightbox = () => {
  lightboxOpen.value = false;
};

const nextLightbox = () => {
  lightboxIndex.value = (lightboxIndex.value + 1) % filteredMedia.value.length;
};

const prevLightbox = () => {
  lightboxIndex.value = (lightboxIndex.value - 1 + filteredMedia.value.length) % filteredMedia.value.length;
};

const getVideoEmbedUrl = (url) => {
  if (!url) return null;
  if (url.includes('youtube.com/watch?v=')) {
    const id = url.split('v=')[1]?.split('&')[0];
    return `https://www.youtube.com/embed/${id}?autoplay=1`;
  }
  if (url.includes('youtu.be/')) {
    const id = url.split('youtu.be/')[1]?.split('?')[0];
    return `https://www.youtube.com/embed/${id}?autoplay=1`;
  }
  return null;
};
</script>
