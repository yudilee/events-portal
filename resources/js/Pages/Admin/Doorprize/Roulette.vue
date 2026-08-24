<template>
  <div class="min-h-screen bg-[#04070A] text-slate-100 font-sans selection:bg-amber-400 selection:text-black flex flex-col justify-between relative overflow-hidden">
    <Head :title="`Live Doorprize Roulette — ${event.title}`" />

    <!-- Ambient Glowing Stage Backdrop -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[550px] bg-gradient-to-b from-teal-500/15 via-cyan-500/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 left-1/2 -translate-x-1/2 w-[1000px] h-[450px] bg-gradient-to-t from-amber-500/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <!-- Top Stage Header Bar -->
    <header class="p-4 sm:p-6 flex items-center justify-between z-20 relative border-b border-slate-800/60 bg-[#060B10]/80 backdrop-blur-md">
      <div class="flex items-center gap-4">
        <Link
          :href="route('admin.events.doorprize', event.id)"
          class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors shadow-md"
          title="Back to Prize Setup"
        >
          <ArrowLeft class="w-5 h-5" />
        </Link>

        <div>
          <div class="flex items-center gap-2">
            <span class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-amber-400 bg-amber-950/80 border border-amber-500/40 px-2.5 py-0.5 rounded-full shadow-sm">
              Live Stage Draw
            </span>
            <span class="text-xs text-slate-400 font-mono">
              Pool: {{ remainingPoolCount }} Eligible Signers
            </span>
          </div>
          <h1 class="text-base sm:text-lg font-black text-white font-heading tracking-wide mt-0.5">
            {{ event.title }}
          </h1>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Mode Switcher: Wheel vs Reel -->
        <div class="hidden sm:flex items-center p-1 bg-slate-900 border border-slate-800 rounded-xl text-xs font-bold">
          <button
            @click="viewMode = 'wheel'"
            :class="[
              'px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5',
              viewMode === 'wheel' ? 'bg-amber-400 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white'
            ]"
          >
            <Disc3 class="w-3.5 h-3.5" />
            <span>Roulette Wheel</span>
          </button>
          <button
            @click="viewMode = 'reel'"
            :class="[
              'px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5',
              viewMode === 'reel' ? 'bg-amber-400 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white'
            ]"
          >
            <SlidersHorizontal class="w-3.5 h-3.5" />
            <span>Slot Reel</span>
          </button>
        </div>

        <!-- Winners History Drawer Toggle -->
        <button
          @click="showWinnersDrawer = !showWinnersDrawer"
          class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 border border-slate-800 text-slate-200 hover:text-amber-400 hover:border-amber-500/40 transition-all flex items-center gap-2 shadow-md"
        >
          <Trophy class="w-4 h-4 text-amber-400" />
          <span>Winners ({{ totalAwardedCount }}/{{ totalPrizesQuota }})</span>
        </button>

        <!-- Fullscreen Toggle -->
        <button
          @click="toggleFullscreen"
          class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors shadow-md"
          :title="isFullscreen ? 'Exit Fullscreen' : 'Enter Fullscreen (F11)'"
        >
          <Minimize v-if="isFullscreen" class="w-5 h-5" />
          <Maximize v-else class="w-5 h-5" />
        </button>
      </div>
    </header>

    <!-- Center Stage Area -->
    <main class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6 z-10 relative max-w-6xl mx-auto w-full">
      <!-- Target Prize Spotlight Pill -->
      <div v-if="activePrize" class="mb-4 text-center space-y-1 animate-fade-in">
        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-gradient-to-r from-amber-500/20 via-teal-500/20 to-cyan-500/20 border border-amber-400/40 text-amber-300 text-xs font-extrabold tracking-wider uppercase shadow-lg shadow-amber-950/40">
          <Sparkles class="w-4 h-4 text-amber-400 animate-spin" />
          <span>
            Drawing For: {{ activePrize.label }}
            <template v-if="(activePrize.quantity || 1) > 1">
              (Slot {{ currentPrizeWinnerIndex }} of {{ activePrize.quantity }})
            </template>
          </span>
        </div>
        <h2 class="text-xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-slate-300 font-heading">
          {{ activePrize.description || activePrize.label }}
        </h2>
      </div>

      <div v-else class="mb-4 text-center space-y-1">
        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-teal-950/80 border border-teal-500/40 text-teal-300 text-xs font-bold uppercase tracking-wider">
          <CheckCircle2 class="w-4 h-4 text-teal-400" />
          <span>All Doorprizes Have Been Awarded!</span>
        </div>
        <h2 class="text-xl sm:text-2xl font-bold text-white font-heading">
          Congratulations to All Winners
        </h2>
      </div>

      <!-- VIEW MODE 1: Interactive Circular Canvas Roulette Wheel -->
      <div v-if="viewMode === 'wheel'" class="relative flex flex-col items-center justify-center">
        <!-- Golden Top Pointer Needle Indicator -->
        <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-30 flex flex-col items-center pointer-events-none drop-shadow-[0_0_15px_rgba(251,191,36,0.9)]">
          <div class="w-8 h-8 bg-amber-400 rotate-45 rounded-sm border-2 border-white shadow-xl"></div>
          <div class="w-2 h-4 bg-amber-500 rounded-b -mt-2"></div>
        </div>

        <!-- Outer Glowing Ring -->
        <div class="p-3.5 sm:p-5 rounded-full bg-gradient-to-b from-amber-400/30 via-slate-900 to-slate-950 border-4 border-amber-400/40 shadow-[0_0_80px_rgba(251,191,36,0.25)] relative">
          <!-- Canvas Wheel -->
          <canvas
            ref="wheelCanvas"
            width="480"
            height="480"
            class="rounded-full max-w-[320px] max-h-[320px] sm:max-w-[440px] sm:max-h-[440px] transition-transform shadow-2xl"
          ></canvas>

          <!-- Golden Hub Center Piece -->
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-b from-amber-300 via-amber-500 to-amber-700 border-4 border-slate-900 shadow-[0_0_25px_rgba(251,191,36,0.8)] flex flex-col items-center justify-center text-center pointer-events-none z-20">
            <span class="text-[0.65rem] sm:text-xs font-black uppercase text-slate-950 tracking-tighter leading-none">HARTONO</span>
            <span class="text-[0.55rem] sm:text-[0.65rem] font-bold text-slate-900 uppercase tracking-widest mt-0.5">ROULETTE</span>
          </div>
        </div>
      </div>

      <!-- VIEW MODE 2: Slot Machine Digital Drum Reel -->
      <div v-else class="w-full glass-card rounded-[2.5rem] p-8 sm:p-12 border-2 border-teal-500/30 bg-gradient-to-b from-[#0B1520]/90 via-[#070D14]/95 to-[#04070A] shadow-[0_0_80px_rgba(13,148,136,0.2)] relative overflow-hidden text-center flex flex-col items-center justify-center min-h-[280px]">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-teal-400 to-transparent"></div>

        <div v-if="isSpinning" class="space-y-3 animate-pulse">
          <div class="text-xs font-mono font-bold tracking-[0.3em] uppercase text-cyan-400">
            🎲 SPINNING LIVE GUESTBOOK DRAW...
          </div>
          <div class="text-3xl sm:text-6xl font-black text-white font-heading tracking-tight">
            {{ currentRollingName }}
          </div>
          <div class="text-sm sm:text-xl font-medium text-teal-400 font-heading">
            {{ currentRollingCompany || 'Event Guest' }}
          </div>
        </div>

        <div v-else-if="latestWinner" class="space-y-3">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-extrabold uppercase tracking-widest">
            <Trophy class="w-4 h-4" />
            <span>WINNER SELECTED</span>
          </div>
          <div class="text-3xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-white to-amber-300 font-heading">
            {{ latestWinner.guest_name }}
          </div>
          <div class="text-base sm:text-xl font-bold text-teal-300 font-heading">
            {{ latestWinner.company || 'Distinguished Guest' }}
          </div>
        </div>

        <div v-else class="space-y-3">
          <div class="w-14 h-14 rounded-2xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 mx-auto">
            <Sparkles class="w-7 h-7" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
            {{ activePrize ? 'Ready to Draw Winner' : 'Draw Concluded' }}
          </div>
          <p class="text-xs text-slate-400 max-w-md mx-auto">
            Click SPIN ROULETTE or press SPACEBAR to begin the live draw.
          </p>
        </div>
      </div>

      <!-- Winner Announcement Spotlight Card (Appears under Wheel after Stop) -->
      <div
        v-if="latestWinner && !isSpinning"
        class="mt-5 w-full max-w-xl glass-card rounded-3xl p-5 sm:p-6 border-2 border-amber-400/60 bg-gradient-to-b from-amber-950/40 via-slate-900/90 to-slate-950 shadow-[0_0_60px_rgba(251,191,36,0.3)] text-center space-y-2 animate-bounce-short relative overflow-hidden"
      >
        <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-amber-400 text-slate-950 text-xs font-extrabold uppercase tracking-widest shadow-md">
          <Trophy class="w-3.5 h-3.5" />
          <span>{{ awardedPrizeHeadline }}</span>
        </div>

        <div class="text-2xl sm:text-4xl font-black text-white font-heading tracking-tight">
          {{ latestWinner.guest_name }}
        </div>

        <div class="text-xs sm:text-base font-bold text-teal-400">
          {{ latestWinner.company || 'Distinguished Guest' }}
        </div>

        <p v-if="latestWinner.message" class="text-xs text-slate-300 italic font-light line-clamp-2">
          "{{ latestWinner.message }}"
        </p>

        <div class="text-[0.65rem] font-mono text-slate-400 pt-1 flex items-center justify-center gap-3">
          <span>Digital Guestbook Entry #{{ latestWinner.id }}</span>
          <span v-if="latestWinnerSlotInfo" class="text-amber-400 font-bold">• {{ latestWinnerSlotInfo }}</span>
        </div>
      </div>

      <!-- Controls & Action Bar -->
      <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 z-20">
        <!-- Target Prize Dropdown Selector -->
        <div v-if="unwonPrizes.length > 0" class="w-72">
          <select
            v-model="selectedPrizeId"
            :disabled="isSpinning"
            class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-700 text-xs font-bold text-slate-200 focus:outline-none focus:border-amber-400 shadow-md"
          >
            <option v-for="p in unwonPrizes" :key="p.id" :value="p.id">
              {{ p.label }} ({{ (p.winners?.length || 0) }}/{{ p.quantity || 1 }} Drawn)
            </option>
          </select>
        </div>

        <!-- Big Shiny SPIN Button -->
        <button
          @click="startSpin"
          :disabled="isSpinning || unwonPrizes.length === 0 || remainingPoolCount === 0"
          class="px-10 sm:px-14 py-4 sm:py-5 rounded-2xl font-black text-sm sm:text-base uppercase tracking-widest bg-gradient-to-r from-amber-400 via-teal-400 to-cyan-400 hover:from-amber-300 hover:to-cyan-300 text-slate-950 shadow-[0_0_40px_rgba(251,191,36,0.4)] hover:shadow-[0_0_60px_rgba(251,191,36,0.6)] hover:scale-105 active:scale-95 transition-all flex items-center gap-3 disabled:opacity-40 disabled:hover:scale-100 disabled:shadow-none cursor-pointer"
        >
          <Sparkles class="w-5 h-5 animate-spin" v-if="isSpinning" />
          <Disc3 class="w-5 h-5 animate-spin" v-else />
          <span>{{ isSpinning ? 'SPINNING ROULETTE...' : (activePrize ? 'SPIN ROULETTE' : 'DRAW CONCLUDED') }}</span>
        </button>
      </div>
    </main>

    <!-- Bottom Footer Status -->
    <footer class="p-4 text-center text-xs text-slate-500 z-20 relative border-t border-slate-900">
      <p>PT Hartono Raya Motor • Official Corporate Events & Gala Experience System</p>
    </footer>

    <!-- Winners History Side Drawer -->
    <div
      v-if="showWinnersDrawer"
      class="fixed inset-y-0 right-0 w-full sm:w-96 bg-[#091017] border-l border-slate-800 p-6 z-50 shadow-2xl flex flex-col justify-between overflow-y-auto"
    >
      <div class="space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
          <div class="flex items-center gap-2 text-amber-400">
            <Trophy class="w-5 h-5" />
            <h3 class="font-bold text-white font-heading">Doorprize Winners</h3>
          </div>
          <button @click="showWinnersDrawer = false" class="text-slate-400 hover:text-white p-1">
            <X class="w-5 h-5" />
          </button>
        </div>

        <div v-if="totalAwardedCount === 0" class="py-12 text-center text-slate-500 text-xs">
          No winners have been drawn yet.
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="p in localPrizes"
            :key="p.id"
            v-show="p.winners && p.winners.length > 0"
            class="p-4 rounded-2xl bg-slate-900/90 border border-teal-500/30 space-y-2.5 relative overflow-hidden"
          >
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
              <span class="text-xs font-bold uppercase tracking-wider text-amber-400">
                {{ p.label }}
              </span>
              <span class="text-[0.65rem] font-bold px-2 py-0.5 rounded-full bg-teal-950 text-teal-300 border border-teal-600">
                {{ p.winners?.length || 0 }} / {{ p.quantity || 1 }} Won
              </span>
            </div>

            <div v-if="p.description" class="text-[0.7rem] text-slate-400 font-light">
              {{ p.description }}
            </div>

            <!-- List of all winners for this prize tier -->
            <div class="space-y-2 pt-1">
              <div
                v-for="(w, wIdx) in p.winners"
                :key="w.id"
                class="p-2.5 rounded-xl bg-slate-950/80 border border-slate-800/80 space-y-0.5"
              >
                <div class="flex items-center justify-between text-xs">
                  <span class="font-bold text-white font-heading">
                    <span class="text-teal-400 mr-1">#{{ wIdx + 1 }}</span>
                    {{ w.guest_book_entry?.guest_name || 'Guest Winner' }}
                  </span>
                  <span class="text-[0.65rem] text-slate-500 font-mono">
                    Entry #{{ w.guest_book_entry_id }}
                  </span>
                </div>
                <div v-if="w.guest_book_entry?.company" class="text-[0.7rem] text-teal-400 font-medium">
                  {{ w.guest_book_entry?.company }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-800">
        <button
          @click="showWinnersDrawer = false"
          class="w-full py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-xs hover:bg-slate-700"
        >
          Close Drawer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import confetti from 'canvas-confetti';
import axios from 'axios';
import {
  ArrowLeft,
  Sparkles,
  Trophy,
  Maximize,
  Minimize,
  CheckCircle2,
  Disc3,
  SlidersHorizontal,
  X
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  prizes: {
    type: Array,
    default: () => [],
  },
  guestbookEntries: {
    type: Array,
    default: () => [],
  },
  wonEntryIds: {
    type: Array,
    default: () => [],
  },
});

const localPrizes = ref([...props.prizes]);
const localWonEntryIds = ref([...props.wonEntryIds]);
const isFullscreen = ref(false);
const showWinnersDrawer = ref(false);
const viewMode = ref('wheel'); // 'wheel' | 'reel'

const isSpinning = ref(false);
const currentRollingName = ref('Starting...');
const currentRollingCompany = ref('');
const latestWinner = ref(null);
const awardedPrizeHeadline = ref('');
const latestWinnerSlotInfo = ref('');

// Filter unwon prizes (prizes that still have remaining winner quotas)
const unwonPrizes = computed(() => {
  return localPrizes.value.filter(p => (p.winners?.length || 0) < (p.quantity || 1));
});

const selectedPrizeId = ref(unwonPrizes.value[0]?.id || null);

const activePrize = computed(() => {
  return localPrizes.value.find(p => p.id === selectedPrizeId.value) || unwonPrizes.value[0] || null;
});

const currentPrizeWinnerIndex = computed(() => {
  if (!activePrize.value) return 1;
  return (activePrize.value.winners?.length || 0) + 1;
});

const totalPrizesQuota = computed(() => {
  return localPrizes.value.reduce((sum, p) => sum + (p.quantity || 1), 0);
});

const totalAwardedCount = computed(() => {
  return localPrizes.value.reduce((sum, p) => sum + (p.winners?.length || 0), 0);
});

const eligiblePool = computed(() => {
  return props.guestbookEntries.filter(g => !localWonEntryIds.value.includes(g.id));
});

const remainingPoolCount = computed(() => eligiblePool.value.length);

// -------------------------------------------------------------
// Canvas Roulette Wheel Rendering & Physics
// -------------------------------------------------------------
const wheelCanvas = ref(null);
let currentRotation = 0;
let animationFrameId = null;

const wheelColors = [
  '#0D9488', // Teal
  '#1E293B', // Slate
  '#D97706', // Amber Gold
  '#0284C7', // Sky Blue
  '#059669', // Emerald
  '#334155', // Slate Light
  '#E11D48', // Crimson Rose
  '#0F766E', // Deep Teal
];

const drawWheel = () => {
  const canvas = wheelCanvas.value;
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const width = canvas.width;
  const height = canvas.height;
  const centerX = width / 2;
  const centerY = height / 2;
  const radius = width / 2 - 10;

  ctx.clearRect(0, 0, width, height);

  const pool = eligiblePool.value;
  const totalSlices = pool.length > 0 ? pool.length : 8;
  const sliceAngle = (2 * Math.PI) / totalSlices;

  ctx.save();
  ctx.translate(centerX, centerY);
  ctx.rotate(currentRotation);

  for (let i = 0; i < totalSlices; i++) {
    const startAngle = i * sliceAngle;
    const endAngle = startAngle + sliceAngle;

    // Draw Slice Segment
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.arc(0, 0, radius, startAngle, endAngle);
    ctx.closePath();

    ctx.fillStyle = wheelColors[i % wheelColors.length];
    ctx.fill();

    ctx.strokeStyle = '#0F172A';
    ctx.lineWidth = 2;
    ctx.stroke();

    // Draw Slice Text
    ctx.save();
    ctx.rotate(startAngle + sliceAngle / 2);
    ctx.textAlign = 'right';
    ctx.fillStyle = '#FFFFFF';
    ctx.font = 'bold 12px sans-serif';
    ctx.shadowColor = 'rgba(0,0,0,0.8)';
    ctx.shadowBlur = 4;

    const guest = pool[i];
    const label = guest ? `${guest.guest_name}` : `Ticket #${i + 1}`;
    const truncated = label.length > 16 ? label.substring(0, 14) + '...' : label;
    ctx.fillText(truncated, radius - 20, 4);

    ctx.restore();
  }

  // Draw Outer Golden Rim
  ctx.beginPath();
  ctx.arc(0, 0, radius, 0, 2 * Math.PI);
  ctx.strokeStyle = '#FBBF24';
  ctx.lineWidth = 8;
  ctx.stroke();

  ctx.restore();
};

const playTick = () => {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = 'triangle';
    osc.frequency.setValueAtTime(450, ctx.currentTime);
    gain.gain.setValueAtTime(0.08, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + 0.05);
  } catch (e) {}
};

const spinWheelToWinner = (winnerId, onFinish) => {
  const pool = eligiblePool.value;
  const winnerIndex = pool.findIndex(g => g.id === winnerId);
  const totalSlices = pool.length > 0 ? pool.length : 1;
  const sliceAngle = (2 * Math.PI) / totalSlices;

  const targetSliceCenter = (winnerIndex * sliceAngle) + (sliceAngle / 2);
  const extraRotations = 6 + Math.floor(Math.random() * 3);
  const finalAngle = (extraRotations * 2 * Math.PI) + (1.5 * Math.PI - targetSliceCenter);

  const startAngle = currentRotation % (2 * Math.PI);
  const totalAngleDelta = finalAngle - startAngle;
  const duration = 5000;
  const startTime = performance.now();
  let lastTickAngle = startAngle;

  const animate = (now) => {
    const elapsed = now - startTime;
    const progress = Math.min(elapsed / duration, 1);

    const easeOut = 1 - Math.pow(1 - progress, 3);
    currentRotation = startAngle + (totalAngleDelta * easeOut);

    if (Math.abs(currentRotation - lastTickAngle) >= sliceAngle) {
      playTick();
      lastTickAngle = currentRotation;
    }

    drawWheel();

    if (progress < 1) {
      animationFrameId = requestAnimationFrame(animate);
    } else {
      currentRotation = finalAngle % (2 * Math.PI);
      drawWheel();
      if (onFinish) onFinish();
    }
  };

  animationFrameId = requestAnimationFrame(animate);
};

const toggleFullscreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(() => {
      isFullscreen.value = true;
    }).catch(() => {});
  } else {
    document.exitFullscreen().then(() => {
      isFullscreen.value = false;
    }).catch(() => {});
  }
};

const startSpin = async () => {
  if (isSpinning.value || !activePrize.value || remainingPoolCount.value === 0) return;

  isSpinning.value = true;
  latestWinner.value = null;

  try {
    const response = await axios.post(route('admin.events.doorprize.spin', props.event.id), {
      prize_id: activePrize.value.id,
    });

    const data = response.data;
    const winner = data.winner;

    if (viewMode.value === 'wheel') {
      spinWheelToWinner(winner.id, () => {
        handleWinnerRevealed(data);
      });
    } else {
      const pool = eligiblePool.value;
      const rollTimer = setInterval(() => {
        if (pool.length > 0) {
          const randomGuest = pool[Math.floor(Math.random() * pool.length)];
          currentRollingName.value = randomGuest.guest_name;
          currentRollingCompany.value = randomGuest.company || '';
          playTick();
        }
      }, 70);

      setTimeout(() => {
        clearInterval(rollTimer);
        currentRollingName.value = winner.guest_name;
        currentRollingCompany.value = winner.company || '';
        handleWinnerRevealed(data);
      }, 4000);
    }

  } catch (error) {
    isSpinning.value = false;
    alert(error.response?.data?.message || 'Error occurred while spinning roulette.');
  }
};

const handleWinnerRevealed = (data) => {
  latestWinner.value = data.winner;
  const winnerIdx = data.winner_index || data.prize.winners?.length || 1;
  const totalQuota = data.total_quota || data.prize.quantity || 1;

  if (totalQuota > 1) {
    awardedPrizeHeadline.value = `WINNER ${winnerIdx} OF ${totalQuota}: ${data.prize.label}`;
    latestWinnerSlotInfo.value = `Slot ${winnerIdx} of ${totalQuota} Awarded`;
  } else {
    awardedPrizeHeadline.value = `WINNER: ${data.prize.label}`;
    latestWinnerSlotInfo.value = 'Sole Prize Winner';
  }

  localWonEntryIds.value.push(data.winner.id);

  // Update local prize record
  const pIndex = localPrizes.value.findIndex(p => p.id === data.prize.id);
  if (pIndex !== -1) {
    localPrizes.value[pIndex] = data.prize;
  }

  // If active prize is now fully awarded, switch selectedPrizeId to the next unwon prize
  if ((data.prize.winners?.length || 0) >= (data.prize.quantity || 1)) {
    const nextUnwon = localPrizes.value.find(p => (p.winners?.length || 0) < (p.quantity || 1));
    if (nextUnwon) {
      selectedPrizeId.value = nextUnwon.id;
    }
  }

  isSpinning.value = false;

  // Redraw updated wheel without winner
  setTimeout(() => {
    drawWheel();
  }, 500);

  // Launch Celebratory Triple Confetti
  fireCelebrationConfetti();
};

const fireCelebrationConfetti = () => {
  try {
    confetti({
      particleCount: 100,
      spread: 70,
      origin: { x: 0.1, y: 0.6 },
      colors: ['#FBBF24', '#00B4B4', '#06B6D4', '#FFFFFF'],
    });
    confetti({
      particleCount: 100,
      spread: 70,
      origin: { x: 0.9, y: 0.6 },
      colors: ['#FBBF24', '#00B4B4', '#06B6D4', '#FFFFFF'],
    });
    setTimeout(() => {
      confetti({
        particleCount: 160,
        spread: 100,
        origin: { x: 0.5, y: 0.5 },
        colors: ['#FBBF24', '#E11D48', '#00B4B4', '#FFFFFF'],
      });
    }, 250);
  } catch (e) {}
};

const handleKeydown = (e) => {
  if (e.code === 'Space' && !isSpinning.value && activePrize.value) {
    e.preventDefault();
    startSpin();
  }
};

watch(() => viewMode.value, () => {
  nextTick(() => {
    drawWheel();
  });
});

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
  document.addEventListener('fullscreenchange', () => {
    isFullscreen.value = !!document.fullscreenElement;
  });

  nextTick(() => {
    drawWheel();
  });
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
  if (animationFrameId) cancelAnimationFrame(animationFrameId);
});
</script>
