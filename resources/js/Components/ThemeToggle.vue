<template>
  <button
    type="button"
    @click="toggleTheme"
    class="p-2 rounded-xl border border-slate-700/60 light:border-slate-300 hover:border-teal-400 bg-slate-900/60 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-teal-400 light:hover:text-teal-600 transition-all flex items-center justify-center gap-1.5 focus:outline-none"
    :title="isLight ? 'Switch to Dark Mode' : 'Switch to Light Mode'"
  >
    <Sun v-if="isLight" class="w-4 h-4 text-amber-500 transition-transform duration-300 rotate-0 scale-100" />
    <Moon v-else class="w-4 h-4 text-cyan-400 transition-transform duration-300 -rotate-12 scale-100" />
    <span v-if="showLabel" class="text-xs font-semibold">
      {{ isLight ? 'Light' : 'Dark' }}
    </span>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Sun, Moon } from 'lucide-vue-next';

defineProps({
  showLabel: {
    type: Boolean,
    default: false,
  },
});

const isLight = ref(false);

const applyTheme = (light) => {
  isLight.value = light;
  if (light) {
    document.documentElement.classList.add('light');
    localStorage.setItem('theme', 'light');
  } else {
    document.documentElement.classList.remove('light');
    localStorage.setItem('theme', 'dark');
  }
};

const toggleTheme = () => {
  applyTheme(!isLight.value);
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'light') {
    applyTheme(true);
  } else if (savedTheme === 'dark') {
    applyTheme(false);
  } else {
    // Default to dark theme for luxury automotive branding
    applyTheme(false);
  }
});
</script>
