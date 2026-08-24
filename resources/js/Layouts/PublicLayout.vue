<template>
  <div class="min-h-screen bg-mesh-dark text-[var(--text-main)] flex flex-col font-sans selection:bg-teal-500 selection:text-black">
    <!-- Navbar -->
    <Navbar />

    <!-- Flash Notifications -->
    <div v-if="flash?.success || flash?.error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
      <div
        v-if="flash.success"
        class="glass-card bg-teal-950/80 light:bg-teal-50 border-teal-500/40 light:border-teal-300 text-teal-200 light:text-teal-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-lg shadow-teal-950/40"
      >
        <div class="flex items-center gap-2 text-sm font-medium">
          <CheckCircle class="w-5 h-5 text-teal-400 light:text-teal-600 shrink-0" />
          <span>{{ flash.success }}</span>
        </div>
        <button @click="dismissFlash('success')" class="text-teal-400 light:text-teal-600 hover:text-white light:hover:text-black p-1">
          <X class="w-4 h-4" />
        </button>
      </div>

      <div
        v-if="flash.error"
        class="glass-card bg-rose-950/80 light:bg-rose-50 border-rose-500/40 light:border-rose-300 text-rose-200 light:text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-lg shadow-rose-950/40"
      >
        <div class="flex items-center gap-2 text-sm font-medium">
          <AlertCircle class="w-5 h-5 text-rose-400 light:text-rose-600 shrink-0" />
          <span>{{ flash.error }}</span>
        </div>
        <button @click="dismissFlash('error')" class="text-rose-400 light:text-rose-600 hover:text-white light:hover:text-black p-1">
          <X class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Main Page Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import { CheckCircle, AlertCircle, X } from 'lucide-vue-next';

const page = usePage();
const flashDismissed = ref({ success: false, error: false });

const flash = computed(() => {
  const pFlash = page.props.flash || {};
  return {
    success: flashDismissed.value.success ? null : pFlash.success,
    error: flashDismissed.value.error ? null : pFlash.error,
  };
});

const dismissFlash = (type) => {
  flashDismissed.value[type] = true;
};
</script>
