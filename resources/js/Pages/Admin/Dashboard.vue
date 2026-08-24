<template>
  <AdminLayout>
    <Head title="Organizer Dashboard" />

    <div class="space-y-8 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Overview</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Event Management Console
          </h1>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.check-in')"
            class="px-4 py-2 rounded-xl text-xs font-bold bg-cyan-500 hover:bg-cyan-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-cyan-950/50"
          >
            <QrCode class="w-4 h-4" />
            <span>Launch QR Scanner</span>
          </Link>

          <Link
            :href="route('admin.events.create')"
            class="px-4 py-2 rounded-xl text-xs font-bold bg-teal-500 hover:bg-teal-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-teal-950/50"
          >
            <Plus class="w-4 h-4" />
            <span>Create New Event</span>
          </Link>
        </div>
      </div>

      <!-- Quick Platform Workflow Banner -->
      <div class="glass-card rounded-3xl p-5 sm:p-6 border border-teal-500/30 light:border-teal-300/60 bg-gradient-to-r from-teal-950/60 via-slate-900/60 to-slate-950/60 light:from-teal-50 light:via-white light:to-cyan-50 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5 max-w-2xl">
          <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-[0.65rem] font-extrabold uppercase tracking-widest bg-teal-500/20 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-500/30 light:border-teal-300">
            <Sparkles class="w-3 h-3" />
            <span>Organizing An Event?</span>
          </div>
          <h3 class="text-base sm:text-lg font-bold text-white light:text-slate-900 font-heading">
            Quick 4-Step Event Lifecycle
          </h3>
          <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed font-light">
            1. <strong>Create Event & Rundowns</strong> &rarr; 2. <strong>Promote on Hero Slider</strong> &rarr; 3. <strong>Scan QR E-Tickets at Reception</strong> &rarr; 4. <strong>Publish Post-Event Recap & Photo Gallery</strong>.
          </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
          <AdminHelpModal />
        </div>
      </div>

      <!-- Stat Metrics Grid -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 space-y-2 shadow-lg">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Total Events</span>
            <Calendar class="w-4 h-4 text-teal-400 light:text-teal-600" />
          </div>
          <div class="text-2xl sm:text-3xl font-bold text-white light:text-slate-900 font-heading">
            {{ metrics?.totalEvents || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400 light:text-slate-500">Across all business units</div>
        </div>

        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 space-y-2 shadow-lg">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Total RSVPs</span>
            <Users class="w-4 h-4 text-teal-400 light:text-teal-600" />
          </div>
          <div class="text-2xl sm:text-3xl font-bold text-white light:text-slate-900 font-heading">
            {{ metrics?.totalRegistrations || 0 }}
          </div>
          <div class="text-[0.7rem] text-teal-400 light:text-teal-700 font-medium">Registered Attendees</div>
        </div>

        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 space-y-2 shadow-lg">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Live Check-Ins</span>
            <UserCheck class="w-4 h-4 text-cyan-400 light:text-cyan-600" />
          </div>
          <div class="text-2xl sm:text-3xl font-bold text-cyan-400 light:text-cyan-600 font-heading">
            {{ metrics?.totalCheckedIn || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400 light:text-slate-500">Scanned at reception desk</div>
        </div>

        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 space-y-2 shadow-lg">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Guestbook Entries</span>
            <MessageSquare class="w-4 h-4 text-rose-400 light:text-rose-600" />
          </div>
          <div class="text-2xl sm:text-3xl font-bold text-white light:text-slate-900 font-heading">
            {{ metrics?.totalGuestbook || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400 light:text-slate-500">Messages posted</div>
        </div>
      </div>

      <!-- Upcoming Events Table & Recent RSVPs -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Upcoming Events Status -->
        <div class="lg:col-span-7 glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">Upcoming Corporate Events</h2>
            <Link :href="route('admin.events.index')" class="text-xs text-teal-400 light:text-teal-700 hover:underline font-semibold">
              View All
            </Link>
          </div>

          <div class="space-y-3">
            <div
              v-for="ev in upcomingEvents"
              :key="ev.id"
              class="p-4 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
              <div class="space-y-1">
                <span class="text-[0.65rem] font-bold uppercase tracking-wider text-teal-400 light:text-teal-700">
                  {{ ev.business_unit?.name || 'Corporate Event' }}
                </span>
                <h3 class="text-sm font-bold text-white light:text-slate-900 font-heading leading-tight">{{ ev.title }}</h3>
                <div class="text-xs text-slate-400 light:text-slate-500">📅 {{ formatDate(ev.date) }} • 📍 {{ ev.venue_name }}</div>
              </div>

              <div class="flex items-center gap-3 shrink-0">
                <div class="text-right">
                  <div class="text-xs font-bold text-white light:text-slate-900">{{ ev.attendees_count || 0 }} / {{ ev.max_capacity }}</div>
                  <div class="text-[0.65rem] text-teal-400 light:text-teal-700">{{ ev.checked_in_count || 0 }} Attended</div>
                </div>

                <Link
                  :href="route('admin.events.check-in', ev.id)"
                  class="p-2 rounded-lg bg-cyan-950 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 hover:bg-cyan-900 border border-cyan-800/50 light:border-cyan-200"
                  title="Open QR Scanner"
                >
                  <QrCode class="w-4 h-4" />
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Registrations -->
        <div class="lg:col-span-5 glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">Recent RSVPs</h2>
            <Link :href="route('admin.registrations.index')" class="text-xs text-teal-400 light:text-teal-700 hover:underline font-semibold">
              Manage
            </Link>
          </div>

          <div class="space-y-3">
            <div
              v-for="reg in recentRegistrations"
              :key="reg.id"
              class="p-3.5 rounded-xl bg-slate-950/60 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 flex items-center justify-between gap-3 text-xs"
            >
              <div class="space-y-0.5">
                <div class="font-bold text-white light:text-slate-900 flex items-center gap-2">
                  <span>{{ reg.full_name }}</span>
                  <span v-if="reg.attendee_type === 'vip'" class="text-[0.65rem] px-1.5 rounded bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 font-mono font-bold">VIP</span>
                </div>
                <div class="text-slate-400 light:text-slate-500 text-[0.7rem] line-clamp-1">{{ reg.event?.title }}</div>
              </div>

              <span
                :class="[
                  'px-2 py-0.5 rounded-full text-[0.65rem] font-bold uppercase shrink-0',
                  reg.checked_in_at
                    ? 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300'
                    : 'bg-slate-800 light:bg-slate-200 text-slate-400 light:text-slate-700'
                ]"
              >
                {{ reg.checked_in_at ? 'Attended' : 'Confirmed' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import AdminHelpModal from '../../Components/AdminHelpModal.vue';
import { formatDate } from '../../Utils/date';
import {
  Calendar,
  Users,
  UserCheck,
  MessageSquare,
  Plus,
  QrCode,
  Sparkles
} from 'lucide-vue-next';

defineProps({
  metrics: {
    type: Object,
    default: () => ({
      totalEvents: 0,
      totalRegistrations: 0,
      totalCheckedIn: 0,
      totalGuestbook: 0,
    }),
  },
  recentRegistrations: {
    type: Array,
    default: () => [],
  },
  upcomingEvents: {
    type: Array,
    default: () => [],
  },
});
</script>
