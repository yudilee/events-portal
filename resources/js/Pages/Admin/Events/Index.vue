<template>
  <AdminLayout>
    <Head title="Events Management" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Portfolio Operations</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Events Management
          </h1>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.events.create')"
            class="px-5 py-2.5 rounded-xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-lg shadow-teal-950/60 transition-all flex items-center gap-2"
          >
            <Plus class="w-4 h-4" />
            <span>Create New Event</span>
          </Link>
        </div>
      </div>

      <!-- Events List Table -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">Event Details</th>
                <th class="px-6 py-4">Business Unit</th>
                <th class="px-6 py-4">Date & Schedule</th>
                <th class="px-6 py-4">RSVP / Checked In</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr
                v-for="event in events.data"
                :key="event.id"
                class="hover:bg-slate-800/40 light:hover:bg-slate-50/80 transition-colors"
              >
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
                  <div v-if="event.is_date_tba" class="space-y-0.5">
                    <span class="inline-flex items-center gap-1 font-mono font-black text-xs text-cyan-400 light:text-cyan-800 bg-cyan-950/80 light:bg-cyan-100 px-2 py-0.5 rounded-md border border-cyan-700/50">
                      ⏳ NEW DATE: TO BE ANNOUNCED SHORTLY
                    </span>
                    <div v-if="event.original_date" class="text-[0.68rem] text-rose-400 line-through">
                      Semula: {{ formatDate(event.original_date) }}
                    </div>
                  </div>
                  <div v-else class="font-medium text-slate-200 light:text-slate-800 flex items-center gap-1.5">
                    <span>📅 {{ formatDate(event.date) }}</span>
                    <span v-if="event.original_date && event.status === 'rescheduled'" class="text-[0.65rem] text-rose-400 line-through">
                      (was {{ formatDate(event.original_date) }})
                    </span>
                  </div>
                  <div class="text-slate-400 light:text-slate-500 text-[0.7rem] mt-0.5">📍 {{ event.venue_name }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900">{{ event.attendees_count || 0 }} / {{ event.max_capacity }}</div>
                  <div class="text-teal-400 light:text-teal-700 text-[0.7rem]">{{ event.checked_in_count || 0 }} Checked In</div>
                </td>
                <td class="px-6 py-4">
                  <span
                    v-if="event.status === 'rescheduled'"
                    class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-black uppercase bg-amber-950 light:bg-amber-100 text-amber-300 light:text-amber-800 border border-amber-500/50 inline-flex items-center gap-1"
                    title="This event has been rescheduled"
                  >
                    <CalendarClock class="w-3 h-3" />
                    <span>Rescheduled</span>
                  </span>
                  <span
                    v-else-if="event.status === 'published'"
                    class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300"
                  >
                    Published
                  </span>
                  <span
                    v-else
                    class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase bg-slate-800 light:bg-slate-200 text-slate-400 light:text-slate-700"
                  >
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <!-- Reschedule Button -->
                    <button
                      type="button"
                      @click="openRescheduleModal(event)"
                      class="p-2 rounded-lg bg-amber-950/80 light:bg-amber-100 text-amber-300 light:text-amber-800 hover:bg-amber-900 border border-amber-500/40 light:border-amber-300 transition-colors"
                      title="Reschedule / Postpone Event"
                    >
                      <CalendarClock class="w-3.5 h-3.5" />
                    </button>

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

      <!-- Dedicated Reschedule Modal -->
      <div
        v-if="eventToReschedule"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
      >
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-lg w-full border-2 border-amber-500/50 light:border-amber-300 shadow-2xl space-y-5 bg-slate-950 light:bg-white relative animate-in fade-in zoom-in-95 duration-200">
          <button
            @click="eventToReschedule = null"
            class="absolute top-4 right-4 text-slate-400 hover:text-white p-1 rounded-lg bg-slate-900 light:bg-slate-100"
          >
            <X class="w-5 h-5" />
          </button>

          <div class="flex items-start gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center shrink-0">
              <CalendarClock class="w-6 h-6" />
            </div>
            <div>
              <span class="text-[0.65rem] font-bold uppercase tracking-widest text-amber-400">Emergency & Schedule Adjustment</span>
              <h3 class="text-lg font-black text-white light:text-slate-900 font-heading">
                Reschedule / Postpone Event
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600 line-clamp-1 mt-0.5">
                {{ eventToReschedule.title }}
              </p>
            </div>
          </div>

          <form @submit.prevent="submitReschedule" class="space-y-3.5 pt-2">
            <!-- TBA Checkbox Option -->
            <div class="p-3 rounded-2xl bg-amber-950/40 light:bg-amber-50 border border-amber-500/40">
              <label class="flex items-center gap-3 cursor-pointer">
                <input
                  v-model="rescheduleForm.is_date_tba"
                  type="checkbox"
                  class="w-4 h-4 text-amber-500 rounded bg-slate-900 border-amber-600 focus:ring-amber-500"
                />
                <div>
                  <span class="text-xs font-bold text-amber-300 light:text-amber-900 block">
                    Tanggal Baru Belum Ditentukan (To Be Announced Shortly / TBA)
                  </span>
                  <span class="text-[0.68rem] text-slate-400 light:text-slate-600 block">
                    Pilih opsi ini jika tanggal pengganti belum fix dan akan diumumkan kemudian.
                  </span>
                </div>
              </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div v-if="!rescheduleForm.is_date_tba">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1">
                  New Event Date <span class="text-rose-400">*</span>
                </label>
                <input
                  v-model="rescheduleForm.date"
                  type="date"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 light:bg-slate-100 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400"
                />
              </div>
              <div v-else class="p-3 rounded-xl bg-cyan-950/40 light:bg-cyan-50 border border-cyan-500/30 flex items-center justify-center text-center">
                <span class="text-xs font-mono font-black text-cyan-300 light:text-cyan-800 uppercase tracking-wider">
                  ⏳ NEW DATE: TO BE ANNOUNCED SHORTLY
                </span>
              </div>

              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1">
                  Start & End Time <span class="text-rose-400">*</span>
                </label>
                <div class="flex items-center gap-1.5">
                  <input
                    v-model="rescheduleForm.start_time"
                    type="text"
                    required
                    placeholder="10:00"
                    class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 light:bg-slate-100 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 font-mono text-center focus:outline-none focus:border-amber-400"
                  />
                  <span class="text-slate-500">-</span>
                  <input
                    v-model="rescheduleForm.end_time"
                    type="text"
                    placeholder="13:00"
                    class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 light:bg-slate-100 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 font-mono text-center focus:outline-none focus:border-amber-400"
                  />
                </div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 light:text-slate-700 mb-1">
                Venue Name <span class="text-rose-400">*</span>
              </label>
              <input
                v-model="rescheduleForm.venue_name"
                type="text"
                required
                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 light:bg-slate-100 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400"
              />
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-1">
                Reschedule Reason / Public Announcement Message <span class="text-rose-400">*</span>
              </label>
              <textarea
                v-model="rescheduleForm.reschedule_notice"
                required
                rows="3"
                placeholder="Tuliskan alasan penundaan atau jadwal baru yang akan ditampilkan kepada para tamu..."
                class="w-full p-3 rounded-xl bg-slate-900 light:bg-slate-100 border border-amber-500/50 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400 leading-relaxed"
              ></textarea>
            </div>

            <div class="p-3 rounded-xl bg-amber-950/40 light:bg-amber-50 border border-amber-800/40 text-[0.72rem] text-amber-300 light:text-amber-900 space-y-1">
              <strong>📢 Info untuk Penyelenggara:</strong>
              <p>
                Status acara akan otomatis diubah menjadi <strong>RESCHEDULED</strong>. Banner pengumuman resmi akan tampil di halaman event dan e-tiket tamu. Tiket & QR code yang telah diterbitkan tetap sah.
              </p>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center gap-2.5 pt-2">
              <button
                type="button"
                @click="eventToReschedule = null"
                class="flex-1 py-3 rounded-xl text-xs font-bold bg-slate-800 light:bg-slate-200 hover:bg-slate-700 text-slate-300 light:text-slate-700 transition-colors"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="rescheduleForm.processing"
                class="flex-1 py-3 rounded-xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 shadow-lg shadow-amber-950/60 transition-all flex items-center justify-center gap-1.5"
              >
                <CalendarClock class="w-4 h-4" />
                <span>{{ rescheduleForm.processing ? 'Menyimpan...' : 'Konfirmasi Reschedule' }}</span>
              </button>
            </div>
          </form>
        </div>
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
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Pagination from '../../../Components/Pagination.vue';
import { formatDate } from '../../../Utils/date';
import {
  Plus,
  QrCode,
  Users,
  Edit3,
  Camera,
  Sparkles,
  Trash2,
  AlertTriangle,
  CalendarClock,
  X
} from 'lucide-vue-next';

defineProps({
  events: {
    type: Object,
    required: true,
  },
});

const eventToDelete = ref(null);
const eventToReschedule = ref(null);
const isDeleting = ref(false);

const rescheduleForm = useForm({
  is_date_tba: true,
  date: '',
  start_time: '10:00',
  end_time: '13:00',
  venue_name: '',
  venue_address: '',
  venue_map_url: '',
  registration_deadline: null,
  reschedule_notice: '',
});

const openRescheduleModal = (event) => {
  eventToReschedule.value = event;
  rescheduleForm.is_date_tba = event.is_date_tba ?? true;
  rescheduleForm.date = event.date || '';
  rescheduleForm.start_time = event.start_time || '10:00';
  rescheduleForm.end_time = event.end_time || '13:00';
  rescheduleForm.venue_name = event.venue_name || '';
  rescheduleForm.venue_address = event.venue_address || '';
  rescheduleForm.venue_map_url = event.venue_map_url || '';
  rescheduleForm.registration_deadline = event.registration_deadline || null;
  rescheduleForm.reschedule_notice = event.reschedule_notice || `Kami memohon maaf yang sebesar-besarnya atas ketidaknyamanan yang terjadi. Keamanan, kenyamanan, dan kepuasan Anda tetap menjadi prioritas utama kami. Jadwal tanggal baru acara akan kami umumkan segera (To Be Announced Shortly). Seluruh e-tiket yang telah didapatkan tetap berlaku.`;
};

const submitReschedule = () => {
  if (!eventToReschedule.value) return;

  rescheduleForm.post(route('admin.events.reschedule', eventToReschedule.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      eventToReschedule.value = null;
    },
  });
};

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
