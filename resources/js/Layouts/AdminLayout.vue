<template>
  <div class="min-h-screen bg-[#070B0E] light:bg-[#F8FAFC] text-slate-100 light:text-slate-900 flex flex-col md:flex-row font-sans selection:bg-teal-500 selection:text-black">
    <!-- Sidebar for Desktop (Toggled via isSidebarHidden) -->
    <aside
      v-if="!isSidebarHidden"
      class="w-full md:w-64 bg-[#091017] light:bg-white border-r border-slate-800/80 light:border-slate-200 flex flex-col justify-between shrink-0 transition-all duration-300"
    >
      <div>
        <!-- Top Logo & Sidebar Collapse Trigger -->
        <div class="p-6 border-b border-slate-800/80 light:border-slate-200 flex items-center justify-between">
          <div>
            <Link :href="route('admin.dashboard')">
              <HartonoLogo size="md" />
            </Link>
            <div class="mt-2 text-[0.65rem] font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">
              Organizer Back-Office
            </div>
          </div>
          <div class="flex items-center gap-1.5">
            <ThemeToggle />
            <button
              @click="isSidebarHidden = true"
              class="p-1.5 rounded-lg text-slate-400 hover:text-white light:hover:text-black hover:bg-slate-800 light:hover:bg-slate-100 transition-colors"
              title="Hide Sidebar (Full-Width View)"
            >
              <PanelLeftClose class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- In-App Workflow & Guide Trigger -->
        <div class="px-4 pt-4 pb-1">
          <AdminHelpModal />
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1 text-xs font-semibold">
          <Link
            :href="route('admin.dashboard')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.dashboard')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <LayoutDashboard class="w-4 h-4" />
            <span>Dashboard</span>
          </Link>

          <!-- On-Site Check-In Scanner (Highlight Button) -->
          <Link
            :href="route('admin.check-in')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.check-in*')
                ? 'bg-cyan-500 text-slate-950 font-bold shadow-md shadow-cyan-950/60'
                : 'text-cyan-400 light:text-cyan-700 bg-cyan-950/40 light:bg-cyan-50 border border-cyan-800/30 light:border-cyan-200 hover:bg-cyan-900/40'
            ]"
          >
            <QrCode class="w-4 h-4 animate-pulse" />
            <span>Live QR Scanner</span>
          </Link>

          <!-- Hero Carousel Management -->
          <Link
            :href="route('admin.banners.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.banners.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Sliders class="w-4 h-4" />
            <span>Hero Slider / Carousel</span>
          </Link>

          <!-- Business Units Ecosystem Management -->
          <Link
            :href="route('admin.business-units.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.business-units.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Building2 class="w-4 h-4" />
            <span>Business Units</span>
          </Link>

          <Link
            :href="route('admin.events.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.events.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Calendar class="w-4 h-4" />
            <span>Events & Rundowns</span>
          </Link>

          <Link
            :href="route('admin.registrations.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.registrations.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Users class="w-4 h-4" />
            <span>Attendees & RSVPs</span>
          </Link>

          <!-- Community Wishes & Feedback -->
          <Link
            :href="route('admin.wishes.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.wishes.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <HeartHandshake class="w-4 h-4" />
            <span>Wishes & Community</span>
          </Link>

          <Link
            :href="route('admin.guestbook.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.guestbook.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <MessageSquare class="w-4 h-4" />
            <span>Guestbook Wall</span>
          </Link>

          <!-- Doorprize Roulette & Prize Manager -->
          <Link
            :href="route('admin.doorprize.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.doorprize.*') || isCurrentRoute('admin.events.doorprize*')
                ? 'bg-amber-400 text-slate-950 font-bold shadow-md shadow-amber-950/60'
                : 'text-amber-400 light:text-amber-700 hover:text-amber-300 light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Trophy class="w-4 h-4" />
            <span>Doorprize Roulette</span>
          </Link>

          <Link
            :href="route('admin.news.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.news.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <Newspaper class="w-4 h-4" />
            <span>Press Releases</span>
          </Link>

          <!-- Footer & Portal Settings -->
          <Link
            :href="route('admin.settings.footer')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.settings.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <LayoutTemplate class="w-4 h-4" />
            <span>Footer & Portal Settings</span>
          </Link>

          <Link
            v-if="currentUser?.role === 'admin'"
            :href="route('admin.users.index')"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all',
              isCurrentRoute('admin.users.*')
                ? 'bg-teal-600 text-slate-950 font-bold shadow-md shadow-teal-950/60'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <ShieldCheck class="w-4 h-4" />
            <span>User & Roles</span>
          </Link>
        </nav>
      </div>

      <!-- Bottom Profile & Site Link -->
      <div class="p-4 border-t border-slate-800/80 light:border-slate-200 space-y-3">
        <Link
          :href="route('home')"
          class="flex items-center justify-between text-xs text-slate-400 light:text-slate-600 hover:text-teal-400 light:hover:text-teal-700 transition-colors px-2"
        >
          <span>View Public Portal</span>
          <ExternalLink class="w-3.5 h-3.5" />
        </Link>

        <div class="glass-card rounded-xl p-3 border border-slate-800 light:border-slate-200 flex items-center justify-between">
          <div class="flex items-center gap-2 text-xs">
            <div class="w-7 h-7 rounded-full bg-teal-500 text-slate-950 font-bold flex items-center justify-center font-heading">
              {{ userInitials }}
            </div>
            <div>
              <div class="font-bold text-white light:text-slate-900 leading-tight line-clamp-1">{{ currentUser?.name || 'Administrator' }}</div>
              <div class="text-[0.65rem] text-teal-400 light:text-teal-700 uppercase font-semibold">{{ userRoleName }}</div>
            </div>
          </div>

          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800 light:hover:bg-slate-100 transition-colors"
            title="Log Out"
          >
            <LogOut class="w-4 h-4" />
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Full-Width Mode Top Bar Trigger (When Sidebar is Collapsed) -->
      <div
        v-if="isSidebarHidden"
        class="bg-[#091017]/90 light:bg-white/90 backdrop-blur-md border-b border-slate-800/80 light:border-slate-200 px-4 py-2.5 flex items-center justify-between shrink-0"
      >
        <div class="flex items-center gap-3">
          <button
            @click="isSidebarHidden = false"
            class="px-3 py-1.5 rounded-xl bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-slate-200 light:text-slate-800 text-xs font-bold flex items-center gap-2 transition-colors border border-slate-700 light:border-slate-300"
          >
            <PanelLeftOpen class="w-4 h-4 text-teal-400" />
            <span>Show Sidebar</span>
          </button>

          <Link :href="route('admin.dashboard')" class="hidden sm:block">
            <HartonoLogo size="sm" />
          </Link>
        </div>

        <div class="flex items-center gap-3">
          <span class="text-xs font-bold text-teal-400 light:text-teal-700 uppercase tracking-widest hidden md:inline">
            Kiosk / Full-Width Display
          </span>
          <ThemeToggle />
        </div>
      </div>

      <!-- Flash Alert -->
      <div v-if="flash?.success || flash?.error" class="p-4">
        <div
          v-if="flash.success"
          class="bg-teal-950/90 light:bg-teal-50 border border-teal-500/50 light:border-teal-300 text-teal-200 light:text-teal-800 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-lg"
        >
          <span>{{ flash.success }}</span>
          <button @click="dismissFlash('success')"><X class="w-4 h-4 text-teal-400 light:text-teal-700" /></button>
        </div>

        <div
          v-if="flash.error"
          class="bg-rose-950/90 light:bg-rose-50 border border-rose-500/50 light:border-rose-300 text-rose-200 light:text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-lg"
        >
          <span>{{ flash.error }}</span>
          <button @click="dismissFlash('error')"><X class="w-4 h-4 text-rose-400 light:text-rose-700" /></button>
        </div>
      </div>

      <!-- Page Slot -->
      <main class="p-4 sm:p-8 flex-1">
        <slot :toggle-sidebar="toggleSidebar" :is-sidebar-hidden="isSidebarHidden" />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import HartonoLogo from '../Components/HartonoLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
import AdminHelpModal from '../Components/AdminHelpModal.vue';
import {
  LayoutDashboard,
  Building2,
  QrCode,
  Sliders,
  Calendar,
  Users,
  HeartHandshake,
  MessageSquare,
  Trophy,
  Newspaper,
  LayoutTemplate,
  ShieldCheck,
  ExternalLink,
  LogOut,
  PanelLeftClose,
  PanelLeftOpen,
  X
} from 'lucide-vue-next';

const props = defineProps({
  hideSidebar: {
    type: Boolean,
    default: false,
  },
});

const isSidebarHidden = ref(props.hideSidebar);

watch(() => props.hideSidebar, (val) => {
  isSidebarHidden.value = val;
});

const toggleSidebar = () => {
  isSidebarHidden.value = !isSidebarHidden.value;
};

const page = usePage();
const flashDismissed = ref({ success: false, error: false });

const currentUser = computed(() => page.props.auth?.user || null);
const userInitials = computed(() => {
  if (!currentUser.value?.name) return 'A';
  return currentUser.value.name.charAt(0).toUpperCase();
});
const userRoleName = computed(() => {
  if (!currentUser.value?.role) return 'ORGANIZER';
  return currentUser.value.role.replace('_', ' ');
});

const isCurrentRoute = (pattern) => {
  try {
    return typeof route === 'function' && route().current ? route().current(pattern) : false;
  } catch (e) {
    return false;
  }
};

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
