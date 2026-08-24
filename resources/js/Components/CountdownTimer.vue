<template>
  <div class="grid grid-cols-4 gap-1.5 sm:gap-2.5 w-full">
    <!-- Days -->
    <div class="rounded-2xl p-2 sm:p-2.5 text-center border border-teal-500/30 bg-slate-900/80 light:bg-white/90 backdrop-blur-md shadow-md flex flex-col items-center justify-center min-w-0">
      <span class="block text-xl sm:text-2xl lg:text-3xl font-black font-heading text-white light:text-slate-900 tracking-tight leading-none">
        {{ String(days).padStart(2, '0') }}
      </span>
      <span class="mt-1 text-[0.55rem] sm:text-[0.65rem] font-bold uppercase tracking-wider text-teal-400 light:text-teal-700 block truncate w-full">
        Days
      </span>
    </div>

    <!-- Hours -->
    <div class="rounded-2xl p-2 sm:p-2.5 text-center border border-teal-500/30 bg-slate-900/80 light:bg-white/90 backdrop-blur-md shadow-md flex flex-col items-center justify-center min-w-0">
      <span class="block text-xl sm:text-2xl lg:text-3xl font-black font-heading text-white light:text-slate-900 tracking-tight leading-none">
        {{ String(hours).padStart(2, '0') }}
      </span>
      <span class="mt-1 text-[0.55rem] sm:text-[0.65rem] font-bold uppercase tracking-wider text-teal-400 light:text-teal-700 block truncate w-full">
        Hours
      </span>
    </div>

    <!-- Minutes -->
    <div class="rounded-2xl p-2 sm:p-2.5 text-center border border-teal-500/30 bg-slate-900/80 light:bg-white/90 backdrop-blur-md shadow-md flex flex-col items-center justify-center min-w-0">
      <span class="block text-xl sm:text-2xl lg:text-3xl font-black font-heading text-white light:text-slate-900 tracking-tight leading-none">
        {{ String(minutes).padStart(2, '0') }}
      </span>
      <span class="mt-1 text-[0.55rem] sm:text-[0.65rem] font-bold uppercase tracking-wider text-teal-400 light:text-teal-700 block truncate w-full">
        Minutes
      </span>
    </div>

    <!-- Seconds -->
    <div class="rounded-2xl p-2 sm:p-2.5 text-center border border-cyan-500/30 bg-slate-900/80 light:bg-white/90 backdrop-blur-md shadow-md flex flex-col items-center justify-center min-w-0">
      <span class="block text-xl sm:text-2xl lg:text-3xl font-black font-heading text-cyan-400 light:text-cyan-600 tracking-tight leading-none">
        {{ String(seconds).padStart(2, '0') }}
      </span>
      <span class="mt-1 text-[0.55rem] sm:text-[0.65rem] font-bold uppercase tracking-wider text-cyan-400 light:text-cyan-600 block truncate w-full">
        Seconds
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  targetDate: {
    type: String,
    required: true,
  },
  targetTime: {
    type: String,
    default: '10:00',
  },
});

const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(0);
let interval = null;

const calculateTime = () => {
  let datePart = props.targetDate || '';
  if (typeof datePart === 'string' && datePart.includes('T')) {
    datePart = datePart.split('T')[0];
  }
  const timePart = (props.targetTime || '10:00').substring(0, 5);
  const target = new Date(`${datePart}T${timePart}:00`).getTime();
  const now = new Date().getTime();
  const diff = target - now;

  if (isNaN(diff) || diff <= 0) {
    days.value = 0;
    hours.value = 0;
    minutes.value = 0;
    seconds.value = 0;
    return;
  }

  days.value = Math.floor(diff / (1000 * 60 * 60 * 24));
  hours.value = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  minutes.value = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  seconds.value = Math.floor((diff % (1000 * 60)) / 1000);
};

onMounted(() => {
  calculateTime();
  interval = setInterval(calculateTime, 1000);
});

onUnmounted(() => {
  if (interval) clearInterval(interval);
});
</script>
