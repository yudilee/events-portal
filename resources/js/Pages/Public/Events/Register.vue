<template>
  <PublicLayout>
    <Head :title="`RSVP — ${event.title}`" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <Link :href="route('public.events.index')" class="hover:text-teal-400">Events</Link>
        <span>/</span>
        <Link :href="route('public.events.show', event.slug)" class="hover:text-teal-400 line-clamp-1">{{ event.title }}</Link>
        <span>/</span>
        <span class="text-slate-200">Registration</span>
      </div>

      <div class="glass-card rounded-3xl p-6 sm:p-10 border border-teal-500/30 shadow-2xl relative overflow-hidden">
        <!-- Top Gradient Stripe -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-teal-500 via-cyan-400 to-rose-500"></div>

        <div class="mb-8 space-y-2">
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400">Online RSVP Desk</span>
          <h1 class="text-2xl sm:text-4xl font-extrabold text-white font-heading">
            Register for Event
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 font-light">
            Fill in your details below to confirm attendance and generate individual digital E-Tickets with QR check-in passes for each attendee.
          </p>
        </div>

        <!-- Rescheduled Announcement Notice on Register Page -->
        <div
          v-if="event.status === 'rescheduled' || event.is_date_tba"
          class="mb-6 p-4 rounded-2xl bg-cyan-950/80 border border-cyan-400/50 shadow-lg text-xs space-y-1.5"
        >
          <div class="flex items-center gap-2 font-bold text-cyan-300">
            <span class="px-2 py-0.5 rounded-full bg-cyan-400 text-slate-950 text-[0.65rem] font-black uppercase">
              Rescheduled Event
            </span>
            <span>Jadwal Acara Sedang Disesuaikan (To Be Announced)</span>
          </div>
          <p class="text-slate-300 text-[0.72rem] leading-relaxed">
            Acara ini sedang dalam penjadwalan ulang. Pendaftaran tetap dibuka dan E-Tiket yang Anda peroleh akan <strong>otomatis berlaku penuh</strong> untuk tanggal baru saat diumumkan.
          </p>
        </div>

        <!-- Event Snapshot Pill -->
        <div class="glass-card bg-slate-950/60 light:bg-slate-50 rounded-2xl p-4 mb-8 border border-slate-800 light:border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-300 light:text-slate-700">
          <div>
            <div class="font-bold text-white light:text-slate-900 text-sm">{{ event.title }}</div>
            <div v-if="event.is_date_tba" class="text-cyan-300 light:text-cyan-800 font-mono font-bold mt-0.5 flex items-center gap-1.5">
              <span class="line-through text-slate-400 light:text-slate-500 font-normal">Semula: {{ formatDate(event.original_date || event.date) }}</span>
              <span>• ⏳ JADWAL BARU: TO BE ANNOUNCED SHORTLY</span>
            </div>
            <div v-else-if="event.status === 'rescheduled'" class="text-amber-300 light:text-amber-800 font-mono font-bold mt-0.5">
              <span class="line-through text-slate-400 font-normal">Semula: {{ formatDate(event.original_date) }}</span>
              <span> • 📅 Jadwal Baru: {{ formatDate(event.date) }} • ⏰ {{ event.start_time }} {{ event.timezone }}</span>
            </div>
            <div v-else class="text-slate-400 light:text-slate-500 mt-0.5">
              📅 {{ formatDate(event.date) }} • ⏰ {{ event.start_time }} {{ event.timezone }} • 👔 {{ event.dress_code }}
            </div>
          </div>
          <div class="text-teal-400 light:text-teal-700 font-semibold shrink-0">
            📍 {{ event.venue_name }}
          </div>
        </div>

        <!-- Registration Form -->
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Full Name (Primary Attendee) -->
            <div class="space-y-1.5 sm:col-span-2">
              <div class="flex items-center justify-between">
                <label class="text-xs font-semibold text-slate-200">
                  Primary Attendee Full Name *
                </label>
                <span v-if="form.num_attendees > 1" class="text-[0.65rem] text-teal-400 font-semibold uppercase">
                  Attendee #1 (Contact Person)
                </span>
              </div>
              <input
                v-model="form.full_name"
                type="text"
                required
                placeholder="e.g. Hendra Wijaya"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.full_name" class="text-rose-400 text-xs mt-1">{{ form.errors.full_name }}</div>
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">Email Address *</label>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="name@company.com"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.email" class="text-rose-400 text-xs mt-1">{{ form.errors.email }}</div>
            </div>

            <!-- Phone -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">WhatsApp / Phone Number *</label>
              <input
                v-model="form.phone"
                type="tel"
                required
                placeholder="+62 812-3456-7890"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.phone" class="text-rose-400 text-xs mt-1">{{ form.errors.phone }}</div>
            </div>

            <!-- Company -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">Company / Organization</label>
              <input
                v-model="form.company"
                type="text"
                placeholder="e.g. PT Nusantara Makmur"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- Attendee Type -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">Attendee Category *</label>
              <select
                v-model="form.attendee_type"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              >
                <option value="general_guest">Customer / General Guest</option>
                <option value="vip">VIP Mercedes-Benz Owner</option>
                <option value="media">News Agency / Press Journalist</option>
                <option value="partner">Corporate / Fleet Partner</option>
              </select>
            </div>

            <!-- Media Outlet Name if Media -->
            <div v-if="form.attendee_type === 'media'" class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-cyan-300">Media Agency / Publication Name *</label>
              <input
                v-model="form.media_outlet_name"
                type="text"
                placeholder="e.g. Kompas Otomotif / AutonetMagz"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-cyan-500/50 text-sm text-white focus:outline-none focus:border-cyan-400"
              />
            </div>

            <!-- Number of Attendees Selection -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200">Total Attending Persons *</label>
              <select
                v-model="form.num_attendees"
                @change="updateCompanions"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              >
                <option :value="1">1 Person (Individual Registration)</option>
                <option :value="2">2 Persons (Primary + 1 Companion)</option>
                <option :value="3">3 Persons (Primary + 2 Companions)</option>
                <option :value="4">4 Persons (Primary + 3 Companions)</option>
                <option :value="5">5 Persons (Primary + 4 Companions)</option>
              </select>
              <p class="text-[0.7rem] text-slate-400">
                Each attendee will receive their own individual E-Ticket and QR Pass for doorprize lottery and check-in eligibility.
              </p>
            </div>

            <!-- Companion Names Dynamic List -->
            <div
              v-if="form.num_attendees > 1"
              class="sm:col-span-2 p-5 rounded-2xl bg-teal-950/30 border border-teal-500/30 space-y-4"
            >
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-teal-300 flex items-center gap-1.5">
                  <Users class="w-4 h-4 text-teal-400" />
                  <span>Companion Attendee Names</span>
                </span>
                <span class="text-[0.65rem] text-teal-400 font-mono">
                  {{ form.num_attendees - 1 }} Additional Person(s)
                </span>
              </div>

              <div class="space-y-3">
                <div
                  v-for="(comp, idx) in form.companions"
                  :key="idx"
                  class="space-y-1"
                >
                  <label class="text-xs font-medium text-slate-300">
                    Attendee #{{ idx + 2 }} Full Name *
                  </label>
                  <input
                    v-model="comp.full_name"
                    type="text"
                    required
                    :placeholder="`e.g. Companion attendee name (${idx + 2})`"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
                  />
                </div>
              </div>
            </div>

            <!-- Vehicle Model -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">Vehicle Model (Optional)</label>
              <input
                v-model="form.vehicle_model"
                type="text"
                placeholder="e.g. Mercedes-Benz C 300 / Actros"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- License Plate -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200">License Plate (For VIP Parking)</label>
              <input
                v-model="form.license_plate"
                type="text"
                placeholder="e.g. B 1956 HRM"
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- Special Notes / Dietary -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200">Special Notes or Dietary Preferences</label>
              <textarea
                v-model="form.dietary_notes"
                rows="2"
                placeholder="e.g. Vegetarian luncheon preference, interest in HR Auto Studio PPF coating..."
                class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>
          </div>

          <!-- Submit Action -->
          <div class="pt-4 border-t border-slate-800">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full py-4 rounded-xl font-bold text-sm bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-xl shadow-teal-950/60 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
            >
              <Ticket class="w-4 h-4" />
              <span>
                {{ form.processing ? 'Generating E-Tickets...' : `Confirm RSVP & Generate ${form.num_attendees} E-Ticket(s)` }}
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import { formatDate } from '../../../Utils/date';
import { Ticket, Users } from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
});

const page = usePage();
const authUser = page.props.auth?.user;

const form = useForm({
  full_name: authUser?.name || '',
  email: authUser?.email || '',
  phone: authUser?.phone || '',
  company: authUser?.company || '',
  attendee_type: authUser?.role === 'news_agency' ? 'media' : (authUser?.role === 'member' ? 'vip' : 'general_guest'),
  media_outlet_name: authUser?.company || '',
  num_attendees: 1,
  companions: [],
  vehicle_model: '',
  license_plate: '',
  dietary_notes: '',
});

const updateCompanions = () => {
  const needed = form.num_attendees - 1;
  while (form.companions.length < needed) {
    form.companions.push({ full_name: '' });
  }
  while (form.companions.length > needed) {
    form.companions.pop();
  }
};

const submit = () => {
  form.post(route('public.events.register.store', props.event.slug));
};
</script>
