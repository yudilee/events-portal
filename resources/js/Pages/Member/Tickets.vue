<template>
  <PublicLayout>
    <Head title="My Event Passes & Wallet" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Member Wallet</span>
        <h1 class="text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">My Event Passes</h1>
      </div>

      <div v-if="tickets.data && tickets.data.length > 0" class="space-y-4">
        <div
          v-for="ticket in tickets.data"
          :key="ticket.id"
          class="glass-card rounded-2xl p-6 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md"
        >
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-xs font-mono font-bold text-teal-400 light:text-teal-700">{{ ticket.registration_code }}</span>
              <span class="text-[0.65rem] px-2 py-0.5 rounded-full bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 uppercase">{{ ticket.status }}</span>
            </div>
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">{{ ticket.event.title }}</h3>
            <p class="text-xs text-slate-400 light:text-slate-600">{{ formatDate(ticket.event.date) }} • {{ ticket.event.venue_name }}</p>
          </div>

          <Link
            :href="route('public.events.ticket', { slug: ticket.event.slug, code: ticket.registration_code })"
            class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold self-start sm:self-auto shadow-md"
          >
            Open E-Ticket
          </Link>
        </div>
      </div>

      <div v-else class="glass-card rounded-2xl p-12 text-center border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white text-xs text-slate-400 light:text-slate-600 shadow-md">
        No past or current tickets recorded.
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import { formatDate } from '../../Utils/date';

defineProps({
  tickets: {
    type: Object,
    required: true,
  },
});
</script>
