<template>
  <PublicLayout>
    <Head title="Member & VIP Customer Portal" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Member Profile Header Banner -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-teal-500/30 mb-10 bg-gradient-to-r from-teal-950/60 via-slate-900 to-slate-950 relative overflow-hidden shadow-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center text-slate-950 font-extrabold text-2xl font-heading shadow-lg shadow-teal-500/30">
              {{ $page.props.auth?.user?.name.charAt(0) }}
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h1 class="text-xl sm:text-2xl font-bold text-white font-heading">
                  {{ $page.props.auth?.user?.name }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-extrabold uppercase tracking-widest bg-teal-500 text-slate-950">
                  {{ $page.props.auth?.user?.member_tier || 'VIP' }} Member
                </span>
              </div>
              <p class="text-xs text-slate-400 mt-1 font-light">
                {{ $page.props.auth?.user?.company || 'Mercedes-Benz Executive Owner' }} • {{ $page.props.auth?.user?.email }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <Link
              :href="route('public.events.index')"
              class="px-4 py-2.5 rounded-xl text-xs font-bold bg-teal-500 hover:bg-teal-400 text-slate-950 transition-colors"
            >
              Browse Events
            </Link>
          </div>
        </div>
      </div>

      <!-- Active Event Passes -->
      <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h2 class="text-xl font-bold text-white font-heading">My Active Event Passes</h2>
            <p class="text-xs text-slate-400 font-light">Your registered RSVP badges and digital QR entry codes.</p>
          </div>
        </div>

        <div v-if="activeTickets && activeTickets.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="ticket in activeTickets"
            :key="ticket.id"
            class="glass-card rounded-3xl p-6 border border-teal-500/30 space-y-4 flex flex-col justify-between"
          >
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase tracking-wider bg-teal-950 text-teal-300 border border-teal-800">
                  {{ ticket.attendee_type.replace('_', ' ') }}
                </span>
                <span class="text-xs font-mono font-bold text-teal-400">
                  {{ ticket.registration_code }}
                </span>
              </div>

              <h3 class="text-base font-bold text-white font-heading">
                {{ ticket.event.title }}
              </h3>
              <p class="text-xs text-slate-400 mt-1">📅 {{ formatDate(ticket.event.date) }} • {{ ticket.event.start_time }} WIB</p>
              <p class="text-xs text-slate-400">📍 {{ ticket.event.venue_name }}</p>

              <div v-if="ticket.vehicle_model" class="mt-3 p-2 rounded-xl bg-slate-950/60 text-xs text-slate-300">
                🚘 {{ ticket.vehicle_model }}
              </div>
            </div>

            <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
              <Link
                :href="route('public.events.ticket', { slug: ticket.event.slug, code: ticket.registration_code })"
                class="text-xs font-bold text-teal-400 hover:text-teal-300 flex items-center gap-1"
              >
                <span>View Full E-Ticket</span>
                <ChevronRight class="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>
        </div>

        <div v-else class="glass-card rounded-2xl p-8 text-center border border-slate-800 text-xs text-slate-400">
          You have no active event registrations.
        </div>
      </div>

      <!-- Exclusive Upcoming Gatherings -->
      <div>
        <h2 class="text-xl font-bold text-white font-heading mb-4">Recommended Gatherings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div
            v-for="event in exclusiveEvents"
            :key="event.id"
            class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between gap-4"
          >
            <div>
              <span class="text-[0.65rem] uppercase font-bold text-teal-400">{{ event.business_unit?.name }}</span>
              <h3 class="text-sm font-bold text-white font-heading mt-0.5">{{ event.title }}</h3>
              <p class="text-xs text-slate-400 mt-0.5">{{ formatDate(event.date) }} • {{ event.venue_name }}</p>
            </div>
            <Link
              :href="route('public.events.show', event.slug)"
              class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-teal-300 text-xs font-semibold shrink-0"
            >
              Details
            </Link>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import { formatDate } from '../../Utils/date';
import { ChevronRight } from 'lucide-vue-next';

defineProps({
  activeTickets: {
    type: Array,
    default: () => [],
  },
  exclusiveEvents: {
    type: Array,
    default: () => [],
  },
});
</script>
