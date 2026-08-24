<template>
  <div class="flex items-center gap-3 select-none">
    <!-- Official High-Res Lockup (Auto-swaps for Dark & Light modes) -->
    <div class="relative flex items-center" :class="containerClass">
      <!-- Dark Mode Logo (White Typography) -->
      <img
        src="/images/logos/mercy-hartono-white.png"
        alt="Mercedes-Benz x Hartono Raya Motor"
        class="w-auto object-contain block light:hidden transition-all"
        :class="imageHeightClass"
      />

      <!-- Light Mode Logo (Black Typography) -->
      <img
        src="/images/logos/mercy-hartono-black.png"
        alt="Mercedes-Benz x Hartono Raya Motor"
        class="w-auto object-contain hidden light:block transition-all"
        :class="imageHeightClass"
      />
    </div>

    <!-- Optional Companion Tag -->
    <div v-if="withTag" class="hidden sm:flex flex-col border-l border-slate-700/60 light:border-slate-300 pl-3">
      <span class="text-[0.6rem] font-extrabold uppercase tracking-[0.25em] text-teal-400 light:text-teal-700 leading-tight">
        Corporate
      </span>
      <span class="text-[0.68rem] font-bold text-white light:text-slate-900 tracking-wider font-heading leading-tight">
        Events Portal
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  size: {
    type: String,
    default: 'md', // sm, md, lg, xl
  },
  withTag: {
    type: Boolean,
    default: false,
  },
});

const imageHeightClass = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'h-7 sm:h-8';
    case 'lg':
      return 'h-11 sm:h-13';
    case 'xl':
      return 'h-14 sm:h-16';
    default:
      return 'h-8 sm:h-10';
  }
});

const containerClass = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'max-w-[140px]';
    case 'lg':
      return 'max-w-[240px]';
    case 'xl':
      return 'max-w-[320px]';
    default:
      return 'max-w-[180px]';
  }
});
</script>
