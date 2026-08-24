<template>
  <PublicLayout>
    <Head :title="event.title" />

    <!-- Event Hero Section -->
    <section class="relative pt-10 pb-16 lg:pt-16 lg:pb-24 overflow-hidden border-b border-slate-800/80 light:border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500 mb-6">
          <Link :href="route('public.events.index')" class="hover:text-teal-400 light:hover:text-teal-600">Events</Link>
          <span>/</span>
          <span class="text-slate-200 light:text-slate-900 line-clamp-1 font-semibold">{{ event.title }}</span>
        </div>

        <!-- Event Reschedule Urgent Announcement Banner (Matching Official Flyer) -->
        <div
          v-if="event.status === 'rescheduled' || event.reschedule_notice"
          class="mb-10 p-6 sm:p-8 rounded-3xl bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 light:from-amber-50/80 light:via-white light:to-amber-50/80 border-2 border-cyan-500/50 light:border-amber-400 shadow-2xl shadow-cyan-950/40 relative overflow-hidden"
        >
          <!-- Top Accent Light Line -->
          <div class="absolute top-0 left-1/4 right-1/4 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>

          <div class="space-y-6">
            <!-- Header Badge -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800/80 light:border-amber-200/80 pb-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-teal-500 text-slate-950 flex items-center justify-center font-black shrink-0 shadow-lg shadow-cyan-500/30">
                  <CalendarClock class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[0.65rem] font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700 block">
                    EVENT UPDATE
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-white light:text-slate-900 font-heading tracking-wider uppercase">
                    EVENT RESCHEDULE
                  </h3>
                </div>
              </div>

              <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-[0.68rem] font-black uppercase tracking-wider bg-rose-500/20 text-rose-300 light:text-rose-700 border border-rose-500/40">
                  ⚠️ PEMBERITAHUAN RESMI
                </span>
              </div>
            </div>

            <!-- Date Comparison Cards (Like Flyer) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Original Date Box -->
              <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/90 light:bg-slate-100/80 border border-slate-800 light:border-slate-300 text-center space-y-1 relative">
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-rose-400 light:text-rose-600 block">
                  ORIGINAL DATE
                </span>
                <div class="text-base sm:text-lg font-black text-slate-400 light:text-slate-500 line-through decoration-rose-500 decoration-2 font-heading">
                  {{ formatDate(event.original_date || event.date) }}
                </div>
                <div class="text-[0.7rem] text-rose-400/80 light:text-rose-600">
                  (DIBATALKAN / DIUNDUR)
                </div>
              </div>

              <!-- New Date Box -->
              <div
                v-if="event.is_date_tba"
                class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-cyan-950/60 via-slate-900 to-cyan-950/60 light:from-cyan-50 light:to-teal-50 border-2 border-cyan-400/80 light:border-cyan-500 text-center space-y-1 shadow-lg shadow-cyan-950/50 relative"
              >
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-cyan-400 light:text-cyan-700 block">
                  NEW DATE
                </span>
                <div class="text-base sm:text-xl font-black text-white light:text-cyan-950 font-heading tracking-wider animate-pulse">
                  TO BE ANNOUNCED SHORTLY
                </div>
                <div class="text-[0.7rem] text-cyan-300 light:text-cyan-700 font-medium">
                  (Akan Diumumkan Segera)
                </div>
              </div>

              <div
                v-else
                class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-teal-950/60 via-slate-900 to-teal-950/60 light:from-teal-50 light:to-teal-100 border-2 border-teal-400/80 light:border-teal-500 text-center space-y-1 shadow-lg shadow-teal-950/50 relative"
              >
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-teal-400 light:text-teal-700 block">
                  NEW DATE
                </span>
                <div class="text-base sm:text-xl font-black text-white light:text-slate-900 font-heading">
                  {{ formatDate(event.date) }}
                </div>
                <div class="text-[0.7rem] text-teal-300 light:text-teal-800 font-medium">
                  {{ event.start_time }} - {{ event.end_time || 'Finish' }} {{ event.timezone }}
                </div>
              </div>
            </div>

            <!-- Official Statement & Apology Text -->
            <div class="space-y-2 text-center max-w-3xl mx-auto pt-1">
              <p v-if="event.reschedule_notice" class="text-xs sm:text-sm text-slate-200 light:text-slate-800 leading-relaxed font-medium">
                "{{ event.reschedule_notice }}"
              </p>
              <p class="text-[0.72rem] sm:text-xs text-slate-400 light:text-slate-600 italic leading-relaxed">
                We sincerely apologize for any inconvenience this change may cause. Your safety, comfort, and convenience remain our top priority.
              </p>
              <div class="text-[0.68rem] sm:text-xs font-black uppercase tracking-wider text-teal-400 light:text-teal-700 pt-1">
                THANK YOU FOR YOUR KIND UNDERSTANDING.
              </div>
            </div>

            <!-- Ticket Guarantee Footer -->
            <div class="pt-3 border-t border-slate-800/80 light:border-amber-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
              <div class="flex items-center gap-2 text-teal-300 light:text-teal-800 font-semibold">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
                <span>Seluruh E-Tiket & Kode QR yang telah didapatkan tetap SAH dan otomatis berlaku.</span>
              </div>

              <Link
                v-if="event.is_registration_enabled && !event.is_full"
                :href="route('public.events.register', event.slug)"
                class="px-5 py-2.5 rounded-xl text-xs font-black bg-cyan-400 hover:bg-cyan-300 text-slate-950 transition-all shadow-md shrink-0"
              >
                RSVP Pre-Register Standby &rarr;
              </Link>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          <div class="lg:col-span-8 space-y-6">
            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-3">
              <span
                v-if="event.business_unit"
                class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-950/80 light:bg-teal-50 text-teal-300 light:text-teal-700 border border-teal-800/40 light:border-teal-200"
              >
                {{ event.business_unit.name }}
              </span>

              <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-800 border border-slate-700 light:border-slate-300">
                👔 Dress Code: {{ event.dress_code }}
              </span>

              <Link
                v-if="event.is_post_event_enabled && event.post_event_published_at"
                :href="route('public.events.recap', event.slug)"
                class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-950/80 light:bg-rose-50 text-rose-300 light:text-rose-700 border border-rose-800/40 light:border-rose-200 flex items-center gap-1.5 animate-pulse"
              >
                <Sparkles class="w-3.5 h-3.5" />
                <span>Post-Event Recap Live</span>
              </Link>
            </div>

            <!-- Title & Subtitle -->
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight leading-tight">
              {{ event.title }}
            </h1>

            <p v-if="event.subtitle" class="text-base sm:text-lg text-teal-300/90 light:text-teal-700 font-medium leading-relaxed">
              {{ event.subtitle }}
            </p>

            <!-- Rich Content or Description -->
            <div
              v-if="event.content_html"
              class="prose-hartono text-sm sm:text-base font-light leading-relaxed pt-2"
              v-html="event.content_html"
            ></div>
            <p v-else class="text-sm sm:text-base text-slate-300 light:text-slate-600 font-light leading-relaxed whitespace-pre-line">
              {{ event.description }}
            </p>

            <!-- Quick Metadata Badges -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
              <div class="glass-card rounded-2xl p-4 border border-slate-800 light:border-slate-200">
                <div class="flex items-center gap-2 text-teal-400 light:text-teal-600 text-xs font-semibold uppercase mb-1">
                  <Calendar class="w-4 h-4" />
                  <span>Date</span>
                </div>
                <div class="text-sm font-bold text-white light:text-slate-900">{{ formattedDate }}</div>
              </div>

              <div class="glass-card rounded-2xl p-4 border border-slate-800 light:border-slate-200">
                <div class="flex items-center gap-2 text-teal-400 light:text-teal-600 text-xs font-semibold uppercase mb-1">
                  <Clock class="w-4 h-4" />
                  <span>Time</span>
                </div>
                <div class="text-sm font-bold text-white light:text-slate-900">{{ event.start_time }} {{ event.timezone }}</div>
              </div>

              <div class="glass-card rounded-2xl p-4 border border-slate-800 light:border-slate-200">
                <div class="flex items-center gap-2 text-teal-400 light:text-teal-600 text-xs font-semibold uppercase mb-1">
                  <MapPin class="w-4 h-4" />
                  <span>Venue</span>
                </div>
                <div class="text-sm font-bold text-white light:text-slate-900 line-clamp-1">{{ event.venue_name }}</div>
              </div>
            </div>
          </div>

          <!-- Right Column: Live Countdown & RSVP Card -->
          <div class="lg:col-span-4">
            <div class="glass-card rounded-3xl p-6 sm:p-7 border border-teal-500/30 space-y-6 shadow-2xl relative">
              <div v-if="event.is_date_tba" class="p-5 rounded-2xl bg-slate-900/80 light:bg-slate-100 border border-cyan-500/40 text-center space-y-2 shadow-inner">
                <span class="text-[0.65rem] font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700 block">
                  Schedule Status
                </span>
                <div class="text-sm sm:text-base font-black font-heading text-white light:text-slate-900 tracking-wide">
                  TO BE ANNOUNCED SHORTLY
                </div>
                <p class="text-[0.7rem] text-slate-400 light:text-slate-600 leading-relaxed">
                  Jadwal baru sedang diselaraskan. Pantau portal acara ini untuk pengumuman tanggal terbaru.
                </p>
              </div>
              <div v-else class="text-center">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 light:text-slate-500 block mb-3">
                  Event Countdown
                </span>
                <CountdownTimer :target-date="event.date" :target-time="event.start_time" />
              </div>

              <!-- Capacity & Status -->
              <div class="border-t border-slate-800/80 light:border-slate-200 pt-5 space-y-3 text-xs">
                <div class="flex items-center justify-between text-slate-300 light:text-slate-700">
                  <span>Guest Capacity</span>
                  <span class="font-bold text-white light:text-slate-900">{{ event.confirmed_registrations_count }} / {{ event.max_capacity }}</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full h-2 rounded-full bg-slate-800 light:bg-slate-200 overflow-hidden">
                  <div
                    class="h-full bg-gradient-to-r from-teal-500 to-cyan-400 rounded-full transition-all duration-500"
                    :style="{ width: `${Math.min(100, (event.confirmed_registrations_count / event.max_capacity) * 100)}%` }"
                  ></div>
                </div>

                <div v-if="event.rsvp_phone" class="pt-2 text-slate-400 light:text-slate-500">
                  <strong>Direct RSVP:</strong> {{ event.rsvp_phone }}
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="pt-2 space-y-2.5">
                <Link
                  v-if="event.is_registration_enabled && !event.is_full"
                  :href="route('public.events.register', event.slug)"
                  class="w-full py-3.5 rounded-xl font-bold text-xs bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-lg shadow-teal-950/50 transition-all flex items-center justify-center gap-2"
                >
                  <Ticket class="w-4 h-4" />
                  <span>Register / RSVP Now</span>
                </Link>

                <div v-else class="text-center py-3 rounded-xl bg-slate-800 text-slate-400 font-semibold text-xs">
                  Registration Capacity Full / Closed
                </div>

                <Link
                  v-if="event.is_gallery_enabled"
                  :href="route('public.events.gallery', event.slug)"
                  class="w-full py-2.5 rounded-xl text-xs font-semibold text-slate-300 light:text-slate-700 hover:text-white glass-card hover:bg-slate-800 border border-slate-700/80 light:border-slate-300 transition-all flex items-center justify-center gap-1.5"
                >
                  <Camera class="w-3.5 h-3.5 text-teal-400" />
                  <span>Event Media Gallery</span>
                </Link>

                <Link
                  v-if="event.is_post_event_enabled"
                  :href="route('public.events.recap', event.slug)"
                  class="w-full py-2.5 rounded-xl text-xs font-semibold text-slate-300 light:text-slate-700 hover:text-white glass-card hover:bg-slate-800 border border-slate-700/80 light:border-slate-300 transition-all flex items-center justify-center gap-1.5"
                >
                  <Sparkles class="w-3.5 h-3.5 text-rose-400" />
                  <span>Post-Event Recap & Wishes Wall</span>
                </Link>

                <Link
                  v-if="event.is_guestbook_enabled"
                  :href="route('public.events.guestbook', event.slug)"
                  class="w-full py-2.5 rounded-xl text-xs font-semibold text-slate-300 light:text-slate-700 hover:text-white glass-card hover:bg-slate-800 border border-slate-700/80 light:border-slate-300 transition-all flex items-center justify-center gap-1.5"
                >
                  <MessageSquare class="w-3.5 h-3.5 text-cyan-400" />
                  <span>Sign Digital Guest Book</span>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Preview Section (if images present) -->
    <section v-if="galleryPreview && galleryPreview.length > 0" class="py-12 bg-slate-950/40 light:bg-slate-100/50 border-b border-slate-800/80 light:border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-600">Visual Highlights</span>
            <h2 class="text-2xl font-extrabold text-white light:text-slate-900 font-heading">
              Event Photo Showcase
            </h2>
          </div>
          <Link
            :href="route('public.events.gallery', event.slug)"
            class="text-xs font-semibold text-teal-400 light:text-teal-600 hover:underline flex items-center gap-1"
          >
            <span>Explore Full Gallery</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </Link>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <Link
            v-for="img in galleryPreview.slice(0, 4)"
            :key="img.id"
            :href="route('public.events.gallery', event.slug)"
            class="rounded-2xl overflow-hidden aspect-4/3 border border-slate-800 light:border-slate-200 bg-slate-950 group shadow-md"
          >
            <img
              :src="img.url"
              :alt="img.caption || event.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
          </Link>
        </div>
      </div>
    </section>

    <!-- Event Agenda & Schedule Section -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
          <!-- Left: Agenda Timeline -->
          <div class="lg:col-span-8 space-y-8">
            <div>
              <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-600">Schedule & Rundown</span>
              <h2 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
                Event Agenda Timeline
              </h2>
              <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 mt-1 font-light">
                Official rundown for the gathering and soft opening ceremony.
              </p>
            </div>

            <!-- Agenda Timeline Component -->
            <AgendaTimeline :agendas="event.agendas" />
          </div>

          <!-- Right: Location & Venue Details -->
          <div class="lg:col-span-4 space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-800/80 light:border-slate-200 space-y-4">
              <div class="flex items-center gap-2 text-teal-400 light:text-teal-600 text-xs font-bold uppercase tracking-wider">
                <MapPin class="w-4 h-4" />
                <span>Venue & Location</span>
              </div>

              <div>
                <h4 class="text-base font-bold text-white light:text-slate-900 font-heading">{{ event.venue_name }}</h4>
                <p class="text-xs text-slate-400 light:text-slate-600 mt-1 font-light leading-relaxed">
                  {{ event.venue_address || 'Jakarta Branch Dealership & Auto Studio' }}
                </p>
              </div>

              <div v-if="event.venue_map_url" class="pt-2">
                <a
                  :href="event.venue_map_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center gap-2 text-xs font-semibold text-teal-400 light:text-teal-600 hover:underline"
                >
                  <ExternalLink class="w-3.5 h-3.5" />
                  <span>Open in Google Maps</span>
                </a>
              </div>
            </div>

            <!-- RSVP Assistance Box -->
            <div class="glass-card rounded-3xl p-6 border border-slate-800/80 light:border-slate-200 space-y-3">
              <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 light:text-slate-500">Event Assistance</h4>
              <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed font-light">
                Need VIP seating arrangements or special accessibility support? Contact the HRM Event Desk:
              </p>
              <div class="p-3 rounded-xl bg-slate-900/80 light:bg-slate-100 border border-slate-800 light:border-slate-200 text-xs text-teal-300 light:text-teal-700 font-mono flex items-center justify-between">
                <span>📱 0877-8222-3724</span>
                <span class="text-[0.65rem] text-slate-400 uppercase">WhatsApp</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import CountdownTimer from '../../../Components/CountdownTimer.vue';
import AgendaTimeline from '../../../Components/AgendaTimeline.vue';
import { formatDate } from '../../../Utils/date';
import {
  Calendar,
  Clock,
  MapPin,
  Ticket,
  MessageSquare,
  ExternalLink,
  Camera,
  Sparkles,
  ArrowRight,
  CalendarClock
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  galleryPreview: {
    type: Array,
    default: () => [],
  },
  highlightedWishes: {
    type: Array,
    default: () => [],
  },
});

const formattedDate = computed(() => {
  if (!props.event?.date) return '';
  const dateObj = new Date(props.event.date);
  return dateObj.toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
});
</script>
