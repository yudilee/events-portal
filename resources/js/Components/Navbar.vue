<template>
  <nav class="sticky top-0 z-50 glass-nav transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <Link :href="route('home')" class="flex items-center gap-2 group">
          <HartonoLogo size="md" />
        </Link>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
          <Link
            :href="route('home')"
            :class="[
              'px-3.5 py-2 rounded-lg text-sm font-medium transition-colors',
              route().current('home')
                ? 'text-teal-400 light:text-teal-700 bg-teal-950/40 light:bg-teal-50 border border-teal-800/40 light:border-teal-200'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/50 light:hover:bg-slate-100'
            ]"
          >
            Overview
          </Link>

          <Link
            :href="route('public.events.index')"
            :class="[
              'px-3.5 py-2 rounded-lg text-sm font-medium transition-colors',
              route().current('public.events.*')
                ? 'text-teal-400 light:text-teal-700 bg-teal-950/40 light:bg-teal-50 border border-teal-800/40 light:border-teal-200'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/50 light:hover:bg-slate-100'
            ]"
          >
            Events Calendar
          </Link>

          <Link
            :href="route('public.news.index')"
            :class="[
              'px-3.5 py-2 rounded-lg text-sm font-medium transition-colors',
              route().current('public.news.*')
                ? 'text-teal-400 light:text-teal-700 bg-teal-950/40 light:bg-teal-50 border border-teal-800/40 light:border-teal-200'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/50 light:hover:bg-slate-100'
            ]"
          >
            Press & News
          </Link>

          <Link
            :href="route('public.media-kit')"
            :class="[
              'px-3.5 py-2 rounded-lg text-sm font-medium transition-colors',
              route().current('public.media-kit')
                ? 'text-teal-400 light:text-teal-700 bg-teal-950/40 light:bg-teal-50 border border-teal-800/40 light:border-teal-200'
                : 'text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/50 light:hover:bg-slate-100'
            ]"
          >
            Media Kit
          </Link>

          <a
            href="https://hartonomotor-group.com"
            target="_blank"
            rel="noopener noreferrer"
            class="px-3.5 py-2 rounded-lg text-sm font-medium text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black hover:bg-slate-800/50 light:hover:bg-slate-100 flex items-center gap-1 transition-colors"
          >
            Corporate Site
            <ExternalLink class="w-3.5 h-3.5" />
          </a>
        </div>

        <!-- Right Side: Theme Toggle & Auth / Portals -->
        <div class="hidden md:flex items-center space-x-3">
          <!-- Theme Switcher Button -->
          <ThemeToggle />

          <template v-if="user">
            <!-- Role Badge -->
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wider" :class="roleBadgeClass">
              {{ user.role.replace('_', ' ') }}
            </span>

            <!-- Portal Shortcuts -->
            <Link
              v-if="user.role === 'admin' || user.role === 'event_organizer'"
              :href="route('admin.dashboard')"
              class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-teal-600 hover:bg-teal-500 text-slate-950 flex items-center gap-1.5 shadow-lg shadow-teal-900/30 transition-all"
            >
              <LayoutDashboard class="w-3.5 h-3.5" />
              Organizer Back-Office
            </Link>

            <Link
              v-else-if="user.role === 'news_agency'"
              :href="route('media.dashboard')"
              class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-cyan-600 hover:bg-cyan-500 text-slate-950 flex items-center gap-1.5 shadow-lg shadow-cyan-900/30 transition-all"
            >
              <Newspaper class="w-3.5 h-3.5" />
              Media Room
            </Link>

            <Link
              v-else
              :href="route('member.dashboard')"
              class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-teal-600 hover:bg-teal-500 text-slate-950 flex items-center gap-1.5 shadow-lg shadow-teal-900/30 transition-all"
            >
              <Ticket class="w-3.5 h-3.5" />
              Member Portal
            </Link>

            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800/60 light:hover:bg-slate-100 rounded-lg transition-colors"
              title="Sign Out"
            >
              <LogOut class="w-4 h-4" />
            </Link>
          </template>

          <template v-else>
            <Link
              :href="route('login')"
              class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100 transition-colors"
            >
              Sign In
            </Link>

            <Link
              :href="route('register')"
              class="px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 transition-all shadow-md shadow-teal-950/50"
            >
              Register Portal
            </Link>
          </template>
        </div>

        <!-- Mobile Menu & Theme Toggle -->
        <div class="flex md:hidden items-center gap-2">
          <ThemeToggle />
          <button
            @click="mobileOpen = !mobileOpen"
            class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none"
          >
            <Menu v-if="!mobileOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Dropdown -->
    <div v-if="mobileOpen" class="md:hidden glass-card border-b border-slate-800 px-4 pt-2 pb-6 space-y-2">
      <Link
        :href="route('home')"
        class="block px-3 py-2 rounded-md text-base font-medium text-slate-200 light:text-slate-800 hover:bg-slate-800 light:hover:bg-slate-100"
        @click="mobileOpen = false"
      >
        Overview
      </Link>
      <Link
        :href="route('public.events.index')"
        class="block px-3 py-2 rounded-md text-base font-medium text-slate-200 light:text-slate-800 hover:bg-slate-800 light:hover:bg-slate-100"
        @click="mobileOpen = false"
      >
        Events Calendar
      </Link>
      <Link
        :href="route('public.news.index')"
        class="block px-3 py-2 rounded-md text-base font-medium text-slate-200 light:text-slate-800 hover:bg-slate-800 light:hover:bg-slate-100"
        @click="mobileOpen = false"
      >
        Press & News
      </Link>
      <Link
        :href="route('public.media-kit')"
        class="block px-3 py-2 rounded-md text-base font-medium text-slate-200 light:text-slate-800 hover:bg-slate-800 light:hover:bg-slate-100"
        @click="mobileOpen = false"
      >
        Media Kit
      </Link>

      <div class="pt-4 border-t border-slate-800 space-y-2">
        <template v-if="user">
          <div class="px-3 py-2 flex items-center justify-between text-sm text-slate-300 light:text-slate-700">
            <span>{{ user.name }}</span>
            <span class="text-xs uppercase px-2 py-0.5 rounded bg-teal-900/60 text-teal-300">{{ user.role }}</span>
          </div>
          <Link
            v-if="user.role === 'admin' || user.role === 'event_organizer'"
            :href="route('admin.dashboard')"
            class="block w-full text-center px-4 py-2 rounded-lg bg-teal-500 text-black font-semibold text-sm"
          >
            Organizer Dashboard
          </Link>
          <Link
            v-else-if="user.role === 'news_agency'"
            :href="route('media.dashboard')"
            class="block w-full text-center px-4 py-2 rounded-lg bg-cyan-500 text-black font-semibold text-sm"
          >
            Media Room
          </Link>
          <Link
            v-else
            :href="route('member.dashboard')"
            class="block w-full text-center px-4 py-2 rounded-lg bg-teal-500 text-black font-semibold text-sm"
          >
            My Event Passes
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="block w-full text-center px-4 py-2 rounded-lg bg-slate-800 text-rose-400 text-sm font-medium"
          >
            Sign Out
          </Link>
        </template>
        <template v-else>
          <Link
            :href="route('login')"
            class="block w-full text-center px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-medium"
            @click="mobileOpen = false"
          >
            Sign In
          </Link>
          <Link
            :href="route('register')"
            class="block w-full text-center px-4 py-2 rounded-lg bg-teal-500 text-black text-sm font-bold"
            @click="mobileOpen = false"
          >
            Register Portal
          </Link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import HartonoLogo from './HartonoLogo.vue';
import ThemeToggle from './ThemeToggle.vue';
import {
  ExternalLink,
  Menu,
  X,
  LayoutDashboard,
  Ticket,
  Newspaper,
  LogOut
} from 'lucide-vue-next';

const mobileOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user);

const roleBadgeClass = computed(() => {
  switch (user.value?.role) {
    case 'admin': return 'bg-rose-900/40 text-rose-300 border border-rose-800/40';
    case 'event_organizer': return 'bg-amber-900/40 text-amber-300 border border-amber-800/40';
    case 'news_agency': return 'bg-cyan-900/40 text-cyan-300 border border-cyan-800/40';
    default: return 'bg-teal-900/40 text-teal-300 border border-teal-800/40';
  }
});
</script>
