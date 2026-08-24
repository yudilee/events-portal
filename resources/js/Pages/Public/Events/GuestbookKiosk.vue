<template>
  <div class="min-h-screen bg-[#070B0E] light:bg-[#F8FAFC] text-white light:text-slate-900 flex flex-col justify-between select-none relative overflow-x-hidden font-sans">
    <Head :title="`Reception Guestbook Kiosk — ${event.title}`" />

    <!-- Ambient background luxury glow -->
    <div class="fixed top-0 left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Top Kiosk Header Bar -->
    <header class="p-4 sm:p-6 border-b border-slate-800/80 light:border-slate-200 bg-slate-950/70 light:bg-white/80 backdrop-blur-xl flex items-center justify-between z-20 shrink-0">
      <!-- Left: Logo & Event Info -->
      <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-2xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 font-bold shrink-0">
          <BookOpen class="w-5 h-5" />
        </div>
        <div>
          <div class="flex items-center gap-2">
            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-teal-400 light:text-teal-700 bg-teal-950/80 light:bg-teal-100 px-2 py-0.5 rounded-full border border-teal-800/40">
              Reception Tablet Desk
            </span>
            <span v-if="event.business_unit" class="text-[0.65rem] text-slate-400 light:text-slate-600 font-medium">
              {{ event.business_unit.name }}
            </span>
          </div>
          <h1 class="text-sm sm:text-base font-extrabold text-white light:text-slate-900 font-heading leading-tight line-clamp-1 mt-0.5">
            {{ event.title }}
          </h1>
        </div>
      </div>

      <!-- Right: Kiosk Tools (Fullscreen, QR Standee Mode, Theme) -->
      <div class="flex items-center gap-2">
        <Link
          :href="route('public.events.guestbook.qr', event.slug)"
          class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-900 light:bg-slate-100 hover:bg-slate-800 text-cyan-400 light:text-cyan-700 border border-slate-700 light:border-slate-300 flex items-center gap-1.5 transition-colors shadow-sm"
          title="Switch to QR Code Standee Display"
        >
          <QrCode class="w-4 h-4" />
          <span class="hidden sm:inline">QR Standee Display</span>
        </Link>

        <Link
          :href="route('public.events.guestbook', event.slug)"
          class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-900 light:bg-slate-100 hover:bg-slate-800 text-slate-300 light:text-slate-700 border border-slate-700 light:border-slate-300 flex items-center gap-1.5 transition-colors shadow-sm"
          title="View Public Wall"
        >
          <Sparkles class="w-4 h-4 text-amber-400" />
          <span class="hidden sm:inline">Live Wall</span>
        </Link>

        <button
          @click="toggleFullscreen"
          type="button"
          class="p-2 rounded-xl bg-slate-900 light:bg-slate-100 hover:bg-slate-800 text-slate-300 light:text-slate-700 border border-slate-700 light:border-slate-300 transition-colors"
          title="Toggle Fullscreen Kiosk Mode"
        >
          <Minimize v-if="isFullscreen" class="w-4 h-4" />
          <Maximize v-else class="w-4 h-4" />
        </button>
      </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 z-10">
      <!-- State 1: Active Signing Form -->
      <div
        v-if="!submittedSuccessfully"
        class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch"
      >
        <!-- Left Side: Guest Form Card (8 Cols) -->
        <div class="lg:col-span-7 glass-card rounded-3xl p-6 sm:p-8 border border-slate-800/90 light:border-slate-200 bg-slate-900/80 light:bg-white shadow-2xl flex flex-col justify-between">
          <div>
            <div class="mb-6 space-y-1">
              <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Digital Attendance</span>
              <h2 class="text-2xl sm:text-3xl font-black text-white light:text-slate-900 font-heading">
                Sign Digital Guest Book
              </h2>
              <p class="text-xs text-slate-400 light:text-slate-600">
                Please enter your details and greeting to check-in and enter the stage doorprize draw.
              </p>
            </div>

            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Full Name (Large input for touch typing) -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1.5">
                  Full Name / Nama Lengkap <span class="text-rose-400">*</span>
                </label>
                <div class="relative">
                  <User class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                  <input
                    v-model="form.guest_name"
                    type="text"
                    required
                    placeholder="e.g. Ir. Hartono / Budi Santoso"
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-950/80 light:bg-slate-50 border border-slate-700 light:border-slate-300 text-sm font-semibold text-white light:text-slate-900 placeholder-slate-500 focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-500/20 transition-all"
                  />
                </div>
              </div>

              <!-- Company & Registration Code Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1.5">
                    Company / Organization
                  </label>
                  <div class="relative">
                    <Building2 class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                    <input
                      v-model="form.company"
                      type="text"
                      placeholder="e.g. PT Mitra Logistik / Guest"
                      class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-950/80 light:bg-slate-50 border border-slate-700 light:border-slate-300 text-xs font-medium text-white light:text-slate-900 placeholder-slate-500 focus:outline-none focus:border-teal-400"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1.5">
                    Pass Code (Optional)
                  </label>
                  <div class="relative">
                    <QrCode class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                    <input
                      v-model="form.registration_code"
                      type="text"
                      placeholder="e.g. HRM-VIP-2026 (Optional)"
                      class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-950/80 light:bg-slate-50 border border-slate-700 light:border-slate-300 text-xs font-mono text-teal-400 light:text-teal-700 placeholder-slate-500 focus:outline-none focus:border-teal-400 uppercase"
                    />
                  </div>
                </div>
              </div>

              <!-- Message / Wishes -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1.5">
                  Wishes & Greetings / Pesan Ucapan <span class="text-rose-400">*</span>
                </label>
                <div class="relative">
                  <textarea
                    v-model="form.message"
                    required
                    rows="3"
                    placeholder="Tulis ucapan selamat atau harapan Anda..."
                    class="w-full p-4 rounded-2xl bg-slate-950/80 light:bg-slate-50 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 placeholder-slate-500 focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-500/20 leading-relaxed"
                  ></textarea>
                </div>

                <!-- Quick Greeting Suggestion Chips -->
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <button
                    v-for="chip in quickChips"
                    :key="chip"
                    type="button"
                    @click="applyChip(chip)"
                    class="px-2.5 py-1 rounded-lg text-[0.7rem] font-medium bg-slate-800/80 light:bg-slate-100 hover:bg-slate-700 light:hover:bg-slate-200 text-slate-300 light:text-slate-700 border border-slate-700/60 light:border-slate-300 transition-colors"
                  >
                    {{ chip }}
                  </button>
                </div>
              </div>

              <!-- Submit Button (Giant Touch-Friendly CTA) -->
              <div class="pt-2">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="w-full py-4 rounded-2xl font-black text-sm bg-gradient-to-r from-teal-500 via-cyan-400 to-teal-500 hover:from-teal-400 hover:to-cyan-300 text-slate-950 shadow-xl shadow-teal-950/70 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                >
                  <Send class="w-4 h-4" />
                  <span>{{ form.processing ? 'Signing Guestbook...' : 'Submit & Sign Guestbook' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Side: Event Stats & QR Standee Helper (5 Cols) -->
        <div class="lg:col-span-5 flex flex-col justify-between gap-6">
          <!-- Live Signed Stats Card -->
          <div class="glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Live Attendance</span>
              <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
              </span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-950/80 light:bg-slate-50 border border-slate-800 light:border-slate-200 text-center">
              <span class="text-3xl sm:text-4xl font-black text-white light:text-slate-900 font-heading">
                {{ totalSignedCount }}
              </span>
              <span class="block text-xs font-bold text-teal-400 light:text-teal-700 uppercase tracking-wider mt-0.5">
                Guests Signed In
              </span>
            </div>

            <!-- Recent Attendees Mini Feed -->
            <div class="space-y-2 pt-2">
              <div class="text-[0.7rem] font-bold uppercase tracking-wider text-slate-400 light:text-slate-600">
                Recent Guest Signatures:
              </div>
              <div v-if="localRecentEntries.length > 0" class="space-y-2 max-h-44 overflow-y-auto pr-1">
                <div
                  v-for="entry in localRecentEntries.slice(0, 4)"
                  :key="entry.id"
                  class="p-2.5 rounded-xl bg-slate-950/40 light:bg-slate-100 border border-slate-800/60 light:border-slate-200 text-xs"
                >
                  <div class="font-bold text-white light:text-slate-900 flex items-center justify-between">
                    <span>{{ entry.guest_name }}</span>
                    <span class="text-[0.65rem] text-teal-400 font-normal">✓ Checked In</span>
                  </div>
                  <p class="text-[0.7rem] text-slate-400 light:text-slate-600 italic line-clamp-1 mt-0.5">
                    "{{ entry.message }}"
                  </p>
                </div>
              </div>
              <div v-else class="text-xs text-slate-500 py-3 text-center italic">
                Be the first guest to sign!
              </div>
            </div>
          </div>

          <!-- Smartphone Scan Helper Card -->
          <div class="glass-card rounded-3xl p-5 border border-teal-500/30 light:border-teal-300 bg-gradient-to-br from-teal-950/30 via-slate-900/60 to-cyan-950/30 light:from-teal-50 light:to-white shadow-xl flex items-center gap-4">
            <div class="p-2.5 bg-white rounded-2xl shadow-md border border-slate-200 shrink-0">
              <img
                :src="`https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${encodeURIComponent(qrUrl)}`"
                alt="QR Code"
                class="w-16 h-16 object-contain"
              />
            </div>
            <div class="space-y-1 min-w-0 flex-1">
              <h4 class="text-xs font-black text-white light:text-slate-900 font-heading uppercase tracking-wider">
                Prefer using your own phone?
              </h4>
              <p class="text-[0.72rem] text-slate-300 light:text-slate-600 leading-snug">
                Scan this QR code with your smartphone camera to sign on your personal device.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- State 2: Celebratory Welcome Screen (Auto-Resets for next guest) -->
      <div
        v-else
        class="w-full max-w-xl glass-card rounded-3xl p-8 sm:p-12 border border-teal-500/50 light:border-teal-300 bg-slate-900/90 light:bg-white shadow-2xl text-center space-y-6 animate-in fade-in zoom-in-95 duration-300"
      >
        <!-- Animated Success Icon -->
        <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-teal-500 to-cyan-400 text-slate-950 flex items-center justify-center mx-auto shadow-xl shadow-teal-500/30 animate-bounce">
          <CheckCircle2 class="w-10 h-10" />
        </div>

        <div class="space-y-2">
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Guestbook Signed Successfully</span>
          <h2 class="text-3xl sm:text-4xl font-black text-white light:text-slate-900 font-heading">
            Thank You, {{ lastSubmittedName }}!
          </h2>
          <p class="text-sm text-slate-300 light:text-slate-600 max-w-md mx-auto leading-relaxed">
            Welcome to <strong class="text-white light:text-slate-900">{{ event.title }}</strong>. Your attendance and greeting have been recorded and entered into the live stage doorprize draw.
          </p>
        </div>

        <!-- Auto Reset Progress Bar & Countdown -->
        <div class="p-4 rounded-2xl bg-slate-950/80 light:bg-slate-100 border border-slate-800 light:border-slate-200 space-y-2">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-600">
            <span>Resetting for next attendee...</span>
            <span class="font-bold font-mono text-teal-400 light:text-teal-700">{{ countdownSeconds }}s</span>
          </div>
          <div class="w-full h-2 rounded-full bg-slate-800 light:bg-slate-300 overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-teal-400 to-cyan-400 transition-all duration-1000 ease-linear"
              :style="{ width: `${(countdownSeconds / 6) * 100}%` }"
            ></div>
          </div>
        </div>

        <!-- Immediate Next Guest Button -->
        <button
          @click="resetForNextGuest"
          type="button"
          class="w-full py-3.5 rounded-2xl font-bold text-xs bg-slate-800 light:bg-slate-200 hover:bg-slate-700 light:hover:bg-slate-300 text-slate-200 light:text-slate-800 border border-slate-700 light:border-slate-300 transition-all flex items-center justify-center gap-2"
        >
          <UserPlus class="w-4 h-4 text-teal-400 light:text-teal-700" />
          <span>Sign for Next Guest (Click to Skip Timer)</span>
        </button>
      </div>
    </main>

    <!-- Bottom Kiosk Footer Bar -->
    <footer class="p-4 border-t border-slate-800/60 light:border-slate-200 bg-slate-950/50 light:bg-slate-100/50 text-[0.7rem] text-slate-400 light:text-slate-600 flex flex-col sm:flex-row items-center justify-between gap-2 shrink-0 z-20">
      <div class="flex items-center gap-2 font-medium">
        <span>📍 {{ event.venue_name }}</span>
        <span>•</span>
        <span>📅 {{ formatDate(event.date) }}</span>
      </div>
      <div class="font-mono opacity-75">
        Hartono Group Official Event Kiosk
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import confetti from 'canvas-confetti';
import { formatDate } from '../../../Utils/date';
import {
  BookOpen,
  User,
  Building2,
  QrCode,
  Send,
  Sparkles,
  Maximize,
  Minimize,
  CheckCircle2,
  UserPlus
} from 'lucide-vue-next';

const props = defineProps({
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
  qrUrl: {
    type: String,
    required: true,
  },
});

const isFullscreen = ref(false);
const submittedSuccessfully = ref(false);
const lastSubmittedName = ref('');
const countdownSeconds = ref(6);
const totalSignedCount = ref(props.totalSigned);
const localRecentEntries = ref([...props.recentEntries]);

let countdownTimer = null;

const quickChips = [
  'Selamat & Sukses! 🎉',
  'Congratulations Hartono Group! 🏢',
  'Sukses selalu & jaya terus! 🌟',
  'Honored to be here! ✨',
];

const form = useForm({
  guest_name: '',
  company: '',
  registration_code: '',
  message: '',
});

const applyChip = (chip) => {
  if (!form.message) {
    form.message = chip;
  } else {
    form.message += ` ${chip}`;
  }
};

const triggerConfetti = () => {
  try {
    confetti({
      particleCount: 80,
      spread: 70,
      origin: { y: 0.6 },
      colors: ['#2dd4bf', '#22d3ee', '#38bdf8', '#f59e0b'],
    });
  } catch (e) {
    // Ignore if not supported
  }
};

const submitForm = () => {
  const submittedName = form.guest_name;
  const submittedCompany = form.company;
  const submittedMsg = form.message;

  form.post(route('public.events.guestbook.store', props.event.slug), {
    preserveScroll: true,
    onSuccess: () => {
      lastSubmittedName.value = submittedName;
      submittedSuccessfully.value = true;
      totalSignedCount.value += 1;
      localRecentEntries.value.unshift({
        id: Date.now(),
        guest_name: submittedName,
        company: submittedCompany,
        message: submittedMsg,
        created_at: new Date().toISOString(),
      });

      triggerConfetti();

      // Start 6 second auto-reset timer
      countdownSeconds.value = 6;
      clearInterval(countdownTimer);
      countdownTimer = setInterval(() => {
        countdownSeconds.value -= 1;
        if (countdownSeconds.value <= 0) {
          resetForNextGuest();
        }
      }, 1000);
    },
  });
};

const resetForNextGuest = () => {
  clearInterval(countdownTimer);
  form.reset();
  submittedSuccessfully.value = false;
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
  clearInterval(countdownTimer);
  document.removeEventListener('fullscreenchange', handleFullscreenChange);
});
</script>
