<template>
  <div class="min-h-screen bg-[#070B0E] light:bg-[#F8FAFC] text-white light:text-slate-900 flex flex-col justify-between select-none relative overflow-x-hidden font-sans print:bg-white print:text-black">
    <Head :title="`Digital Guestbook QR Standee — ${event.title}`" />

    <!-- Ambient background glows (hidden during print) -->
    <div class="fixed top-1/4 left-1/3 w-[30rem] h-[30rem] bg-teal-500/10 rounded-full blur-3xl pointer-events-none print:hidden"></div>
    <div class="fixed bottom-1/4 right-1/3 w-[30rem] h-[30rem] bg-cyan-500/10 rounded-full blur-3xl pointer-events-none print:hidden"></div>

    <!-- Top Standee Navigation Bar (hidden during print) -->
    <header class="p-4 sm:p-6 border-b border-slate-800/80 light:border-slate-200 bg-slate-950/80 light:bg-white/80 backdrop-blur-xl flex items-center justify-between z-20 shrink-0 print:hidden">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-teal-500 to-cyan-400 text-slate-950 flex items-center justify-center font-bold shadow-md">
          <QrCode class="w-5 h-5" />
        </div>
        <div>
          <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-teal-400 light:text-teal-700 bg-teal-950/80 light:bg-teal-100 px-2 py-0.5 rounded-full border border-teal-800/40">
            Reception QR Standee
          </span>
          <h1 class="text-sm sm:text-base font-extrabold text-white light:text-slate-900 font-heading leading-tight line-clamp-1 mt-0.5">
            {{ event.title }}
          </h1>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button
          @click="windowPrint"
          type="button"
          class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 light:bg-slate-100 hover:bg-slate-800 text-slate-200 light:text-slate-800 border border-slate-700 light:border-slate-300 flex items-center gap-1.5 transition-colors shadow-sm"
          title="Print Acrylic QR Standee"
        >
          <Printer class="w-4 h-4 text-teal-400" />
          <span class="hidden sm:inline">Print Standee</span>
        </button>

        <Link
          :href="route('public.events.guestbook.kiosk', event.slug)"
          class="px-3.5 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-teal-950/50"
          title="Open Tablet Touch Kiosk Mode"
        >
          <Tablet class="w-4 h-4" />
          <span class="hidden sm:inline">Tablet Touch Mode</span>
        </Link>

        <button
          @click="toggleFullscreen"
          type="button"
          class="p-2 rounded-xl bg-slate-900 light:bg-slate-100 hover:bg-slate-800 text-slate-300 light:text-slate-700 border border-slate-700 light:border-slate-300 transition-colors"
          title="Toggle Fullscreen"
        >
          <Minimize v-if="isFullscreen" class="w-4 h-4" />
          <Maximize v-else class="w-4 h-4" />
        </button>
      </div>
    </header>

    <!-- Main Standee Card -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8 lg:p-12 z-10 print:p-0">
      <div class="w-full max-w-4xl glass-card rounded-3xl overflow-hidden border-2 border-teal-500/40 light:border-slate-300 bg-slate-950/90 light:bg-white shadow-2xl print:border-2 print:border-black print:shadow-none print:bg-white">
        <!-- Top Metallic Header with Logo & Event Details -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-teal-950 via-slate-900 to-cyan-950 border-b border-teal-500/30 text-center relative overflow-hidden print:bg-none print:border-b-2 print:border-black">
          <div class="relative z-10 space-y-2">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest bg-teal-500/20 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-500/40 print:border-black print:text-black">
              ★ OFFICIAL EVENT GUESTBOOK & CHECK-IN
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-white print:text-black font-heading leading-tight max-w-2xl mx-auto">
              {{ event.title }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 print:text-slate-700 font-medium flex items-center justify-center gap-2 flex-wrap">
              <span>📍 {{ event.venue_name }}</span>
              <span>•</span>
              <span>📅 {{ formatDate(event.date) }}</span>
            </p>
          </div>
        </div>

        <!-- Standee Body Grid -->
        <div class="p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-slate-950/80 light:bg-white print:bg-white">
          <!-- Left: High-Contrast Giant QR Code Box (7 Cols) -->
          <div class="lg:col-span-7 flex flex-col items-center justify-center space-y-4">
            <div class="relative p-5 sm:p-6 bg-white rounded-3xl shadow-2xl border-4 border-teal-500/40 print:border-black flex items-center justify-center group">
              <!-- Corner Decorative Accents -->
              <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-teal-500 print:hidden"></div>
              <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-teal-500 print:hidden"></div>
              <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-teal-500 print:hidden"></div>
              <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-teal-500 print:hidden"></div>

              <!-- High-Resolution QR Code Image -->
              <img
                :src="`https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=12&data=${encodeURIComponent(targetUrl)}`"
                :alt="`QR Code ${event.title}`"
                crossorigin="anonymous"
                class="w-56 h-56 sm:w-72 sm:h-72 object-contain"
              />
            </div>

            <!-- Radar / Scan Prompt Badge -->
            <div class="text-center space-y-1">
              <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-500/10 light:bg-teal-100 text-teal-400 light:text-teal-800 border border-teal-500/30 text-xs font-bold uppercase tracking-wider">
                <Smartphone class="w-4 h-4" />
                <span>Scan with Smartphone Camera</span>
              </div>
              <p class="text-[0.7rem] text-slate-400 light:text-slate-600 print:text-black">
                No app installation required • Instant mobile web form
              </p>
            </div>
          </div>

          <!-- Right: 3 Easy Instructions & Live Ticker (5 Cols) -->
          <div class="lg:col-span-5 space-y-6">
            <!-- 3 Simple Steps -->
            <div class="space-y-3.5">
              <h3 class="text-xs font-extrabold uppercase tracking-widest text-teal-400 light:text-teal-700 print:text-black">
                How to Sign in 3 Easy Steps:
              </h3>

              <!-- Step 1 -->
              <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-900/60 light:bg-slate-100 border border-slate-800/80 light:border-slate-200 print:border-black">
                <div class="w-8 h-8 rounded-xl bg-teal-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0">
                  1
                </div>
                <div>
                  <h4 class="text-xs font-bold text-white light:text-slate-900 print:text-black">Open Camera</h4>
                  <p class="text-[0.7rem] text-slate-400 light:text-slate-600 print:text-black">
                    Point your smartphone camera at the QR code on screen.
                  </p>
                </div>
              </div>

              <!-- Step 2 -->
              <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-900/60 light:bg-slate-100 border border-slate-800/80 light:border-slate-200 print:border-black">
                <div class="w-8 h-8 rounded-xl bg-cyan-400 text-slate-950 font-black text-sm flex items-center justify-center shrink-0">
                  2
                </div>
                <div>
                  <h4 class="text-xs font-bold text-white light:text-slate-900 print:text-black">Enter Your Name & Message</h4>
                  <p class="text-[0.7rem] text-slate-400 light:text-slate-600 print:text-black">
                    Fill in your name and congratulations/wishes for the event.
                  </p>
                </div>
              </div>

              <!-- Step 3 -->
              <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-900/60 light:bg-slate-100 border border-slate-800/80 light:border-slate-200 print:border-black">
                <div class="w-8 h-8 rounded-xl bg-amber-400 text-slate-950 font-black text-sm flex items-center justify-center shrink-0">
                  3
                </div>
                <div>
                  <h4 class="text-xs font-bold text-white light:text-slate-900 print:text-black">Enter Doorprize Draw</h4>
                  <p class="text-[0.7rem] text-slate-400 light:text-slate-600 print:text-black">
                    Your attendance is verified and entered into the live stage prize draw.
                  </p>
                </div>
              </div>
            </div>

            <!-- Live Signed Badge (hidden in print) -->
            <div class="p-4 rounded-2xl bg-gradient-to-r from-teal-950/40 to-slate-900/80 light:bg-slate-100 border border-teal-500/20 light:border-slate-200 flex items-center justify-between print:hidden">
              <div class="flex items-center gap-2">
                <span class="flex h-2.5 w-2.5 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                </span>
                <span class="text-xs text-slate-300 light:text-slate-700 font-semibold">Attended Guests</span>
              </div>
              <span class="font-mono font-black text-teal-300 light:text-teal-700 text-sm">
                {{ totalSigned }} Signed
              </span>
            </div>
          </div>
        </div>

        <!-- Standee Footer -->
        <div class="p-4 bg-slate-950 border-t border-slate-800/80 light:border-slate-200 light:bg-slate-50 flex items-center justify-between text-[0.68rem] text-slate-400 light:text-slate-600 print:bg-white print:text-black">
          <div class="flex items-center gap-2 font-medium">
            <span>Official Hartono Raya Motor & Hartono Group Reception</span>
          </div>
          <span class="font-mono">{{ event.slug }}</span>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { formatDate } from '../../../Utils/date';
import {
  QrCode,
  Smartphone,
  Tablet,
  Printer,
  Maximize,
  Minimize
} from 'lucide-vue-next';

defineProps({
  event: {
    type: Object,
    required: true,
  },
  recentEntries: {
    type: Array,
    default: () => [],
  },
  totalSigned: {
    type: Number,
    default: 0,
  },
  targetUrl: {
    type: String,
    required: true,
  },
});

const isFullscreen = ref(false);

const windowPrint = () => {
  window.print();
};

const toggleFullscreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(() => {
      isFullscreen.value = true;
    }).catch(() => {});
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen().then(() => {
        isFullscreen.value = false;
      }).catch(() => {});
    }
  }
};

const handleFullscreenChange = () => {
  isFullscreen.value = !!document.fullscreenElement;
};

onMounted(() => {
  document.addEventListener('fullscreenchange', handleFullscreenChange);
});

onUnmounted(() => {
  document.removeEventListener('fullscreenchange', handleFullscreenChange);
});
</script>
