<template>
  <AdminLayout :hide-sidebar="isKioskMode">
    <Head title="Live QR Check-In Scanner" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Header Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700">
            Reception Desk
          </span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Live On-Site QR Scanner
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Real-time attendee check-in and QR e-ticket verification kiosk.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- Event Day Status Badge -->
          <div
            v-if="selectedEvent"
            :class="[
              'px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 border shadow-sm',
              eventDateStatus.isToday
                ? 'bg-teal-950/80 light:bg-teal-50 text-teal-300 light:text-teal-800 border-teal-500/50'
                : eventDateStatus.isUpcoming
                ? 'bg-amber-950/80 light:bg-amber-50 text-amber-300 light:text-amber-800 border-amber-500/50'
                : 'bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 border-slate-700'
            ]"
          >
            <span class="w-2 h-2 rounded-full" :class="eventDateStatus.isToday ? 'bg-teal-400 animate-ping' : eventDateStatus.isUpcoming ? 'bg-amber-400' : 'bg-slate-400'"></span>
            <span>{{ eventDateStatus.label }}</span>
          </div>

          <!-- Event Selector -->
          <div class="w-full sm:w-72">
            <select
              v-model="selectedEventId"
              @change="changeEvent"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-cyan-400 font-medium"
            >
              <option v-for="ev in events" :key="ev.id" :value="ev.id">
                {{ ev.title }} ({{ formatDate(ev.date) }})
              </option>
            </select>
          </div>

          <!-- Kiosk / Hide Sidebar Mode Toggle -->
          <button
            @click="isKioskMode = !isKioskMode"
            :class="[
              'px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 border shadow-md',
              isKioskMode
                ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-cyan-950/40'
                : 'bg-slate-900 light:bg-white text-slate-300 light:text-slate-700 border-slate-700 light:border-slate-300 hover:border-cyan-500'
            ]"
            title="Toggle Full-Width Kiosk Mode (Ideal for iPads / Tablets)"
          >
            <Tablet class="w-4 h-4" />
            <span>{{ isKioskMode ? 'Exit Kiosk View' : 'Kiosk / iPad View' }}</span>
          </button>
        </div>
      </div>

      <!-- Real-Time Interactive Clickable Metrics Bar -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Total RSVPs Card -->
        <button
          @click="openAttendeeModal('all')"
          class="glass-card rounded-2xl p-4 sm:p-5 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white text-left transition-all hover:scale-[1.02] hover:border-teal-500/50 hover:shadow-lg group relative overflow-hidden"
        >
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-600 font-semibold uppercase">
            <span>Total RSVPs</span>
            <Users class="w-4 h-4 text-slate-400 light:text-slate-500 group-hover:text-teal-400 transition-colors" />
          </div>
          <span class="block text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-2">
            {{ stats.total }}
          </span>
          <div class="mt-2 flex items-center justify-between text-[0.65rem] text-teal-400 light:text-teal-700 font-medium">
            <span>View All Attendees &rarr;</span>
          </div>
        </button>

        <!-- Checked In Card -->
        <button
          @click="openAttendeeModal('attended')"
          class="glass-card rounded-2xl p-4 sm:p-5 border border-teal-500/40 light:border-teal-300 bg-teal-950/30 light:bg-teal-50 text-left transition-all hover:scale-[1.02] hover:shadow-lg group relative overflow-hidden"
        >
          <div class="flex items-center justify-between text-xs text-teal-400 light:text-teal-700 font-semibold uppercase">
            <span>Checked In</span>
            <CheckCircle2 class="w-4 h-4 text-teal-400 light:text-teal-600" />
          </div>
          <span class="block text-2xl sm:text-3xl font-extrabold text-teal-300 light:text-teal-700 font-heading mt-2">
            {{ stats.attended }}
          </span>
          <div class="mt-2 flex items-center justify-between text-[0.65rem] text-teal-400 light:text-teal-800 font-medium">
            <span>View Attended List &rarr;</span>
          </div>
        </button>

        <!-- Remaining Card -->
        <button
          @click="openAttendeeModal('remaining')"
          class="glass-card rounded-2xl p-4 sm:p-5 border border-amber-500/40 light:border-amber-300 bg-amber-950/20 light:bg-amber-50 text-left transition-all hover:scale-[1.02] hover:shadow-lg group relative overflow-hidden"
        >
          <div class="flex items-center justify-between text-xs text-amber-400 light:text-amber-700 font-semibold uppercase">
            <span>Remaining</span>
            <Clock class="w-4 h-4 text-amber-400 light:text-amber-600" />
          </div>
          <span class="block text-2xl sm:text-3xl font-extrabold text-amber-300 light:text-amber-700 font-heading mt-2">
            {{ stats.remaining }}
          </span>
          <div class="mt-2 flex items-center justify-between text-[0.65rem] text-amber-400 light:text-amber-800 font-medium">
            <span>View Remaining &rarr;</span>
          </div>
        </button>

        <!-- VIP Guests Card -->
        <button
          @click="openAttendeeModal('vip')"
          class="glass-card rounded-2xl p-4 sm:p-5 border border-cyan-500/40 light:border-cyan-300 bg-cyan-950/20 light:bg-cyan-50 text-left transition-all hover:scale-[1.02] hover:shadow-lg group relative overflow-hidden"
        >
          <div class="flex items-center justify-between text-xs text-cyan-400 light:text-cyan-700 font-semibold uppercase">
            <span>VIP Guests</span>
            <Crown class="w-4 h-4 text-cyan-400 light:text-cyan-600" />
          </div>
          <span class="block text-2xl sm:text-3xl font-extrabold text-cyan-300 light:text-cyan-700 font-heading mt-2">
            {{ stats.vip_count }}
          </span>
          <div class="mt-2 flex items-center justify-between text-[0.65rem] text-cyan-400 light:text-cyan-800 font-medium">
            <span>View VIP List &rarr;</span>
          </div>
        </button>
      </div>

      <!-- Scanner & Result Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        <!-- Scanner & Manual Code Input -->
        <div class="lg:col-span-7 space-y-6">
          <div class="glass-card rounded-3xl p-6 border border-cyan-500/40 light:border-cyan-300/80 bg-slate-900/60 light:bg-white shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-bold text-white light:text-slate-900 font-heading flex items-center gap-2">
                <Camera class="w-4 h-4 text-cyan-400 light:text-cyan-600" />
                <span>Camera QR Code Reader</span>
              </h3>
              <button
                @click="toggleCamera"
                :class="[
                  'px-4 py-1.5 rounded-xl text-xs font-bold transition-all shadow-md',
                  isCameraActive
                    ? 'bg-rose-600 text-white hover:bg-rose-500'
                    : 'bg-cyan-500 text-slate-950 hover:bg-cyan-400'
                ]"
              >
                {{ isCameraActive ? 'Stop Camera' : 'Start Camera' }}
              </button>
            </div>

            <!-- Scanner Box -->
            <div id="qr-reader" class="rounded-2xl overflow-hidden bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 min-h-[260px] flex items-center justify-center text-slate-400 light:text-slate-600 text-xs text-center p-4">
              <span v-if="!isCameraActive">Click 'Start Camera' or search / type code below to check in</span>
            </div>

            <!-- Manual Search / Code Entry Form -->
            <div class="pt-3 border-t border-slate-800 light:border-slate-200">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700 block mb-1">
                Manual Code / Phone / Name Check-In
              </label>
              <div class="flex items-center gap-2">
                <input
                  v-model="manualCode"
                  @keyup.enter="handleManualCheckIn"
                  type="text"
                  placeholder="e.g. HRM-VIP-2026 or guest phone number..."
                  class="flex-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-cyan-400 font-mono"
                />
                <button
                  @click="handleManualCheckIn"
                  :disabled="!manualCode || isScanning"
                  class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-md transition-all disabled:opacity-50"
                >
                  {{ isScanning ? 'Checking...' : 'Check-In' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Scan Result / Attendee Verification Card -->
        <div class="lg:col-span-5 space-y-6">
          <div class="glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 min-h-[380px] flex flex-col justify-between shadow-xl">
            <h3 class="text-sm font-bold text-white light:text-slate-900 font-heading flex items-center gap-2 border-b border-slate-800 light:border-slate-200 pb-3">
              <UserCheck class="w-4 h-4 text-teal-400 light:text-teal-600" />
              <span>Attendee Verification Desk</span>
            </h3>

            <!-- Result Card -->
            <div v-if="scanResult" class="space-y-4 my-auto">
              <div
                :class="[
                  'p-4 rounded-2xl border text-xs font-bold flex items-center gap-2 shadow-md',
                  scanResult.already_checked_in
                    ? 'bg-amber-950/80 light:bg-amber-50 border-amber-500/60 light:border-amber-300 text-amber-300 light:text-amber-900'
                    : scanResult.is_early
                    ? 'bg-amber-950/80 light:bg-amber-50 border-amber-500/60 light:border-amber-300 text-amber-300 light:text-amber-900'
                    : scanResult.success
                    ? 'bg-teal-950/80 light:bg-teal-50 border-teal-500/60 light:border-teal-300 text-teal-300 light:text-teal-900'
                    : 'bg-rose-950/80 light:bg-rose-50 border-rose-500/60 light:border-rose-300 text-rose-300 light:text-rose-900'
                ]"
              >
                <CheckCircle2 v-if="scanResult.success" class="w-5 h-5 shrink-0" />
                <AlertTriangle v-else-if="scanResult.is_early" class="w-5 h-5 shrink-0 text-amber-400" />
                <AlertCircle v-else class="w-5 h-5 shrink-0" />
                <span>{{ scanResult.message }}</span>
              </div>

              <!-- Staff Override Button for Early / Rehearsal Check-In -->
              <div v-if="scanResult.is_early && lastAttemptedCode" class="p-3 bg-amber-950/30 light:bg-amber-50 rounded-2xl border border-amber-800/40 light:border-amber-200 text-center space-y-2">
                <p class="text-[0.7rem] text-amber-300 light:text-amber-900 font-medium">
                  Need to check in ahead of time for rehearsal, VIP briefing, or early crew access?
                </p>
                <button
                  type="button"
                  @click="processCode(lastAttemptedCode, true)"
                  class="w-full py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all shadow-md flex items-center justify-center gap-1.5"
                >
                  <ShieldAlert class="w-4 h-4" />
                  <span>Authorize Staff Early Check-In</span>
                </button>
              </div>

              <!-- Guest Card Details -->
              <div v-if="scanResult.registration" class="p-5 rounded-2xl bg-slate-950/80 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-mono text-cyan-400 light:text-cyan-700 font-bold">
                    {{ scanResult.registration.registration_code }}
                  </span>
                  <span
                    :class="[
                      'px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase',
                      scanResult.registration.attendee_type === 'vip'
                        ? 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300'
                        : 'bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700'
                    ]"
                  >
                    {{ scanResult.registration.attendee_type.replace('_', ' ') }}
                  </span>
                </div>

                <div class="text-lg font-bold text-white light:text-slate-900 font-heading">
                  {{ scanResult.registration.full_name }}
                </div>
                <div class="text-xs text-slate-400 light:text-slate-500">
                  {{ scanResult.registration.company || 'Private Attendee' }}
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-slate-800 light:border-slate-200 text-slate-300 light:text-slate-700">
                  <div><strong>Party Size:</strong> {{ scanResult.registration.num_attendees }} Person(s)</div>
                  <div><strong>Phone:</strong> {{ scanResult.registration.phone }}</div>
                </div>

                <div v-if="scanResult.registration.vehicle_model" class="p-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-800 light:border-slate-200 text-xs text-teal-300 light:text-teal-700 font-medium">
                  🚘 {{ scanResult.registration.vehicle_model }}
                  <span v-if="scanResult.registration.license_plate">[{{ scanResult.registration.license_plate }}]</span>
                </div>
              </div>
            </div>

            <!-- Idle State -->
            <div v-else class="text-center py-8 space-y-2 text-slate-400 light:text-slate-500 my-auto">
              <QrCode class="w-12 h-12 mx-auto text-slate-500 light:text-slate-400 animate-pulse" />
              <div class="text-xs font-semibold text-slate-300 light:text-slate-700">Ready for QR Scanning</div>
              <p class="text-[0.75rem]">Point guest E-Ticket QR code to camera or search attendee by name/phone.</p>
            </div>

            <!-- Recent Check-ins Strip -->
            <div class="border-t border-slate-800 light:border-slate-200 pt-3 text-[0.7rem] text-slate-400 light:text-slate-600">
              <div class="font-semibold text-slate-300 light:text-slate-700 mb-1.5 flex items-center justify-between">
                <span>Recent Live Check-Ins:</span>
                <span class="text-[0.65rem] text-teal-400 light:text-teal-700">{{ recentCheckIns.length }} logged</span>
              </div>
              <div class="flex flex-wrap gap-1.5">
                <span
                  v-for="rc in recentCheckIns.slice(0, 6)"
                  :key="rc.id"
                  class="px-2.5 py-1 rounded-lg bg-slate-950 light:bg-slate-100 text-teal-300 light:text-teal-800 border border-slate-800 light:border-slate-300 font-medium"
                >
                  {{ rc.full_name }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendee List Inspection Modal (Triggered by Clicking Stat Cards) -->
      <div v-if="showAttendeeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-4xl w-full border border-teal-500/40 light:border-teal-300 bg-[#091017] light:bg-white text-slate-100 light:text-slate-900 shadow-2xl relative flex flex-col max-h-[90vh]">
          <!-- Modal Header -->
          <div class="flex items-center justify-between pb-4 border-b border-slate-800 light:border-slate-200">
            <div>
              <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Event Attendance Manifest</span>
              <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading">
                Attendee Directory & Live Status
              </h3>
            </div>
            <button
              @click="showAttendeeModal = false"
              class="text-slate-400 hover:text-white light:hover:text-black p-2 rounded-xl hover:bg-slate-800 light:hover:bg-slate-100"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Modal Filter Tabs & Search Bar -->
          <div class="py-4 space-y-3 border-b border-slate-800 light:border-slate-200">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <!-- Tabs -->
              <div class="flex flex-wrap gap-1.5 p-1 bg-slate-950 light:bg-slate-100 rounded-xl border border-slate-800 light:border-slate-200 text-xs font-bold">
                <button
                  @click="activeModalTab = 'all'"
                  :class="[
                    'px-3 py-1.5 rounded-lg transition-all',
                    activeModalTab === 'all' ? 'bg-teal-500 text-slate-950' : 'text-slate-400 light:text-slate-600 hover:text-white'
                  ]"
                >
                  All RSVPs ({{ stats.total }})
                </button>
                <button
                  @click="activeModalTab = 'attended'"
                  :class="[
                    'px-3 py-1.5 rounded-lg transition-all',
                    activeModalTab === 'attended' ? 'bg-teal-500 text-slate-950' : 'text-slate-400 light:text-slate-600 hover:text-white'
                  ]"
                >
                  Checked In ({{ stats.attended }})
                </button>
                <button
                  @click="activeModalTab = 'remaining'"
                  :class="[
                    'px-3 py-1.5 rounded-lg transition-all',
                    activeModalTab === 'remaining' ? 'bg-amber-400 text-slate-950' : 'text-slate-400 light:text-slate-600 hover:text-white'
                  ]"
                >
                  Remaining ({{ stats.remaining }})
                </button>
                <button
                  @click="activeModalTab = 'vip'"
                  :class="[
                    'px-3 py-1.5 rounded-lg transition-all',
                    activeModalTab === 'vip' ? 'bg-cyan-400 text-slate-950' : 'text-slate-400 light:text-slate-600 hover:text-white'
                  ]"
                >
                  VIPs ({{ stats.vip_count }})
                </button>
              </div>

              <!-- Search Input inside Modal -->
              <div class="w-full sm:w-64 relative">
                <Search class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
                <input
                  v-model="modalSearchQuery"
                  type="text"
                  placeholder="Search name, phone, code..."
                  class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 light:bg-slate-50 border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                />
              </div>
            </div>
          </div>

          <!-- Attendees Table / List -->
          <div class="flex-1 overflow-y-auto py-2">
            <div v-if="filteredModalAttendees.length === 0" class="py-12 text-center text-slate-400 text-xs">
              No attendees found matching this filter.
            </div>

            <div v-else class="divide-y divide-slate-800/60 light:divide-slate-200">
              <div
                v-for="att in filteredModalAttendees"
                :key="att.id"
                class="py-3 px-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-900/40 light:hover:bg-slate-50 rounded-xl transition-colors"
              >
                <div class="space-y-0.5">
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-sm text-white light:text-slate-900 font-heading">
                      {{ att.full_name }}
                    </span>
                    <span
                      v-if="att.attendee_type === 'vip'"
                      class="px-1.5 py-0.5 rounded bg-cyan-950 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 font-mono text-[0.65rem] font-bold"
                    >
                      VIP
                    </span>
                    <span class="text-xs font-mono text-slate-400">
                      {{ att.registration_code }}
                    </span>
                  </div>

                  <div class="text-xs text-slate-400 light:text-slate-600 flex items-center gap-3">
                    <span v-if="att.company">🏢 {{ att.company }}</span>
                    <span v-if="att.phone">📞 {{ att.phone }}</span>
                    <span>👥 Party: {{ att.num_attendees }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                  <!-- Attendance Status -->
                  <span
                    v-if="att.checked_in_at"
                    class="px-2.5 py-1 rounded-full text-xs font-bold bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300 flex items-center gap-1.5"
                  >
                    <CheckCircle2 class="w-3.5 h-3.5 text-teal-400" />
                    <span>Checked In</span>
                  </span>

                  <!-- One-Click Instant Check-In for Remaining Guests -->
                  <button
                    v-else
                    @click="checkInFromModal(att.registration_code)"
                    class="px-3 py-1.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-md transition-all flex items-center gap-1.5"
                  >
                    <UserCheck class="w-3.5 h-3.5" />
                    <span>Check-In Now</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="pt-4 border-t border-slate-800 light:border-slate-200 flex items-center justify-between text-xs text-slate-400">
            <span>Showing {{ filteredModalAttendees.length }} of {{ localRegistrations.length }} registrations</span>
            <button
              @click="showAttendeeModal = false"
              class="px-4 py-2 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-200 light:text-slate-800 font-bold hover:bg-slate-700"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { formatDate } from '../../../Utils/date';
import axios from 'axios';
import { Html5Qrcode } from 'html5-qrcode';
import {
  Camera,
  UserCheck,
  CheckCircle2,
  AlertCircle,
  AlertTriangle,
  ShieldAlert,
  QrCode,
  Users,
  Clock,
  Crown,
  Search,
  Tablet,
  X
} from 'lucide-vue-next';

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
  selectedEvent: {
    type: Object,
    default: null,
  },
  stats: {
    type: Object,
    default: () => ({ total: 0, attended: 0, remaining: 0, vip_count: 0 }),
  },
  recentCheckIns: {
    type: Array,
    default: () => [],
  },
  registrations: {
    type: Array,
    default: () => [],
  },
});

const isCameraActive = ref(false);
const isScanning = ref(false);
const manualCode = ref('');
const scanResult = ref(null);
const lastAttemptedCode = ref('');
const isKioskMode = ref(false);
const showAttendeeModal = ref(false);
const activeModalTab = ref('all');
const modalSearchQuery = ref('');
const localRegistrations = ref([...props.registrations]);
let html5QrCode = null;

const selectedEventId = ref(props.selectedEvent?.id || props.events[0]?.id || null);

const eventDateStatus = computed(() => {
  if (!props.selectedEvent?.date) return { isToday: false, isUpcoming: false, isPast: false, label: '' };
  const rawDate = String(props.selectedEvent.date).split('T')[0];
  const todayStr = new Date().toISOString().split('T')[0];

  if (rawDate === todayStr) {
    return { isToday: true, isUpcoming: false, isPast: false, label: 'Live Check-in Open (Event Day)' };
  } else if (rawDate > todayStr) {
    return { isToday: false, isUpcoming: true, isPast: false, label: `Scheduled: ${formatDate(props.selectedEvent.date)}` };
  } else {
    return { isToday: false, isUpcoming: false, isPast: true, label: `Past Event (${formatDate(props.selectedEvent.date)})` };
  }
});

const openAttendeeModal = (tab = 'all') => {
  activeModalTab.value = tab;
  modalSearchQuery.value = '';
  showAttendeeModal.value = true;
};

const filteredModalAttendees = computed(() => {
  let list = [...localRegistrations.value];

  // Filter by tab
  if (activeModalTab.value === 'attended') {
    list = list.filter(r => !!r.checked_in_at);
  } else if (activeModalTab.value === 'remaining') {
    list = list.filter(r => !r.checked_in_at);
  } else if (activeModalTab.value === 'vip') {
    list = list.filter(r => r.attendee_type === 'vip');
  }

  // Filter by search query
  if (modalSearchQuery.value.trim()) {
    const q = modalSearchQuery.value.toLowerCase().trim();
    list = list.filter(r =>
      (r.full_name && r.full_name.toLowerCase().includes(q)) ||
      (r.registration_code && r.registration_code.toLowerCase().includes(q)) ||
      (r.phone && r.phone.toLowerCase().includes(q)) ||
      (r.company && r.company.toLowerCase().includes(q))
    );
  }

  return list;
});

const changeEvent = () => {
  router.get(route('admin.events.check-in', selectedEventId.value));
};

const playBeep = (isSuccess) => {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = isSuccess ? 'sine' : 'sawtooth';
    osc.frequency.setValueAtTime(isSuccess ? 880 : 300, ctx.currentTime);
    gain.gain.setValueAtTime(0.2, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + 0.3);
  } catch (e) {}
};

const processCode = async (code, override = false) => {
  if (!props.selectedEvent?.id || !code || isScanning.value) return;
  isScanning.value = true;
  lastAttemptedCode.value = code;

  try {
    const response = await axios.post(route('admin.api.check-in.scan'), {
      event_id: props.selectedEvent.id,
      code: code,
      override_early: override,
    });

    scanResult.value = response.data;
    playBeep(true);

    if (!response.data.already_checked_in) {
      props.stats.attended += 1;
      props.stats.remaining = Math.max(0, props.stats.remaining - 1);

      // Update local registration record
      const reg = localRegistrations.value.find(r => r.registration_code === code || r.phone === code || r.email === code);
      if (reg) {
        reg.checked_in_at = new Date().toISOString();
        props.recentCheckIns.unshift(reg);
      }
    }

    manualCode.value = '';
  } catch (err) {
    playBeep(false);
    const errData = err.response?.data;
    scanResult.value = {
      success: false,
      is_early: errData?.is_early || false,
      event_date: errData?.event_date || '',
      message: errData?.message || `Code '${code}' not found.`,
      registration: null,
    };
  } finally {
    isScanning.value = false;
  }
};

const handleManualCheckIn = () => {
  if (manualCode.value) {
    processCode(manualCode.value.trim());
  }
};

const checkInFromModal = (code) => {
  processCode(code);
};

const toggleCamera = async () => {
  if (isCameraActive.value) {
    if (html5QrCode) {
      await html5QrCode.stop();
      isCameraActive.value = false;
    }
  } else {
    try {
      html5QrCode = new Html5Qrcode('qr-reader');
      await html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 220, height: 220 } },
        (decodedText) => {
          processCode(decodedText);
        },
        () => {}
      );
      isCameraActive.value = true;
    } catch (e) {
      alert('Camera permission denied or camera not found.');
    }
  }
};

onUnmounted(async () => {
  if (html5QrCode && isCameraActive.value) {
    try {
      await html5QrCode.stop();
    } catch (e) {}
  }
});
</script>
