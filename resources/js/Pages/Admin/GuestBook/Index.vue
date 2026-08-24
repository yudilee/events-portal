<template>
  <AdminLayout>
    <Head title="Guestbook Moderation & Live Wall" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Live Moderation</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Guest Book & Wall Messages
          </h1>
        </div>

        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
          <div class="w-full sm:w-72">
            <select
              v-model="selectedEventId"
              @change="changeEvent"
              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-medium"
            >
              <option v-for="ev in events" :key="ev.id" :value="ev.id">
                {{ ev.title }} ({{ formatDate(ev.date) }})
              </option>
            </select>
          </div>

          <template v-if="selectedEvent">
            <a
              :href="route('public.events.guestbook.kiosk', selectedEvent.slug)"
              target="_blank"
              class="px-3.5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-teal-950/50 shrink-0"
              title="Open Reception Tablet Kiosk Mode"
            >
              <Tablet class="w-4 h-4" />
              <span>Launch iPad Kiosk</span>
            </a>

            <a
              :href="route('public.events.guestbook.qr', selectedEvent.slug)"
              target="_blank"
              class="px-3.5 py-2.5 rounded-xl text-xs font-bold bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-cyan-400 light:text-cyan-700 border border-slate-700 light:border-slate-300 flex items-center gap-1.5 transition-colors shadow-sm shrink-0"
              title="Open Reception QR Standee Display"
            >
              <QrCode class="w-4 h-4" />
              <span>QR Standee</span>
            </a>
          </template>
        </div>
      </div>

      <!-- Messages Table -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">Guest & Affiliation</th>
                <th class="px-6 py-4">Message</th>
                <th class="px-6 py-4">Status / Highlight</th>
                <th class="px-6 py-4 text-right">Moderation Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr v-for="entry in entries.data" :key="entry.id" class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900 text-sm font-heading">{{ entry.guest_name }}</div>
                  <div class="text-[0.7rem] text-slate-400 light:text-slate-500">{{ entry.company || 'Attendee' }}</div>
                </td>

                <td class="px-6 py-4 max-w-md">
                  <p class="text-slate-200 light:text-slate-800 italic font-light">"{{ entry.message }}"</p>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span
                      :class="[
                        'px-2 py-0.5 rounded-full text-[0.65rem] font-bold uppercase',
                        entry.is_approved
                          ? 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300'
                          : 'bg-rose-950 light:bg-rose-100 text-rose-300 light:text-rose-800 border border-rose-800/40 light:border-rose-300'
                      ]"
                    >
                      {{ entry.is_approved ? 'Approved' : 'Pending' }}
                    </span>

                    <span
                      v-if="entry.is_highlighted"
                      class="px-2 py-0.5 rounded-full text-[0.65rem] font-bold uppercase bg-amber-950 light:bg-amber-100 text-amber-300 light:text-amber-800 border border-amber-800 light:border-amber-300"
                    >
                      ★ Featured
                    </span>
                  </div>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link
                      :href="route('admin.guestbook.highlight', entry.id)"
                      method="post"
                      as="button"
                      :class="[
                        'p-2 rounded-lg text-xs font-semibold transition-colors border',
                        entry.is_highlighted
                          ? 'bg-amber-500 text-slate-950 font-bold border-amber-500'
                          : 'bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 border-slate-700 light:border-slate-300 hover:text-amber-300 light:hover:text-amber-700'
                      ]"
                      title="Toggle Feature on Screen"
                    >
                      <Sparkles class="w-3.5 h-3.5" />
                    </Link>

                    <Link
                      :href="route('admin.guestbook.approval', entry.id)"
                      method="post"
                      as="button"
                      :class="[
                        'p-2 rounded-lg text-xs font-semibold transition-colors border border-slate-700 light:border-slate-300',
                        entry.is_approved
                          ? 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 hover:text-rose-400'
                          : 'bg-teal-600 text-slate-950 font-bold'
                      ]"
                      :title="entry.is_approved ? 'Hide Message' : 'Approve Message'"
                    >
                      <EyeOff v-if="entry.is_approved" class="w-3.5 h-3.5" />
                      <Eye v-else class="w-3.5 h-3.5" />
                    </Link>

                    <Link
                      v-if="$page.props.auth?.user?.role === 'admin'"
                      :href="route('admin.guestbook.destroy', entry.id)"
                      method="delete"
                      as="button"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 light:text-rose-600 hover:bg-rose-950 light:hover:bg-rose-100 border border-slate-700 light:border-slate-300"
                      title="Delete Entry (Admin Only)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Integrated Pagination Component -->
        <Pagination
          :pagination="entries"
          item-name="messages"
          :per-page-options="[10, 25, 50, 100, 'all']"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Pagination from '../../../Components/Pagination.vue';
import { formatDate } from '../../../Utils/date';
import { Sparkles, Eye, EyeOff, Trash2, Tablet, QrCode } from 'lucide-vue-next';

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
  selectedEvent: {
    type: Object,
    default: null,
  },
  entries: {
    type: Object,
    required: true,
  },
});

const selectedEventId = ref(props.selectedEvent?.id || '');

const changeEvent = () => {
  router.get(route('admin.events.guestbook', selectedEventId.value));
};
</script>
