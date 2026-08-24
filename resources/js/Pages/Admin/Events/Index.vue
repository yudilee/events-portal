<template>
  <AdminLayout>
    <Head title="Events Management" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Events Management</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Corporate Events & Gatherings
          </h1>
        </div>

        <Link
          :href="route('admin.events.create')"
          class="px-4 py-2 rounded-xl text-xs font-bold bg-teal-500 hover:bg-teal-400 text-slate-950 flex items-center gap-1.5 self-start sm:self-auto transition-all shadow-md shadow-teal-950/50"
        >
          <Plus class="w-4 h-4" />
          <span>New Event</span>
        </Link>
      </div>

      <!-- Events Table Card -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">Event Details</th>
                <th class="px-6 py-4">Business Unit</th>
                <th class="px-6 py-4">Date & Venue</th>
                <th class="px-6 py-4">Capacity / Attendance</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr v-for="event in events.data" :key="event.id" class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900 text-sm font-heading">{{ event.title }}</div>
                  <div class="text-slate-400 light:text-slate-500 text-[0.7rem]">{{ event.subtitle || event.event_type }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="px-2.5 py-1 rounded-full text-[0.65rem] font-bold uppercase bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300">
                    {{ event.business_unit?.name || 'Group HQ' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="font-medium text-slate-200 light:text-slate-800">📅 {{ formatDate(event.date) }}</div>
                  <div class="text-slate-400 light:text-slate-500 text-[0.7rem]">📍 {{ event.venue_name }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900">{{ event.attendees_count || 0 }} / {{ event.max_capacity }}</div>
                  <div class="text-teal-400 light:text-teal-700 text-[0.7rem]">{{ event.checked_in_count || 0 }} Checked In</div>
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase',
                      event.status === 'published'
                        ? 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300'
                        : 'bg-slate-800 light:bg-slate-200 text-slate-400 light:text-slate-700'
                    ]"
                  >
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <Link
                      :href="route('admin.events.media.index', event.id)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-teal-300 light:hover:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors"
                      title="Manage Media & Gallery"
                    >
                      <Camera class="w-3.5 h-3.5" />
                    </Link>
                    <Link
                      :href="route('admin.events.post-event', event.id)"
                      class="p-2 rounded-lg bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 hover:bg-teal-900 light:hover:bg-teal-200 border border-teal-800/40 light:border-teal-300 transition-colors"
                      title="Post-Event Narrative & Wishes"
                    >
                      <Sparkles class="w-3.5 h-3.5" />
                    </Link>
                    <Link
                      :href="route('admin.events.check-in', event.id)"
                      class="p-2 rounded-lg bg-cyan-950 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 hover:bg-cyan-900 light:hover:bg-cyan-200 border border-cyan-800/40 light:border-cyan-300"
                      title="Open Live Scanner"
                    >
                      <QrCode class="w-3.5 h-3.5" />
                    </Link>
                    <Link
                      :href="route('admin.events.registrations', event.id)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black"
                      title="View Attendees"
                    >
                      <Users class="w-3.5 h-3.5" />
                    </Link>
                    <Link
                      :href="route('admin.events.edit', event.id)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-teal-400 light:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200"
                      title="Edit Event"
                    >
                      <Edit3 class="w-3.5 h-3.5" />
                    </Link>
                    <button
                      v-if="$page.props.auth?.user?.role === 'admin'"
                      type="button"
                      @click.stop="openDeleteModal(event)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 hover:bg-rose-950/60 light:hover:bg-rose-100 transition-colors"
                      title="Delete Event (Admin Only)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Integrated Pagination Component -->
        <Pagination
          :pagination="events"
          item-name="events"
          :per-page-options="[10, 15, 25, 50, 'all']"
        />
      </div>

      <!-- High-Contrast Dual-Theme Delete Confirmation Modal -->
      <div
        v-if="eventToDelete"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      >
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-md w-full border border-rose-500/40 light:border-rose-300 shadow-2xl space-y-5 bg-slate-950/95 light:bg-white text-center relative">
          <!-- Icon -->
          <div class="w-14 h-14 rounded-2xl bg-rose-950/80 light:bg-rose-100 border border-rose-500/40 light:border-rose-200 flex items-center justify-center text-rose-400 light:text-rose-600 mx-auto">
            <AlertTriangle class="w-7 h-7" />
          </div>

          <div class="space-y-2.5">
            <h3 class="text-xl font-black text-white light:text-slate-900 font-heading">
              Delete Event?
            </h3>
            <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed">
              Are you sure you want to permanently delete:
            </p>
            <div class="p-3 rounded-2xl bg-slate-900/90 light:bg-slate-100 border border-slate-800 light:border-slate-200 text-sm font-bold text-white light:text-slate-900 font-heading">
              "{{ eventToDelete.title }}"
            </div>

            <!-- Warning Box -->
            <div class="text-[0.75rem] text-rose-300 light:text-rose-800 bg-rose-950/40 light:bg-rose-50 p-3.5 rounded-2xl border border-rose-800/40 light:border-rose-200 text-left space-y-1 mt-3">
              <div class="font-bold text-rose-400 light:text-rose-700 flex items-center gap-1.5">
                <span>⚠️ Irreversible Action:</span>
              </div>
              <p class="leading-relaxed text-rose-300 light:text-rose-900">
                This will permanently remove all linked registrations, attendee QR passes, event agendas, guestbook entries, gallery media, and doorprizes.
              </p>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex items-center gap-3 pt-2">
            <button
              type="button"
              @click="eventToDelete = null"
              :disabled="isDeleting"
              class="flex-1 py-3 rounded-xl text-xs font-bold bg-slate-800 light:bg-slate-200 hover:bg-slate-700 light:hover:bg-slate-300 text-slate-300 light:text-slate-700 transition-colors"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="confirmDelete"
              :disabled="isDeleting"
              class="flex-1 py-3 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-950/60 transition-all flex items-center justify-center gap-1.5"
            >
              <Trash2 class="w-3.5 h-3.5" />
              <span>{{ isDeleting ? 'Deleting...' : 'Yes, Delete Event' }}</span>
            </button>
          </div>
        </div>
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
import { Plus, QrCode, Users, Edit3, Camera, Sparkles, Trash2, AlertTriangle } from 'lucide-vue-next';

defineProps({
  events: {
    type: Object,
    required: true,
  },
});

const eventToDelete = ref(null);
const isDeleting = ref(false);

const openDeleteModal = (event) => {
  eventToDelete.value = event;
};

const confirmDelete = () => {
  if (!eventToDelete.value || isDeleting.value) return;
  isDeleting.value = true;

  router.delete(route('admin.events.destroy', eventToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false;
      eventToDelete.value = null;
    },
  });
};
</script>
