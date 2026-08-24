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

      <!-- Filters & Search -->
      <div class="glass-card rounded-2xl p-4 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white flex flex-col md:flex-row gap-4 items-center justify-between shadow-md">
        <!-- Event Switcher -->
        <div class="w-full md:w-72">
          <select
            v-model="selectedEventId"
            @change="changeEvent"
            class="w-full px-3.5 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
          >
            <option v-for="ev in events" :key="ev.id" :value="ev.id">
              {{ ev.title }} ({{ formatDate(ev.date) }})
            </option>
          </select>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:w-72">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="search"
            @input="handleSearch"
            type="text"
            placeholder="Search by name, code, vehicle..."
            class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 placeholder-slate-400 focus:outline-none focus:border-teal-400"
          />
        </div>
      </div>

      <!-- Attendees Table -->
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
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { formatDate } from '../../../Utils/date';
import { Download, QrCode, Search, Trash2 } from 'lucide-vue-next';

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

const selectedEventId = ref(props.selectedEvent?.id || '');
const search = ref(props.filters.search || '');

const changeEvent = () => {
  router.get(route('admin.events.registrations', selectedEventId.value));
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get(route('admin.registrations.index'), {
      search: search.value || undefined,
    }, {
      preserveState: true,
      replace: true,
    });
  }, 300);
};
</script>
