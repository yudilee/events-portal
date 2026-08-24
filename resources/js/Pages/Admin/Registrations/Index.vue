<template>
  <AdminLayout>
    <Head title="Attendees & RSVPs Management" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">RSVP Desk</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Guest List & Registrations
          </h1>
        </div>

        <div class="flex items-center gap-3">
          <button
            v-if="selectedEvent && (selectedEvent.status === 'rescheduled' || selectedEvent.reschedule_notice)"
            @click="showBroadcastModal = true"
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-amber-950/50"
            title="Copy WhatsApp Reschedule Announcement Template"
          >
            <MessageSquare class="w-4 h-4" />
            <span>Reschedule WhatsApp Broadcast</span>
          </button>

          <a
            v-if="selectedEvent"
            :href="route('admin.registrations.export', selectedEvent.id)"
            class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-teal-400 light:text-teal-700 border border-slate-700 light:border-slate-300 flex items-center gap-1.5 transition-colors shadow-sm"
          >
            <Download class="w-4 h-4" />
            <span>Export CSV</span>
          </a>

          <Link
            :href="route('admin.check-in')"
            class="px-4 py-2 rounded-xl text-xs font-bold bg-cyan-500 hover:bg-cyan-400 text-slate-950 flex items-center gap-1.5 transition-all shadow-md shadow-cyan-950/50"
          >
            <QrCode class="w-4 h-4" />
            <span>Launch Scanner</span>
          </Link>
        </div>
      </div>

      <!-- Filters & Search Toolbar -->
      <div class="glass-card rounded-2xl p-4 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between shadow-md">
        <!-- Left: Event Switcher -->
        <div class="w-full lg:w-72 shrink-0">
          <select
            v-model="selectedEventId"
            @change="applyFilters"
            class="w-full px-3.5 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-medium"
          >
            <option v-for="ev in events" :key="ev.id" :value="ev.id">
              {{ ev.title }} ({{ formatDate(ev.date) }})
            </option>
          </select>
        </div>

        <!-- Middle & Right: Category Filter, Status Filter, and Search -->
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 flex-1 justify-end">
          <!-- Attendee Type Filter -->
          <div class="w-full sm:w-40 shrink-0">
            <select
              v-model="type"
              @change="applyFilters"
              class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            >
              <option value="">All Categories</option>
              <option value="vip">VIP Guest</option>
              <option value="media">Media / Press</option>
              <option value="general">General Guest</option>
            </select>
          </div>

          <!-- Status Filter -->
          <div class="w-full sm:w-40 shrink-0">
            <select
              v-model="status"
              @change="applyFilters"
              class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            >
              <option value="">All Statuses</option>
              <option value="attended">Attended / Checked In</option>
              <option value="confirmed">Confirmed (Not Arrived)</option>
              <option value="pending">Pending</option>
              <option value="waitlist">Waitlist</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Search Input -->
          <div class="relative w-full sm:w-64">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              v-model="search"
              @input="handleSearch"
              type="text"
              placeholder="Search name, code, vehicle..."
              class="w-full pl-10 pr-8 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 placeholder-slate-400 focus:outline-none focus:border-teal-400"
            />
            <button
              v-if="search"
              @click="clearSearch"
              type="button"
              class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white light:hover:text-slate-900 text-xs p-1"
            >
              &times;
            </button>
          </div>
        </div>
      </div>

      <!-- Attendees Table Card -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">Guest Code & Name</th>
                <th class="px-6 py-4">Company / Category</th>
                <th class="px-6 py-4">Contact</th>
                <th class="px-6 py-4">Vehicle Details</th>
                <th class="px-6 py-4">Attendance</th>
                <th class="px-6 py-4 text-right">Quick Check-In</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr v-if="!registrations.data || registrations.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-400 light:text-slate-500">
                  <div class="max-w-xs mx-auto space-y-2">
                    <p class="text-sm font-semibold text-slate-300 light:text-slate-700">No registrations found</p>
                    <p class="text-xs text-slate-500">Try adjusting your event selection, category filter, or search keywords.</p>
                  </div>
                </td>
              </tr>
              <tr v-for="reg in registrations.data" :key="reg.id" class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-mono text-teal-400 light:text-teal-700 font-bold">{{ reg.registration_code }}</div>
                  <div class="font-bold text-white light:text-slate-900 text-sm font-heading mt-0.5">{{ reg.full_name }}</div>
                  <div class="text-[0.65rem] text-slate-400 light:text-slate-500">{{ reg.num_attendees }} Attendee(s)</div>
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2 py-0.5 rounded-full text-[0.65rem] font-bold uppercase tracking-wider',
                      reg.attendee_type === 'vip' ? 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300' :
                      reg.attendee_type === 'media' ? 'bg-cyan-950 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 border border-cyan-800 light:border-cyan-300' : 'bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700'
                    ]"
                  >
                    {{ reg.attendee_type.replace('_', ' ') }}
                  </span>
                  <div class="text-slate-300 light:text-slate-600 mt-1 text-[0.7rem]">{{ reg.company || 'Private Guest' }}</div>
                </td>

                <td class="px-6 py-4">
                  <div class="text-slate-200 light:text-slate-800 font-medium">{{ reg.email }}</div>
                  <div class="text-slate-400 light:text-slate-500 font-mono">{{ reg.phone }}</div>
                </td>

                <td class="px-6 py-4">
                  <div v-if="reg.vehicle_model" class="text-slate-200 light:text-slate-800">
                    🚘 {{ reg.vehicle_model }}
                    <span v-if="reg.license_plate" class="text-slate-400 light:text-slate-500 font-mono block text-[0.65rem]">[{{ reg.license_plate }}]</span>
                  </div>
                  <span v-else class="text-slate-500">—</span>
                </td>

                <td class="px-6 py-4">
                  <div v-if="reg.checked_in_at" class="text-teal-400 light:text-teal-700 font-semibold">
                    ✓ Checked In
                    <span class="block text-[0.65rem] text-slate-400 light:text-slate-500">{{ new Date(reg.checked_in_at).toLocaleTimeString() }}</span>
                  </div>
                  <div v-else class="text-slate-500 font-medium">
                    Not Arrived Yet
                  </div>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link
                      :href="route('admin.registrations.check-in-toggle', reg.id)"
                      method="post"
                      as="button"
                      :class="[
                        'px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm',
                        reg.checked_in_at
                          ? 'bg-slate-800 light:bg-slate-100 text-rose-400 hover:bg-rose-950/60 light:hover:bg-rose-100 border border-slate-700 light:border-slate-300'
                          : 'bg-teal-600 hover:bg-teal-500 text-slate-950'
                      ]"
                    >
                      {{ reg.checked_in_at ? 'Undo Check-In' : 'Mark Arrived' }}
                    </Link>

                    <button
                      v-if="$page.props.auth?.user?.role === 'admin'"
                      type="button"
                      @click="deleteRegistration(reg)"
                      class="p-1.5 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 hover:bg-rose-950/60 light:hover:bg-rose-100 transition-colors"
                      title="Delete Registration (Admin Only)"
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
          :pagination="registrations"
          item-name="guests"
          :per-page-options="[10, 25, 50, 100, 'all']"
        />
      </div>

      <!-- Reschedule WhatsApp Broadcast Modal -->
      <div
        v-if="showBroadcastModal && selectedEvent"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      >
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-lg w-full border-2 border-amber-500/50 light:border-amber-300 shadow-2xl space-y-4 bg-slate-950 light:bg-white relative">
          <button
            @click="showBroadcastModal = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-white p-1 rounded-lg bg-slate-900 light:bg-slate-100"
          >
            <X class="w-5 h-5" />
          </button>

          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center shrink-0">
              <MessageSquare class="w-5 h-5" />
            </div>
            <div>
              <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
                Template Broadcast WhatsApp Reschedule
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600">
                Pesan siap kirim untuk menginformasikan jadwal baru kepada para tamu RSVP.
              </p>
            </div>
          </div>

          <div class="p-3.5 rounded-2xl bg-slate-900 light:bg-slate-100 border border-slate-800 light:border-slate-200">
            <textarea
              readonly
              rows="9"
              :value="whatsappTemplateText"
              class="w-full bg-transparent text-xs text-slate-200 light:text-slate-800 font-mono focus:outline-none resize-none leading-relaxed"
            ></textarea>
          </div>

          <div class="flex items-center justify-between gap-3 pt-2">
            <span v-if="copied" class="text-xs text-teal-400 font-bold flex items-center gap-1">
              ✓ Teks berhasil disalin ke clipboard!
            </span>
            <span v-else></span>

            <div class="flex items-center gap-2">
              <button
                type="button"
                @click="showBroadcastModal = false"
                class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700"
              >
                Tutup
              </button>
              <button
                type="button"
                @click="copyWhatsAppTemplate"
                class="px-5 py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 text-slate-950 shadow-md flex items-center gap-1.5"
              >
                <Copy class="w-4 h-4" />
                <span>{{ copied ? 'Disalin!' : 'Salin Pesan' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Pagination from '../../../Components/Pagination.vue';
import { formatDate } from '../../../Utils/date';
import { Download, QrCode, Search, Trash2, MessageSquare, Copy, X } from 'lucide-vue-next';

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
  selectedEvent: {
    type: Object,
    default: null,
  },
  registrations: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const deleteRegistration = (reg) => {
  if (confirm(`Delete RSVP registration for "${reg.full_name}" (${reg.registration_code})?`)) {
    router.delete(route('admin.registrations.destroy', reg.id));
  }
};

const selectedEventId = ref(props.selectedEvent?.id || (props.events.length > 0 ? props.events[0].id : ''));
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const type = ref(props.filters.type || '');

const applyFilters = () => {
  const targetRoute = selectedEventId.value
    ? route('admin.events.registrations', selectedEventId.value)
    : route('admin.registrations.index');

  router.get(targetRoute, {
    search: search.value || undefined,
    status: status.value || undefined,
    type: type.value || undefined,
    per_page: props.filters.per_page || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 300);
};

const clearSearch = () => {
  search.value = '';
  applyFilters();
};

const showBroadcastModal = ref(false);
const copied = ref(false);

const whatsappTemplateText = computed(() => {
  if (!props.selectedEvent) return '';
  const ev = props.selectedEvent;
  const reason = ev.reschedule_notice || 'Sehubungan dengan penyesuaian operasional dan jadwal manajemen.';
  const newDate = formatDate(ev.date);
  const time = `${ev.start_time || '10:00'} ${ev.timezone || 'WIB'}`;
  const venue = ev.venue_name || 'Showroom PT Hartono Raya Motor';
  const url = `https://events.hartonomotor-group.com/events/${ev.slug}`;

  return `*PEMBERITAHUAN PENJADWALAN ULANG ACARA (RESCHEDULE)*\nKepada Yth. Tamu Undangan ${ev.title},\n\nKami dari panitia Hartono Raya Motor / Hartono Group menginformasikan bahwa sehubungan dengan:\n"${reason}"\n\nAcara resmi dijadwalkan ulang menjadi:\n📅 Tanggal Baru: ${newDate}\n⏰ Waktu: ${time}\n📍 Lokasi: ${venue}\n\n*PENTING:* E-Tiket & Kode QR registrasi yang telah Anda terima tetap SAH dan dapat langsung digunakan untuk check-in pada hari acara.\n\nDetail Acara & E-Tiket:\n${url}\n\nTerima kasih atas pengertian dan kerja sama Anda.\nSalam hangat,\n*Panitia Hartono Group Events*`;
});

const copyWhatsAppTemplate = async () => {
  try {
    await navigator.clipboard.writeText(whatsappTemplateText.value);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 3000);
  } catch (e) {}
};
</script>
