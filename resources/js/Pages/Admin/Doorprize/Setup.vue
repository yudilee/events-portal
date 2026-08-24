<template>
  <AdminLayout>
    <Head title="Doorprize Setup & Management" />

    <div class="space-y-6 max-w-7xl">
      <!-- Top Header & Event Selector -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400">Live Stage & Gala Entertainment</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Doorprize Setup & Prize Manager
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Configure multi-winner prizes (e.g. 1st Prize: 1 winner, 2nd Prize: 2 winners, Consolation: 5 winners) and spin the live roulette drawn from digital guestbook signers.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <div class="w-full sm:w-72">
            <select
              v-model="selectedEventId"
              @change="changeEvent"
              class="w-full px-3.5 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-medium"
            >
              <option v-for="ev in events" :key="ev.id" :value="ev.id">
                {{ ev.title }} ({{ formatDate(ev.date) }})
              </option>
            </select>
          </div>

          <Link
            v-if="selectedEvent"
            :href="route('admin.events.doorprize.roulette', selectedEvent.id)"
            class="px-5 py-2.5 rounded-xl font-bold text-xs bg-gradient-to-r from-amber-400 via-teal-400 to-cyan-400 hover:from-amber-300 hover:to-cyan-300 text-slate-950 shadow-xl shadow-teal-950/50 flex items-center gap-2 transition-all shrink-0"
          >
            <Sparkles class="w-4 h-4 animate-spin" />
            <span>Launch Live Roulette</span>
          </Link>
        </div>
      </div>

      <!-- Overview Stats Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Guestbook Pool Card -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 shadow-lg space-y-1">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Guestbook Pool</span>
            <Users class="w-4 h-4 text-teal-400" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading">
            {{ poolStats?.total_guestbook_entries || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400">Approved guest signers</div>
        </div>

        <!-- Total Defined Prizes -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 shadow-lg space-y-1">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Total Prize Items</span>
            <Trophy class="w-4 h-4 text-amber-400" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold text-amber-400 font-heading">
            {{ poolStats?.total_prizes || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400">Total winner quotas across {{ prizes.length }} tiers</div>
        </div>

        <!-- Awarded Prizes -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 shadow-lg space-y-1">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Winners Awarded</span>
            <Gift class="w-4 h-4 text-cyan-400" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold text-cyan-400 font-heading">
            {{ poolStats?.awarded_prizes || 0 }} / {{ poolStats?.total_prizes || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400">Winners drawn live</div>
        </div>

        <!-- Remaining Draw Pool -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800 light:border-slate-200 shadow-lg space-y-1">
          <div class="flex items-center justify-between text-xs text-slate-400 light:text-slate-500">
            <span>Eligible Pool</span>
            <Sparkles class="w-4 h-4 text-emerald-400" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400 font-heading">
            {{ poolStats?.remaining_pool || 0 }}
          </div>
          <div class="text-[0.7rem] text-slate-400">Available to win next</div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Prizes List Section -->
        <div class="lg:col-span-8 space-y-4">
          <div class="glass-card rounded-3xl p-6 border border-slate-800 light:border-slate-200 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
                  Pre-Defined Event Prizes & Winner Quotas
                </h3>
                <p class="text-xs text-slate-400 light:text-slate-500">
                  Prizes are spun in sequence order. Prizes with multiple winner quotas will spin repeatedly until all winner slots are filled.
                </p>
              </div>

              <div class="flex items-center gap-2">
                <button
                  v-if="hasAnyWinners && $page.props.auth?.user?.role === 'admin'"
                  @click="confirmResetAll"
                  class="px-3 py-1.5 rounded-lg text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-950/60 border border-rose-800/40 transition-colors flex items-center gap-1.5"
                >
                  <RotateCcw class="w-3.5 h-3.5" />
                  <span>Reset All Winners</span>
                </button>
              </div>
            </div>

            <!-- Empty State -->
            <div v-if="prizes.length === 0" class="py-12 text-center border border-dashed border-slate-800 rounded-2xl">
              <Trophy class="w-12 h-12 text-slate-600 mx-auto mb-2" />
              <h4 class="text-sm font-bold text-white light:text-slate-800">No prizes created yet</h4>
              <p class="text-xs text-slate-400 light:text-slate-500 mt-1 max-w-sm mx-auto">
                Use the form on the right to add your Grand Prize (1 winner), 2nd Prize (2 winners), and Consolation gifts (multiple winners).
              </p>
            </div>

            <!-- Prizes Table -->
            <div v-else class="overflow-x-auto">
              <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
                <thead class="bg-slate-950/60 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.65rem] border-b border-slate-800 light:border-slate-200">
                  <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Prize Details</th>
                    <th class="px-4 py-3">Quota</th>
                    <th class="px-4 py-3">Drawn Winners</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
                  <tr
                    v-for="(prize, index) in prizes"
                    :key="prize.id"
                    class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors"
                  >
                    <td class="px-4 py-4 font-mono font-bold text-teal-400 text-sm align-top">
                      #{{ index + 1 }}
                    </td>

                    <td class="px-4 py-4 space-y-1 align-top max-w-xs">
                      <div class="font-bold text-white light:text-slate-900 text-sm font-heading flex items-center gap-2">
                        <span>{{ prize.label }}</span>
                        <span v-if="index === 0" class="px-1.5 py-0.5 rounded bg-amber-500/20 border border-amber-500/40 text-amber-300 text-[0.65rem] font-sans">
                          Top Tier
                        </span>
                      </div>
                      <div v-if="prize.description" class="text-xs text-slate-400 light:text-slate-500 font-light leading-relaxed">
                        {{ prize.description }}
                      </div>
                    </td>

                    <td class="px-4 py-4 align-top">
                      <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 font-bold text-xs">
                        <Users class="w-3 h-3 text-teal-400" />
                        <span>{{ prize.awarded_count || (prize.winners ? prize.winners.length : 0) }} / {{ prize.quantity || 1 }}</span>
                      </div>
                    </td>

                    <td class="px-4 py-4 align-top space-y-1.5">
                      <!-- List of individual winners for this tier -->
                      <div v-if="prize.winners && prize.winners.length > 0" class="space-y-1.5">
                        <div
                          v-for="w in prize.winners"
                          :key="w.id"
                          class="flex items-center justify-between gap-2 p-2 rounded-xl bg-teal-950/40 light:bg-teal-50 border border-teal-800/40 light:border-teal-200 text-xs"
                        >
                          <div class="flex items-center gap-2 truncate">
                            <span class="w-5 h-5 rounded-full bg-teal-500 text-slate-950 flex items-center justify-center text-[0.65rem] font-bold shrink-0">
                              🏆
                            </span>
                            <div class="truncate">
                              <span class="font-bold text-white light:text-slate-900 block truncate">
                                {{ w.guest_book_entry?.guest_name || 'Guest Winner' }}
                              </span>
                              <span v-if="w.guest_book_entry?.company" class="text-[0.65rem] text-slate-400 block truncate">
                                {{ w.guest_book_entry?.company }}
                              </span>
                            </div>
                          </div>

                          <button
                            v-if="$page.props.auth?.user?.role === 'admin'"
                            @click="deleteSingleWinner(w.id)"
                            class="p-1 rounded-lg text-rose-400 hover:bg-rose-950/60 transition-colors shrink-0"
                            title="Remove this winner (Admin Only)"
                          >
                            <X class="w-3.5 h-3.5" />
                          </button>
                        </div>
                      </div>
                    </td>

                    <td class="px-4 py-4 text-right align-top">
                      <div class="flex items-center justify-end gap-1.5">
                        <!-- Reset All Winners for this Prize -->
                        <button
                          v-if="prize.winners && prize.winners.length > 0 && $page.props.auth?.user?.role === 'admin'"
                          @click="resetPrizeWinners(prize.id)"
                          class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-amber-400 hover:bg-slate-700"
                          title="Reset All Winners for this Prize (Admin Only)"
                        >
                          <RotateCcw class="w-3.5 h-3.5" />
                        </button>

                        <!-- Edit Prize -->
                        <button
                          @click="openEditModal(prize)"
                          class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-white"
                          title="Edit Prize"
                        >
                          <Edit3 class="w-3.5 h-3.5" />
                        </button>

                        <!-- Delete Prize -->
                        <button
                          v-if="$page.props.auth?.user?.role === 'admin'"
                          @click="deletePrize(prize.id)"
                          class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 hover:bg-rose-950"
                          title="Delete Prize (Admin Only)"
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

        <!-- Add Prize Form Sidebar -->
        <div class="lg:col-span-4 space-y-4">
          <div class="glass-card rounded-3xl p-6 border border-teal-500/30 shadow-xl space-y-4">
            <div class="flex items-center gap-2 text-teal-400">
              <PlusCircle class="w-5 h-5" />
              <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
                Add New Prize Tier
              </h3>
            </div>

            <form @submit.prevent="submitNewPrize" class="space-y-4">
              <div>
                <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Prize Label / Title *</label>
                <input
                  v-model="createForm.label"
                  type="text"
                  required
                  placeholder="e.g. 1st Prize, 2nd Prize, Consolation Gift"
                  class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                />
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Prize Item / Description</label>
                <textarea
                  v-model="createForm.description"
                  rows="3"
                  placeholder="e.g. Mercedes-Benz Chronograph Watch + Detailing Voucher"
                  class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                ></textarea>
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-200 light:text-slate-700 flex items-center justify-between">
                  <span>Number of Winners (Quota) *</span>
                  <span class="text-teal-400 font-mono font-bold">{{ createForm.quantity }} Winner(s)</span>
                </label>
                <div class="flex items-center gap-2 mt-1">
                  <input
                    v-model.number="createForm.quantity"
                    type="number"
                    min="1"
                    max="100"
                    required
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-mono"
                  />
                </div>
                <p class="text-[0.65rem] text-slate-400 mt-1">
                  For example: 1 for 1st Prize, 2 for 2nd Prize, 5 for Consolation Doorprizes.
                </p>
              </div>

              <button
                type="submit"
                :disabled="createForm.processing"
                class="w-full py-3 rounded-xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-lg shadow-teal-950/40 transition-all flex items-center justify-center gap-2"
              >
                <Plus class="w-4 h-4" />
                <span>{{ createForm.processing ? 'Adding Prize...' : 'Save Prize Tier' }}</span>
              </button>
            </form>
          </div>

          <!-- Quick Launch Presentation Banner -->
          <div class="glass-card rounded-3xl p-6 border border-amber-500/30 bg-gradient-to-br from-amber-950/30 via-slate-900 to-slate-950 space-y-3">
            <div class="flex items-center gap-2 text-amber-400">
              <Tv class="w-5 h-5" />
              <h4 class="text-sm font-bold text-white font-heading">Event Stage Ready</h4>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed font-light">
              Connect this computer to the venue LED wall or projector and click <strong>Launch Live Roulette</strong> to spin for each prize with audio-visual celebratory effects.
            </p>
            <Link
              v-if="selectedEvent"
              :href="route('admin.events.doorprize.roulette', selectedEvent.id)"
              class="block text-center py-2.5 rounded-xl text-xs font-bold bg-amber-400 hover:bg-amber-300 text-slate-950 transition-all shadow-md shadow-amber-950/60"
            >
              Open Full-Screen Presentation &rarr;
            </Link>
          </div>
        </div>
      </div>

      <!-- Edit Prize Modal -->
      <div v-if="editingPrize" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-md w-full border border-teal-500/40 shadow-2xl relative">
          <button
            @click="editingPrize = null"
            class="absolute top-4 right-4 text-slate-400 hover:text-white p-1"
          >
            <X class="w-5 h-5" />
          </button>

          <div class="mb-4 space-y-1">
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400">Modify Prize Tier</span>
            <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading">Edit Prize</h3>
          </div>

          <form @submit.prevent="submitEditPrize" class="space-y-4">
            <div>
              <label class="text-xs font-semibold text-slate-200">Prize Label / Title *</label>
              <input
                v-model="editForm.label"
                type="text"
                required
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-teal-400"
              />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200">Prize Item / Description</label>
              <textarea
                v-model="editForm.description"
                rows="3"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200 flex items-center justify-between">
                <span>Number of Winners (Quota) *</span>
                <span class="text-teal-400 font-mono font-bold">{{ editForm.quantity }} Winner(s)</span>
              </label>
              <input
                v-model.number="editForm.quantity"
                type="number"
                min="1"
                max="100"
                required
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-teal-400 font-mono"
              />
            </div>

            <div class="flex items-center gap-3 pt-2">
              <button
                type="button"
                @click="editingPrize = null"
                class="flex-1 py-2.5 rounded-xl font-bold text-xs bg-slate-800 text-slate-300 hover:bg-slate-700"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="editForm.processing"
                class="flex-1 py-2.5 rounded-xl font-bold text-xs bg-teal-500 text-slate-950 hover:bg-teal-400 shadow-md"
              >
                {{ editForm.processing ? 'Updating...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { formatDate } from '../../../Utils/date';
import {
  Sparkles,
  Trophy,
  Users,
  Gift,
  PlusCircle,
  Plus,
  Edit3,
  Trash2,
  RotateCcw,
  CheckCircle2,
  Tv,
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
  prizes: {
    type: Array,
    default: () => [],
  },
  poolStats: {
    type: Object,
    default: () => ({}),
  },
});

const selectedEventId = ref(props.selectedEvent?.id || '');

const hasAnyWinners = computed(() => {
  return props.prizes.some(p => (p.winners && p.winners.length > 0) || p.winner_guest_book_entry_id);
});

const changeEvent = () => {
  router.get(route('admin.events.doorprize', selectedEventId.value));
};

const createForm = useForm({
  label: '',
  description: '',
  quantity: 1,
});

const submitNewPrize = () => {
  if (!props.selectedEvent) return;
  createForm.post(route('admin.events.doorprize.store', props.selectedEvent.id), {
    onSuccess: () => {
      createForm.reset();
      createForm.quantity = 1;
    },
  });
};

const editingPrize = ref(null);
const editForm = useForm({
  label: '',
  description: '',
  quantity: 1,
});

const openEditModal = (prize) => {
  editingPrize.value = prize;
  editForm.label = prize.label;
  editForm.description = prize.description || '';
  editForm.quantity = prize.quantity || 1;
};

const submitEditPrize = () => {
  if (!editingPrize.value) return;
  editForm.put(route('admin.doorprize.update', editingPrize.value.id), {
    onSuccess: () => {
      editingPrize.value = null;
    },
  });
};

const deletePrize = (id) => {
  if (confirm('Are you sure you want to delete this prize tier?')) {
    router.delete(route('admin.doorprize.destroy', id));
  }
};

const resetPrizeWinners = (id) => {
  if (confirm('Reset all winners for this prize? All won attendees will be returned to the live draw pool.')) {
    router.post(route('admin.doorprize.reset', id));
  }
};

const deleteSingleWinner = (winnerId) => {
  if (confirm('Remove this winner? The attendee will be returned to the live draw pool.')) {
    router.delete(route('admin.doorprize.winner.destroy', winnerId));
  }
};

const confirmResetAll = () => {
  if (!props.selectedEvent) return;
  if (confirm('Are you sure you want to RESET ALL winners for this event? All guests will be returned to the draw pool.')) {
    router.delete(route('admin.events.doorprize.reset-all', props.selectedEvent.id));
  }
};
</script>
