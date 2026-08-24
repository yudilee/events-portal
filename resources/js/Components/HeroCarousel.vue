<template>
  <div
    class="relative rounded-3xl overflow-hidden glass-card border border-teal-500/30 shadow-2xl group select-none min-h-[460px] sm:min-h-[520px] flex items-center"
    @mouseenter="pauseAutoplay"
    @mouseleave="startAutoplay"
  >
    <!-- Slides Background Container -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div
        v-for="(slide, index) in currentSlides"
        :key="slide.id || index"
        class="absolute inset-0 transition-opacity duration-1000 ease-in-out bg-cover bg-center"
        :class="currentSlideIndex === index ? 'opacity-100 scale-100' : 'opacity-0 scale-105 pointer-events-none'"
        :style="{
          backgroundImage: `url(${slide.image_path || '/images/hero-bg.jpg'})`,
          transition: 'opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 8s linear'
        }"
      >
        <!-- Luxury Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/50"></div>
      </div>
    </div>

    <!-- Slide Content Container -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-12 py-10 sm:py-16">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <!-- Left Text & Actions -->
        <div class="lg:col-span-7 space-y-5">
          <!-- Slide Badge -->
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-teal-500/40 text-teal-300 text-xs font-bold uppercase tracking-widest animate-fadeIn">
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
            <span>{{ activeSlide.badge_text || 'Featured Showcase' }}</span>
          </div>

          <!-- Slide Heading -->
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight leading-[1.15] drop-shadow-md">
            {{ activeSlide.title }}
          </h1>

          <!-- Slide Subtitle -->
          <p class="text-xs sm:text-sm text-slate-300 font-light leading-relaxed max-w-xl">
            {{ activeSlide.subtitle }}
          </p>

          <!-- CTA Buttons -->
          <div class="pt-2 flex flex-wrap items-center gap-3">
            <Link
              v-if="activeSlide.cta_url"
              :href="activeSlide.cta_url"
              class="px-6 py-3 rounded-2xl font-bold text-xs sm:text-sm bg-gradient-to-r from-teal-500 via-teal-400 to-cyan-400 text-slate-950 shadow-xl shadow-teal-950/60 hover:shadow-teal-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2"
            >
              <span>{{ activeSlide.cta_text || 'Explore Event' }}</span>
              <ArrowRight class="w-4 h-4" />
            </Link>

            <Link
              v-if="activeSlide.secondary_cta_url"
              :href="activeSlide.secondary_cta_url"
              class="px-5 py-3 rounded-2xl font-semibold text-xs sm:text-sm bg-slate-900/80 hover:bg-slate-800 text-white border border-slate-700/80 transition-all flex items-center gap-2"
            >
              <span>{{ activeSlide.secondary_cta_text }}</span>
            </Link>
          </div>
        </div>

        <!-- Right Side: Live Countdown or Event Meta Card -->
        <div v-if="activeSlide.show_countdown && activeSlide.countdown_date" class="lg:col-span-5 flex flex-col items-center lg:items-end justify-center">
          <div class="rounded-3xl p-5 sm:p-6 border border-teal-500/30 shadow-2xl bg-slate-950/85 backdrop-blur-xl max-w-md w-full text-center space-y-3.5">
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 block">
              Event Starts In
            </span>
            <CountdownTimer
              :target-date="activeSlide.countdown_date"
              :target-time="activeSlide.countdown_time || '10:00'"
            />
            <div class="pt-1.5 border-t border-slate-800/80 text-[0.7rem] text-slate-400">
              Live updates & RSVP pass generator
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Arrows -->
    <button
      type="button"
      @click="prevSlide"
      class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-950/80 border border-slate-700/80 text-white hover:text-teal-400 hover:border-teal-400 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-30 shadow-xl"
      aria-label="Previous Slide"
    >
      <ChevronLeft class="w-5 h-5" />
    </button>

    <button
      type="button"
      @click="nextSlide"
      class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-950/80 border border-slate-700/80 text-white hover:text-teal-400 hover:border-teal-400 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-30 shadow-xl"
      aria-label="Next Slide"
    >
      <ChevronRight class="w-5 h-5" />
    </button>

    <!-- Bottom Controls: Progress Bar & Dots -->
    <div class="absolute bottom-4 left-6 right-6 z-20 flex items-center justify-between">
      <!-- Dots -->
      <div class="flex items-center gap-2">
        <button
          v-for="(slide, idx) in currentSlides"
          :key="idx"
          @click="goToSlide(idx)"
          class="h-2 rounded-full transition-all duration-300"
          :class="currentSlideIndex === idx ? 'w-8 bg-teal-400' : 'w-2 bg-slate-600/60 hover:bg-slate-400'"
          :aria-label="`Slide ${idx + 1}`"
        />
      </div>

      <!-- Slide Counter -->
      <div class="text-[0.7rem] font-mono text-slate-400 tracking-wider">
        <span class="text-teal-400 font-bold">{{ currentSlideIndex + 1 }}</span> / {{ currentSlides.length }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import CountdownTimer from './CountdownTimer.vue';
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
  slides: {
    type: Array,
    default: () => [],
  },
});

// Fallback high-fidelity default slides if database is empty
const fallbackSlides = [
  {
    id: 1,
    title: 'Experience Automotive Excellence & Innovation',
    subtitle: 'Customer Gathering CV HRM Jakarta & Soft Opening HR Auto Studio Body Repair & Detailing facility.',
    badge_text: 'Featured Gathering • Aug 27',
    image_path: 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1600&q=80',
    cta_text: 'Confirm Your Attendance',
    cta_url: '/events',
    secondary_cta_text: 'Browse Calendar',
    secondary_cta_url: '/events',
    show_countdown: true,
    countdown_date: '2026-08-27',
    countdown_time: '10:00',
  },
  {
    id: 2,
    title: 'HR Auto Studio: State-of-the-Art Body & Detailing',
    subtitle: 'Official soft launch of our certified Mercedes-Benz body repair and luxury detailing studio in Jakarta.',
    badge_text: 'Grand Soft Opening',
    image_path: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1600&q=80',
    cta_text: 'Explore Facility Gallery',
    cta_url: '/events',
    secondary_cta_text: 'View Press Kit',
    secondary_cta_url: '/media-kit',
    show_countdown: false,
  },
  {
    id: 3,
    title: 'Hartono Group Corporate Ecosystem Since 1970',
    subtitle: 'Over 55 years of everlasting, continuous growth across premium dealership networks and transport services.',
    badge_text: 'Corporate Heritage',
    image_path: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1600&q=80',
    cta_text: 'Discover Network',
    cta_url: '/news',
    secondary_cta_text: 'Media Room',
    secondary_cta_url: '/media-kit',
    show_countdown: false,
  },
];

const currentSlides = computed(() => {
  return props.slides && props.slides.length > 0 ? props.slides : fallbackSlides;
});

const currentSlideIndex = ref(0);
const isPaused = ref(false);
let timer = null;

const activeSlide = computed(() => {
  return currentSlides.value[currentSlideIndex.value] || currentSlides.value[0];
});

const nextSlide = () => {
  currentSlideIndex.value = (currentSlideIndex.value + 1) % currentSlides.value.length;
};

const prevSlide = () => {
  currentSlideIndex.value = (currentSlideIndex.value - 1 + currentSlides.value.length) % currentSlides.value.length;
};

const goToSlide = (idx) => {
  currentSlideIndex.value = idx;
};

const startAutoplay = () => {
  isPaused.value = false;
  clearInterval(timer);
  timer = setInterval(() => {
    if (!isPaused.value) {
      nextSlide();
    }
  }, 6500);
};

const pauseAutoplay = () => {
  isPaused.value = true;
};

onMounted(() => {
  startAutoplay();
});

onBeforeUnmount(() => {
  clearInterval(timer);
});
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.4s ease-out forwards;
}
</style>
