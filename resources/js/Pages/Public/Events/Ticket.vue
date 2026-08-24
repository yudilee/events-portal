<template>
  <PublicLayout>
    <Head :title="`E-Ticket — ${event.title}`" />

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 print:p-0 print:max-w-none">
      <!-- Success Header Alert (Hidden during Print) -->
      <div class="text-center mb-8 space-y-2 print:hidden">
        <div class="w-12 h-12 rounded-full bg-teal-500/20 border border-teal-500/40 text-teal-400 flex items-center justify-center mx-auto mb-3">
          <CheckCircle2 class="w-6 h-6" />
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400">RSVP Confirmed</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading">
          Your Event E-Ticket & Pass
        </h1>
        <p class="text-xs text-slate-400 light:text-slate-600">
          Present this QR pass to the registration desk upon your arrival.
        </p>
      </div>

      <!-- Multiple Attendees Switcher Tabs (Hidden during Print) -->
      <div v-if="ticketsList.length > 1" class="mb-6 space-y-2 print:hidden">
        <div class="flex items-center justify-between text-xs text-slate-300 light:text-slate-700">
          <span class="font-bold flex items-center gap-1.5 text-teal-400 light:text-teal-700">
            <Users class="w-4 h-4" />
            <span>Group E-Tickets ({{ ticketsList.length }} Passes)</span>
          </span>
          <span class="text-[0.65rem] text-slate-400">Click to switch attendee</span>
        </div>

        <div class="flex flex-wrap gap-2">
          <button
            v-for="(t, index) in ticketsList"
            :key="t.id"
            @click="activeTicketIndex = index"
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 border',
              activeTicketIndex === index
                ? 'bg-teal-500 text-slate-950 border-teal-400 shadow-lg shadow-teal-950/60'
                : 'bg-slate-900/90 light:bg-slate-100 text-slate-300 light:text-slate-700 border-slate-700 light:border-slate-300 hover:bg-slate-800'
            ]"
          >
            <span class="w-4 h-4 rounded-full bg-black/20 flex items-center justify-center text-[0.65rem]">
              {{ index + 1 }}
            </span>
            <span class="line-clamp-1">{{ t.full_name }}</span>
            <span class="text-[0.65rem] opacity-75 font-mono">({{ t.registration_code }})</span>
          </button>
        </div>
      </div>

      <!-- Active Ticket Card (Luxury Boarding Pass Aesthetic) -->
      <div
        id="ticket-pass-card"
        class="ticket-pass-container rounded-3xl overflow-hidden border border-teal-500/40 shadow-2xl relative bg-slate-950 text-white print:border-2 print:border-black print:bg-white print:text-black print:shadow-none print:m-0"
      >
        <!-- Top Header Banner with Metallic Gradient -->
        <div class="p-6 sm:p-7 bg-gradient-to-r from-teal-900 via-slate-900 to-cyan-950 border-b border-teal-500/20 relative overflow-hidden print:bg-none print:border-b-2 print:border-black">
          <!-- Background Subtle Watermark -->
          <div class="absolute -right-6 -bottom-6 opacity-10 pointer-events-none print:hidden">
            <svg class="w-40 h-40 text-teal-400" viewBox="0 0 100 100" fill="currentColor">
              <path d="M50 0 C22.4 0 0 22.4 0 50 C0 77.6 22.4 100 50 100 C77.6 100 100 77.6 100 50 C100 22.4 77.6 0 50 0 Z M50 90 C27.9 90 10 72.1 10 50 C10 27.9 27.9 10 50 10 C72.1 10 90 27.9 90 50 C90 72.1 72.1 90 50 90 Z"/>
            </svg>
          </div>

          <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping print:hidden"></span>
              <span class="text-[0.68rem] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full bg-gradient-to-r from-teal-500 to-cyan-400 text-slate-950 shadow-sm print:bg-none print:border print:border-black print:text-black">
                ★ {{ currentTicket.attendee_type.replace('_', ' ') }} PASS
              </span>
            </div>

            <div class="text-right">
              <span class="text-[0.65rem] text-slate-400 print:text-slate-600 block uppercase tracking-wider">Ticket / Doorprize ID</span>
              <span class="text-sm sm:text-base font-mono font-black text-teal-300 print:text-black tracking-wider">
                #{{ currentTicket.registration_code }}
              </span>
            </div>
          </div>

          <h2 class="text-lg sm:text-xl font-black text-white print:text-black font-heading leading-snug relative z-10">
            {{ event.title }}
          </h2>
          <p class="text-xs text-slate-300 print:text-slate-700 mt-1 font-medium relative z-10 flex items-center gap-1.5">
            <MapPin class="w-3.5 h-3.5 text-teal-400 print:text-black shrink-0" />
            <span>{{ event.venue_name }}</span>
          </p>
        </div>

        <!-- Perforated Tear Notch Line -->
        <div class="relative flex items-center justify-between py-1 bg-slate-950 print:bg-white">
          <div class="w-5 h-8 bg-[#070B0E] light:bg-[#F8FAFC] rounded-r-full border-r border-teal-500/30 print:bg-white print:border-black -ml-0.5"></div>
          <div class="flex-1 border-b-2 border-dashed border-slate-800 print:border-black mx-2"></div>
          <div class="w-5 h-8 bg-[#070B0E] light:bg-[#F8FAFC] rounded-l-full border-l border-teal-500/30 print:bg-white print:border-black -mr-0.5"></div>
        </div>

        <!-- Ticket Body Details -->
        <div class="p-6 sm:p-8 space-y-6 bg-slate-950/90 print:bg-white">
          <!-- Rescheduled Pass Notice Banner (If event was rescheduled) -->
          <div
            v-if="event.status === 'rescheduled' || event.reschedule_notice"
            class="p-3.5 rounded-2xl bg-amber-950/80 light:bg-amber-50 border border-amber-500/60 print:border-black text-amber-200 print:text-black text-xs space-y-1"
          >
            <div class="font-bold flex items-center gap-1.5 text-amber-400 print:text-black uppercase text-[0.68rem] tracking-wider">
              <span>⚠️ PEMBERITAHUAN: JADWAL ACARA DIUNDUR / RESCHEDULED</span>
            </div>
            <p class="text-[0.7rem] text-amber-100/90 print:text-black leading-relaxed">
              Acara resmi dijadwalkan ulang menjadi <strong>{{ formatDate(event.date) }}</strong> pukul <strong>{{ event.start_time }} {{ event.timezone }}</strong>. Tiket & barcode QR di bawah ini tetap sah digunakan untuk check-in.
            </p>
          </div>

          <!-- Attendee Information Grid -->
          <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-xs">
            <div class="col-span-2 sm:col-span-1">
              <span class="text-slate-400 print:text-slate-600 uppercase text-[0.65rem] tracking-wider block font-semibold">Attendee Name</span>
              <span class="text-sm sm:text-base font-black text-white print:text-black block mt-0.5 font-heading">{{ currentTicket.full_name }}</span>
              <span v-if="currentTicket.company" class="text-slate-400 print:text-slate-600 text-[0.7rem] block mt-0.5">{{ currentTicket.company }}</span>
            </div>

            <div class="col-span-2 sm:col-span-1">
              <span class="text-slate-400 print:text-slate-600 uppercase text-[0.65rem] tracking-wider block font-semibold">Date & Schedule</span>
              <span class="text-xs sm:text-sm font-bold text-white print:text-black block mt-0.5">
                📅 {{ formatDate(event.date) }}
              </span>
              <span class="text-teal-400 print:text-slate-700 text-[0.7rem] font-mono block mt-0.5">
                ⏰ {{ event.start_time || '10:00' }} - {{ event.end_time || 'Finish' }} {{ event.timezone || 'WIB' }}
              </span>
            </div>

            <div>
              <span class="text-slate-400 print:text-slate-600 uppercase text-[0.65rem] tracking-wider block font-semibold">Dress Code</span>
              <span class="text-xs font-bold text-slate-200 print:text-black block mt-0.5">
                👔 {{ event.dress_code || 'Smart Casual' }}
              </span>
            </div>

            <div>
              <span class="text-slate-400 print:text-slate-600 uppercase text-[0.65rem] tracking-wider block font-semibold">RSVP Contact Desk</span>
              <span class="text-xs font-bold text-slate-200 print:text-black block mt-0.5">
                📞 {{ event.rsvp_phone || '087782223724' }}
              </span>
            </div>

            <div v-if="currentTicket.vehicle_model" class="col-span-2 pt-3 border-t border-slate-800/80 print:border-slate-300">
              <span class="text-slate-400 print:text-slate-600 uppercase text-[0.65rem] tracking-wider block font-semibold">Registered Vehicle</span>
              <span class="text-xs font-semibold text-slate-200 print:text-black block mt-0.5">
                🚘 {{ currentTicket.vehicle_model }}
                <span v-if="currentTicket.license_plate" class="text-teal-300 print:text-black font-mono ml-2 px-2 py-0.5 bg-slate-900 print:bg-slate-200 rounded-md border border-slate-700 print:border-slate-400">
                  {{ currentTicket.license_plate }}
                </span>
              </span>
            </div>
          </div>

          <!-- Dynamic QR Code & Check-in Verification Box -->
          <div class="border-t border-slate-800 print:border-slate-300 pt-6 flex flex-col items-center justify-center space-y-3">
            <div class="p-4 bg-white rounded-2xl shadow-xl flex items-center justify-center border-2 border-teal-500/40 print:border-black">
              <!-- Render high-res dynamic QR Code Image -->
              <img
                :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentTicket.registration_code)}`"
                :alt="`QR Code ${currentTicket.registration_code}`"
                crossorigin="anonymous"
                class="w-44 h-44 object-contain"
              />
            </div>

            <div class="text-center space-y-0.5">
              <span class="text-xs font-mono font-bold tracking-widest text-teal-400 print:text-black uppercase block">
                {{ currentTicket.registration_code }}
              </span>
              <span class="text-[0.68rem] text-slate-400 print:text-slate-600 block">
                Show this barcode at the reception desk for instant check-in
              </span>
            </div>
          </div>

          <!-- Corporate Brand Footer in Badge -->
          <div class="pt-4 border-t border-slate-800/60 print:border-slate-300 flex items-center justify-between text-[0.65rem] text-slate-400 print:text-slate-600">
            <div class="flex items-center gap-1.5 font-semibold text-slate-300 print:text-black">
              <ShieldCheck class="w-3.5 h-3.5 text-teal-400 print:text-black" />
              <span>Official Hartono Raya Motor Invitation</span>
            </div>
            <span class="font-mono">hartonomotor-group.com</span>
          </div>
        </div>

        <!-- Ticket Action Buttons Bar (Hidden during Print) -->
        <div class="p-4 bg-slate-900 border-t border-slate-800 flex flex-wrap items-center justify-between gap-2.5 print:hidden">
          <!-- Save Image Button -->
          <button
            @click="saveAsImage"
            :disabled="isGeneratingImage"
            class="flex-1 py-3 px-3 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-md shadow-teal-950/50"
            title="Download crisp PNG ticket pass to your phone or computer"
          >
            <Download class="w-4 h-4" />
            <span>{{ isGeneratingImage ? 'Saving Image...' : 'Save as Image' }}</span>
          </button>

          <!-- Print / Save PDF Button -->
          <button
            @click="printTicket"
            class="flex-1 py-3 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold flex items-center justify-center gap-1.5 transition-colors border border-slate-700"
            title="Print or Save as PDF"
          >
            <Printer class="w-4 h-4" />
            <span>Print / Save PDF</span>
          </button>

          <!-- Add to Calendar Button -->
          <a
            :href="googleCalendarUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full sm:w-auto py-3 px-4 rounded-xl bg-slate-950 hover:bg-slate-800 text-teal-400 text-xs font-bold flex items-center justify-center gap-1.5 transition-colors border border-slate-800"
            title="Add event to Google Calendar"
          >
            <CalendarPlus class="w-4 h-4" />
            <span>Add to Calendar</span>
          </a>
        </div>
      </div>

      <!-- Quick Link to Guestbook -->
      <div class="text-center mt-8 space-y-1.5 print:hidden">
        <Link
          :href="route('public.events.guestbook', event.slug)"
          class="text-xs text-teal-400 hover:text-teal-300 font-bold inline-flex items-center gap-1.5 group"
        >
          <span>Leave a greeting on the Digital Guest Book & Wall</span>
          <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
        </Link>
        <p class="text-[0.7rem] text-slate-400 light:text-slate-600">
          Signing the guestbook enters you into the Gala Live Doorprize Drawing!
        </p>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import { formatDate } from '../../../Utils/date';
import confetti from 'canvas-confetti';
import { toPng } from 'html-to-image';
import {
  Sparkles,
  Printer,
  Download,
  CalendarPlus,
  ArrowRight,
  ShieldCheck,
  CheckCircle2,
  Users,
  MapPin
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  registration: {
    type: Object,
    required: true,
  },
  groupTickets: {
    type: Array,
    default: () => [],
  },
});

const activeTicketIndex = ref(0);
const isGeneratingImage = ref(false);

const ticketsList = computed(() => {
  if (props.groupTickets && props.groupTickets.length > 0) {
    return props.groupTickets;
  }
  return [props.registration];
});

const currentTicket = computed(() => {
  return ticketsList.value[activeTicketIndex.value] || props.registration;
});

onMounted(() => {
  try {
    confetti({
      particleCount: 80,
      spread: 60,
      origin: { y: 0.6 },
      colors: ['#00B4B4', '#0E8B8B', '#06B6D4', '#FFFFFF'],
    });
  } catch (e) {}
});

const printTicket = () => {
  window.print();
};

const saveAsImage = async () => {
  const node = document.getElementById('ticket-pass-card');
  if (!node || isGeneratingImage.value) return;

  isGeneratingImage.value = true;

  try {
    // Hide buttons bar before image capture
    const dataUrl = await toPng(node, {
      cacheBust: true,
      pixelRatio: 2.5,
      filter: (child) => {
        // Exclude the bottom action buttons bar from the saved image
        return !child.classList?.contains('print:hidden');
      },
    });

    const link = document.createElement('a');
    link.download = `E-Ticket-${currentTicket.value.registration_code}-${currentTicket.value.full_name.replace(/\s+/g, '_')}.png`;
    link.href = dataUrl;
    link.click();
  } catch (err) {
    console.error('Failed to generate ticket image:', err);
    alert('Unable to generate ticket image directly. You can use Print / Save PDF instead.');
  } finally {
    isGeneratingImage.value = false;
  }
};

const googleCalendarUrl = computed(() => {
  const title = encodeURIComponent(props.event.title);
  const details = encodeURIComponent(`PT Hartono Raya Motor Event. Ticket: ${currentTicket.value.registration_code} (${currentTicket.value.full_name})`);
  const location = encodeURIComponent(props.event.venue_name);
  const rawDate = String(props.event.date || '').split('T')[0];
  const dateStr = rawDate.replace(/-/g, '');
  return `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${dateStr}T030000Z/${dateStr}T060000Z&details=${details}&location=${location}`;
});
</script>

<style>
@media print {
  body {
    background: white !important;
    color: black !important;
  }
  header, footer, nav, .print\\:hidden {
    display: none !important;
  }
  .ticket-pass-container {
    border: 2px solid #000 !important;
    box-shadow: none !important;
    background: #fff !important;
    color: #000 !important;
    page-break-inside: avoid !important;
    max-width: 580px !important;
    margin: 20px auto !important;
  }
}
</style>
