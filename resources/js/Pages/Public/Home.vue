<template>
  <PublicLayout>
    <Head title="Official Event & Corporate Experience Portal" />

    <!-- Hero Showcase Carousel Section -->
    <section class="relative pt-6 sm:pt-10 pb-16 overflow-hidden">
      <!-- Background Ambient Glow -->
      <div class="absolute inset-0 pointer-events-none -z-10 flex items-center justify-center">
        <div class="w-[800px] h-[450px] bg-teal-600/10 rounded-full blur-[140px]"></div>
        <div class="w-[500px] h-[300px] bg-cyan-500/10 rounded-full blur-[100px] -translate-y-24"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Interactive Hero Slider Component -->
        <HeroCarousel :slides="slides" />
      </div>
    </section>

    <!-- Business Units Ecosystem -->
    <section class="py-16 bg-slate-950/60 light:bg-[#EDF5F6] border-y border-slate-800/80 light:border-slate-200 transition-colors duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Integrated Ecosystem</span>
          <h2 class="text-3xl font-extrabold text-white light:text-slate-900 font-heading">
            Hartono Group Business Units
          </h2>
          <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 font-light">
            Providing service excellence through our one-stop automotive and logistics ecosystem.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
          <div
            v-for="unit in businessUnits"
            :key="unit.id"
            class="glass-card rounded-2xl p-5 border border-slate-800/80 light:border-slate-200 hover:border-teal-500/40 transition-all group flex flex-col justify-between shadow-lg"
          >
            <div>
              <!-- Dynamic Icon with Selected Theme Color -->
              <div
                class="w-10 h-10 rounded-xl border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                :class="getUnitBadgeClass(unit.badge_color)"
              >
                <component :is="getUnitIcon(unit.icon)" class="w-5 h-5" />
              </div>

              <h3 class="text-sm font-bold text-white light:text-slate-900 font-heading group-hover:text-teal-300 light:group-hover:text-teal-700 transition-colors">
                {{ unit.name }}
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600 mt-1 font-light line-clamp-3">
                {{ unit.subtitle || unit.description }}
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between text-[0.7rem] text-slate-400 light:text-slate-600">
              <span>{{ unit.events_count || 0 }} Events</span>
              <Link :href="route('public.events.index', { unit: unit.slug })" class="text-teal-400 light:text-teal-700 hover:underline font-semibold">
                Explore &rarr;
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Upcoming Events Grid -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Corporate Calendar</span>
            <h2 class="text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
              Upcoming Events & Launches
            </h2>
          </div>
          <Link
            :href="route('public.events.index')"
            class="text-sm font-semibold text-teal-400 light:text-teal-700 hover:underline flex items-center gap-1.5"
          >
            <span>View All Events</span>
            <ArrowRight class="w-4 h-4" />
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <EventCard
            v-for="event in featuredEvents"
            :key="event.id"
            :event="event"
          />
        </div>
      </div>
    </section>

    <!-- Latest News & Media Releases -->
    <section v-if="latestNews.length > 0" class="py-16 bg-slate-950/40 light:bg-[#EDF5F6] border-t border-slate-800/60 light:border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700">Press & Statements</span>
            <h2 class="text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
              Latest Media Releases
            </h2>
          </div>
          <Link
            :href="route('public.news.index')"
            class="text-sm font-semibold text-cyan-400 light:text-cyan-700 hover:underline flex items-center gap-1.5"
          >
            <span>Media Center</span>
            <ArrowRight class="w-4 h-4" />
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="article in latestNews"
            :key="article.id"
            class="glass-card rounded-2xl p-6 border border-slate-800/80 light:border-slate-200 hover:border-cyan-500/40 transition-all flex flex-col justify-between shadow-lg"
          >
            <div>
              <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500 mb-3">
                <span class="text-cyan-400 light:text-cyan-700 font-semibold uppercase tracking-wider text-[0.7rem]">
                  {{ article.business_unit?.name || 'Press Release' }}
                </span>
                <span>{{ new Date(article.published_at).toLocaleDateString() }}</span>
              </div>
              <h3 class="text-base font-bold text-white light:text-slate-900 font-heading hover:text-cyan-300 light:hover:text-cyan-700 transition-colors mb-2">
                <Link :href="route('public.news.show', article.slug)">
                  {{ article.title }}
                </Link>
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600 line-clamp-3 font-light leading-relaxed">
                {{ article.summary || article.content }}
              </p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between">
              <Link
                :href="route('public.news.show', article.slug)"
                class="text-xs font-semibold text-cyan-400 light:text-cyan-700 hover:underline flex items-center gap-1"
              >
                <span>Read Full Article</span>
                <ChevronRight class="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import HeroCarousel from '../../Components/HeroCarousel.vue';
import EventCard from '../../Components/EventCard.vue';
import {
  ArrowRight,
  ChevronRight,
  Building2,
  Car,
  Sparkles,
  Key,
  Truck,
  Container,
  ShieldCheck,
  Wrench,
  Layers,
  Globe
} from 'lucide-vue-next';

defineProps({
  slides: {
    type: Array,
    default: () => [],
  },
  featuredEvents: {
    type: Array,
    default: () => [],
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
  latestNews: {
    type: Array,
    default: () => [],
  },
});

const iconsMap = {
  Car,
  Sparkles,
  Key,
  Truck,
  Container,
  Building2,
  ShieldCheck,
  Wrench,
  Layers,
  Globe,
};

const getUnitIcon = (iconName) => {
  return iconsMap[iconName] || Building2;
};

const getUnitBadgeClass = (color) => {
  switch (color) {
    case 'cyan':
      return 'bg-cyan-950/60 light:bg-cyan-50 border-cyan-800/40 light:border-cyan-200 text-cyan-400 light:text-cyan-700';
    case 'gold':
      return 'bg-amber-950/60 light:bg-amber-50 border-amber-800/40 light:border-amber-200 text-amber-400 light:text-amber-700';
    case 'orange':
      return 'bg-orange-950/60 light:bg-orange-50 border-orange-800/40 light:border-orange-200 text-orange-400 light:text-orange-700';
    case 'rose':
      return 'bg-rose-950/60 light:bg-rose-50 border-rose-800/40 light:border-rose-200 text-rose-400 light:text-rose-700';
    case 'indigo':
      return 'bg-indigo-950/60 light:bg-indigo-50 border-indigo-800/40 light:border-indigo-200 text-indigo-400 light:text-indigo-700';
    case 'slate':
      return 'bg-slate-900 light:bg-slate-100 border-slate-700 light:border-slate-300 text-slate-300 light:text-slate-700';
    default:
      return 'bg-teal-950/60 light:bg-teal-50 border-teal-800/40 light:border-teal-200 text-teal-400 light:text-teal-700';
  }
};
</script>
