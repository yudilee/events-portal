<template>
  <AdminLayout>
    <Head title="Attendee Wishes & Community Feedback — Organizer" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Community Engagement</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Attendee Wishes & Feedback Wall
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Moderate attendee congratulations, event afterthoughts, selfie uploads, and featured spotlights.
          </p>
        </div>

        <!-- Filter by Event -->
        <div class="flex items-center gap-2">
          <select
            :value="selectedEventId"
            @change="filterByEvent($event.target.value)"
            class="px-3.5 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
          >
            <option value="">All Corporate Events</option>
            <option v-for="e in events" :key="e.id" :value="e.id">{{ e.title }}</option>
          </select>
        </div>
      </div>

      <!-- Wishes List -->
      <div class="glass-card rounded-3xl border border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white overflow-hidden shadow-xl">
        <div v-if="comments.data.length === 0" class="p-12 text-center text-xs text-slate-400 light:text-slate-600">
          No attendee wishes found matching the filter.
        </div>

        <div v-else class="divide-y divide-slate-800/80 light:divide-slate-200">
          <div
            v-for="item in comments.data"
            :key="item.id"
            class="p-5 sm:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:bg-slate-900/30 light:hover:bg-slate-50 transition-colors"
          >
            <!-- Left: Attendee Info & Message -->
            <div class="flex items-start gap-4 flex-1 min-w-0">
              <div v-if="item.photo_url" class="w-16 h-16 rounded-2xl overflow-hidden shrink-0 border border-slate-700 light:border-slate-300 bg-slate-900 light:bg-slate-100">
                <img :src="item.photo_url" alt="Selfie" class="w-full h-full object-cover" />
              </div>
              <div v-else class="w-12 h-12 rounded-2xl bg-teal-950/80 light:bg-teal-100 border border-teal-800/40 light:border-teal-300 text-teal-300 light:text-teal-800 font-bold flex items-center justify-center shrink-0">
                {{ item.guest_name.charAt(0) }}
              </div>

              <div class="space-y-1 min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-sm font-bold text-white light:text-slate-900 font-heading">{{ item.guest_name }}</span>
                  <span v-if="item.guest_company" class="text-xs text-teal-400 light:text-teal-700 font-medium">({{ item.guest_company }})</span>
                  <span v-if="item.is_highlighted" class="px-2 py-0.5 rounded text-[0.6rem] font-bold bg-amber-950/80 light:bg-amber-100 text-amber-300 light:text-amber-800 border border-amber-800/40 light:border-amber-300">
                    ★ Featured
                  </span>
                </div>

                <div class="text-[0.7rem] text-slate-400 light:text-slate-500 flex items-center gap-2">
                  <span>Event: <strong class="text-slate-300 light:text-slate-700">{{ item.event?.title }}</strong></span>
                  <span>•</span>
                  <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                  <span>•</span>
                  <span class="text-rose-400 light:text-rose-600 font-semibold">❤️ {{ item.likes_count }} Likes</span>
                </div>

                <p class="text-xs text-slate-300 light:text-slate-700 font-light leading-relaxed pt-1">
                  "{{ item.message }}"
                </p>
              </div>
            </div>

            <!-- Right: Moderation Actions -->
            <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
              <button
                type="button"
                @click="toggleHighlight(item.id)"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors border',
                  item.is_highlighted
                    ? 'bg-amber-500 text-slate-950 font-bold border-amber-500'
                    : 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 border-slate-700 light:border-slate-300 hover:text-amber-300 light:hover:text-amber-700'
                ]"
                title="Highlight on Public Wall"
              >
                <Star class="w-3.5 h-3.5" />
                <span>{{ item.is_highlighted ? 'Highlighted' : 'Highlight' }}</span>
              </button>

              <button
                type="button"
                @click="toggleApproval(item.id)"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors border',
                  item.is_approved
                    ? 'bg-emerald-950/80 light:bg-emerald-100 text-emerald-300 light:text-emerald-800 border-emerald-800/40 light:border-emerald-300 hover:bg-emerald-900/60'
                    : 'bg-rose-950/80 light:bg-rose-100 text-rose-300 light:text-rose-800 border-rose-800/40 light:border-rose-300 hover:bg-rose-900/60'
                ]"
              >
                <Check v-if="item.is_approved" class="w-3.5 h-3.5" />
                <X v-else class="w-3.5 h-3.5" />
                <span>{{ item.is_approved ? 'Approved' : 'Hidden' }}</span>
              </button>

              <button
                type="button"
                @click="deleteComment(item.id)"
                class="p-2 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 hover:text-rose-400 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors border border-slate-700 light:border-slate-300"
                title="Delete Message"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import {
  Star,
  Check,
  X,
  Trash2
} from 'lucide-vue-next';

defineProps({
  comments: {
    type: Object,
    required: true,
  },
  events: {
    type: Array,
    default: () => [],
  },
  selectedEventId: {
    type: Number,
    default: null,
  },
});

const filterByEvent = (eventId) => {
  router.get(route('admin.wishes.index'), { event_id: eventId || undefined }, { preserveState: true });
};

const toggleApproval = (id) => {
  router.post(route('admin.wishes.approval', id), {}, { preserveScroll: true });
};

const toggleHighlight = (id) => {
  router.post(route('admin.wishes.highlight', id), {}, { preserveScroll: true });
};

const deleteComment = (id) => {
  if (confirm('Delete this attendee message?')) {
    router.delete(route('admin.wishes.destroy', id), { preserveScroll: true });
  }
};
</script>
