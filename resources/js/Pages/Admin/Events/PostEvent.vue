<template>
  <AdminLayout>
    <Head :title="`Post-Event Hub — ${event.title}`" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Top Navigation & Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500">
            <Link :href="route('admin.events.index')" class="hover:text-teal-400 light:hover:text-teal-700">Events</Link>
            <span>/</span>
            <span class="text-teal-400 light:text-teal-700 font-semibold">{{ event.title }}</span>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
            Post-Event Recap & Narrative Studio
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Craft the official post-event recap, executive afterthoughts, and manage attendee wishes wall.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.events.media.index', event.id)"
            class="px-4 py-2.5 rounded-xl bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-slate-200 light:text-slate-800 hover:text-white light:hover:text-black text-xs font-semibold flex items-center gap-1.5 transition-all border border-slate-700 light:border-slate-300 shadow-sm"
          >
            <Camera class="w-3.5 h-3.5 text-teal-400" />
            <span>Manage Media ({{ postMedia.length }})</span>
          </Link>

          <Link
            :href="route('public.events.recap', event.slug)"
            target="_blank"
            class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold flex items-center gap-1.5 transition-all shadow-lg shadow-teal-950/50"
          >
            <span>View Public Recap Wall</span>
            <ExternalLink class="w-3.5 h-3.5" />
          </Link>
        </div>
      </div>

      <!-- Narrative Form with Tiptap WYSIWYG -->
      <form @submit.prevent="submitNarrative" class="space-y-6">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 light:border-slate-200 pb-4">
            <div>
              <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">
                Executive Afterthought & Event Recap Narrative
              </h2>
              <p class="text-xs text-slate-400 light:text-slate-500 mt-0.5">
                Rich story summarizing event accomplishments, guest experiences, and leadership speeches.
              </p>
            </div>

            <!-- Publish Status Badge -->
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-2 text-xs font-semibold cursor-pointer">
                <input
                  v-model="form.publish_recap"
                  type="checkbox"
                  class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
                />
                <span :class="form.publish_recap ? 'text-teal-300 light:text-teal-700 font-bold' : 'text-slate-400 light:text-slate-500'">
                  {{ form.publish_recap ? 'Published (Live on Portal)' : 'Draft (Unpublished)' }}
                </span>
              </label>
            </div>
          </div>

          <!-- Rich Text Editor for Narrative -->
          <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">
              Recap Narrative Body (WYSIWYG)
            </label>
            <RichTextEditor
              v-model="form.post_event_summary"
              placeholder="Write the post-event highlights, attendee reception, executive remarks, and key milestones reached during this event..."
            />
          </div>

          <!-- Settings Toggles -->
          <div class="pt-2 border-t border-slate-800/80 light:border-slate-200 flex flex-wrap items-center justify-between gap-4">
            <label class="flex items-center gap-2.5 text-xs text-slate-300 light:text-slate-700 cursor-pointer">
              <input
                v-model="form.is_post_event_enabled"
                type="checkbox"
                class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
              />
              <span>Enable Community Wishes & Feedback Wall on Public Portal</span>
            </label>

            <button
              type="submit"
              :disabled="form.processing"
              class="px-8 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all"
            >
              {{ form.processing ? 'Saving Changes...' : 'Save & Update Recap' }}
            </button>
          </div>
        </div>
      </form>

      <!-- Attendee Wishes & Community Feedback Wall Moderation -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-800 light:border-slate-200 pb-4">
          <div>
            <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">
              Attendee Wishes & Afterthoughts ({{ comments.length }})
            </h2>
            <p class="text-xs text-slate-400 light:text-slate-500 mt-0.5">
              Messages and photos submitted by attendees during and after the gathering.
            </p>
          </div>

          <Link
            :href="route('admin.wishes.index', { event_id: event.id })"
            class="text-xs font-semibold text-teal-400 light:text-teal-700 hover:underline"
          >
            Moderate in Full Wishes Center &rarr;
          </Link>
        </div>

        <div v-if="comments.length === 0" class="p-8 text-center text-xs text-slate-400 light:text-slate-500">
          No attendee wishes submitted for this event yet.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="c in comments.slice(0, 6)"
            :key="c.id"
            class="p-4 rounded-2xl bg-slate-900/80 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-3 flex flex-col justify-between shadow-sm"
          >
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <div class="font-bold text-white light:text-slate-900 text-xs">{{ c.guest_name }}</div>
                <span
                  v-if="c.is_highlighted"
                  class="px-2 py-0.5 rounded text-[0.6rem] font-bold bg-amber-950/80 light:bg-amber-100 text-amber-300 light:text-amber-800 border border-amber-800/40 light:border-amber-300"
                >
                  ★ Highlighted
                </span>
              </div>
              <div v-if="c.guest_company" class="text-[0.65rem] text-teal-400 light:text-teal-700">
                {{ c.guest_company }}
              </div>
              <p class="text-xs text-slate-300 light:text-slate-700 font-light leading-relaxed">
                "{{ c.message }}"
              </p>
            </div>

            <div v-if="c.photo_url" class="rounded-xl overflow-hidden h-28 bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-200">
              <img :src="c.photo_url" alt="Attendee photo" class="w-full h-full object-cover" />
            </div>

            <div class="pt-2 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between text-[0.65rem] text-slate-500">
              <span>❤️ {{ c.likes_count }} Likes</span>
              <span>{{ new Date(c.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import RichTextEditor from '../../../Components/RichTextEditor.vue';
import {
  Camera,
  ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  postMedia: {
    type: Array,
    default: () => [],
  },
  comments: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  post_event_summary: props.event?.post_event_summary || '',
  is_post_event_enabled: props.event?.is_post_event_enabled ?? true,
  publish_recap: Boolean(props.event?.post_event_published_at),
});

const submitNarrative = () => {
  form.post(route('admin.events.post-event.update', props.event.id), {
    preserveScroll: true,
  });
};
</script>
