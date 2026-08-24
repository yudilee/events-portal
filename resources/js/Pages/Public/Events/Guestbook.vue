<template>
  <PublicLayout>
    <Head :title="`Digital Guest Book — ${event.title}`" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500 mb-6">
        <Link :href="route('public.events.index')" class="hover:text-teal-400 light:hover:text-teal-700">Events</Link>
        <span>/</span>
        <Link :href="route('public.events.show', event.slug)" class="hover:text-teal-400 light:hover:text-teal-700 line-clamp-1">{{ event.title }}</Link>
        <span>/</span>
        <span class="text-slate-200 light:text-slate-800 font-semibold">Guest Book Wall</span>
      </div>

      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Live Event Wall</span>
          <h1 class="text-3xl sm:text-4xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
            Digital Guest Book
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 light:text-slate-600 font-light mt-1">
            Leave your greetings, congratulations, and wishes for {{ event.title }}.
          </p>
        </div>

        <button
          @click="showFormModal = true"
          class="px-6 py-3 rounded-2xl font-bold text-xs bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-xl shadow-teal-950/60 transition-all flex items-center gap-2 self-start md:self-auto"
        >
          <PenTool class="w-4 h-4" />
          <span>Sign Digital Guest Book</span>
        </button>
      </div>

      <!-- Live Messages Masonry Grid -->
      <div v-if="entries.data && entries.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="entry in entries.data"
          :key="entry.id"
          :class="[
            'glass-card rounded-3xl p-6 border transition-all flex flex-col justify-between relative overflow-hidden shadow-md',
            entry.is_highlighted
              ? 'border-teal-500/50 bg-gradient-to-b from-teal-950/40 to-slate-900 light:from-teal-50 light:to-white shadow-xl shadow-teal-950/30'
              : 'border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white'
          ]"
        >
          <!-- VIP Highlight Pin -->
          <div v-if="entry.is_highlighted" class="absolute top-3 right-3 flex items-center gap-1 text-[0.65rem] font-bold uppercase tracking-wider text-teal-300 light:text-teal-800 bg-teal-950 light:bg-teal-100 px-2 py-0.5 rounded-full border border-teal-700/50 light:border-teal-300">
            <Sparkles class="w-3 h-3 text-teal-400 light:text-teal-700" />
            <span>Featured</span>
          </div>

          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-slate-800 light:bg-slate-100 border border-slate-700 light:border-slate-300 flex items-center justify-center text-sm font-bold font-heading text-white light:text-slate-900 shrink-0">
                {{ entry.guest_name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <h4 class="text-sm font-bold text-white light:text-slate-900 font-heading">{{ entry.guest_name }}</h4>
                <p v-if="entry.company" class="text-xs text-slate-400 light:text-slate-600 font-light">{{ entry.company }}</p>
              </div>
            </div>

            <!-- Message Text -->
            <p class="text-xs sm:text-sm text-slate-300 light:text-slate-700 leading-relaxed font-light italic">
              "{{ entry.message }}"
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between text-[0.7rem] text-slate-400 light:text-slate-500">
            <span>Posted today</span>
            <span class="text-teal-400 light:text-teal-700 font-semibold">✓ Verified Guest</span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="glass-card rounded-3xl p-12 text-center border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white max-w-md mx-auto shadow-md">
        <MessageSquareDashed class="w-12 h-12 text-slate-500 light:text-slate-400 mx-auto mb-3" />
        <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading">Be the First to Sign</h3>
        <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
          Share your message with the Hartono Group management and event attendees.
        </p>
        <button
          @click="showFormModal = true"
          class="mt-6 px-6 py-2.5 rounded-xl bg-teal-500 text-slate-950 font-bold text-xs"
        >
          Sign Guest Book
        </button>
      </div>

      <!-- Sign Form Modal -->
      <div v-if="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-teal-500/40 light:border-teal-300 bg-slate-900 light:bg-white shadow-2xl relative">
          <button
            @click="showFormModal = false"
            class="absolute top-4 right-4 text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black p-1"
          >
            <X class="w-5 h-5" />
          </button>

          <div class="mb-6 space-y-1">
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Signature Desk</span>
            <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading">Sign the Guest Book</h3>
          </div>

          <form @submit.prevent="submitGuestBook" class="space-y-4">
            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Your Full Name *</label>
              <input
                v-model="form.guest_name"
                type="text"
                required
                placeholder="e.g. Ir. Hartono"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Company / Affiliation</label>
              <input
                v-model="form.company"
                type="text"
                placeholder="e.g. PT Mitra Logistik"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Registration Code (Optional)</label>
              <input
                v-model="form.registration_code"
                type="text"
                placeholder="e.g. HRM-VIP-2026"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Your Message / Wishes *</label>
              <textarea
                v-model="form.message"
                required
                rows="4"
                placeholder="Congratulations on the Grand Soft Opening of HR Auto Studio..."
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>

            <div class="pt-2">
              <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 rounded-xl font-bold text-xs bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-lg transition-all"
              >
                {{ form.processing ? 'Posting Message...' : 'Post Message to Live Wall' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import {
  PenTool,
  Sparkles,
  MessageSquareDashed,
  X
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  entries: {
    type: Object,
    default: () => ({ data: [] }),
  },
});

const page = usePage();
const authUser = page.props.auth?.user;
const showFormModal = ref(false);

const form = useForm({
  guest_name: authUser?.name || '',
  company: authUser?.company || '',
  registration_code: '',
  message: '',
});

const submitGuestBook = () => {
  form.post(route('public.events.guestbook.store', props.event.slug), {
    onSuccess: () => {
      showFormModal.value = false;
      form.reset('message');
    },
  });
};
</script>
