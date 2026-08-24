<template>
  <AdminLayout>
    <Head :title="slide ? 'Edit Hero Slide — Organizer' : 'Create New Hero Slide — Organizer'" />

    <div class="max-w-4xl mx-auto space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Front Page Showcase</span>
          <h1 class="text-2xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            {{ slide ? 'Edit Carousel Slide' : 'Create New Showcase Slide' }}
          </h1>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Quick Auto-Import from Existing Event -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-teal-500/30 light:border-teal-300 bg-slate-900/60 light:bg-white shadow-xl space-y-4">
          <div class="flex items-center gap-2 text-teal-300 light:text-teal-700 text-xs font-bold uppercase tracking-wider">
            <Sparkles class="w-4 h-4 text-teal-400" />
            <span>Quick Auto-Fill from Corporate Event</span>
          </div>
          <p class="text-xs text-slate-400 light:text-slate-600">
            Select an existing event to automatically populate the title, subtitle, countdown date, and RSVP link.
          </p>

          <select
            @change="autoFillFromEvent($event.target.value)"
            class="w-full px-4 py-3 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
          >
            <option value="">-- Choose an Event to Import --</option>
            <option v-for="evt in events" :key="evt.id" :value="evt.id">
              {{ evt.title }} ({{ formatDate(evt.date) }})
            </option>
          </select>
        </div>

        <!-- Slide Details -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            Slide Content & Typography
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Badge Text -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Badge Tag (e.g. Featured Gathering, Grand Opening, Special Spotlight)</label>
              <input
                v-model="form.badge_text"
                type="text"
                placeholder="e.g. Featured Gathering • August 27"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- Title -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Main Slide Title *</label>
              <input
                v-model="form.title"
                type="text"
                required
                placeholder="e.g. Experience Automotive Excellence & Innovation"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.title" class="text-rose-400 text-xs">{{ form.errors.title }}</div>
            </div>

            <!-- Subtitle -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Subtitle / Narrative Summary</label>
              <textarea
                v-model="form.subtitle"
                rows="2"
                placeholder="e.g. Customer Gathering CV HRM Jakarta & Soft Opening HR Auto Studio Body Repair & Detailing."
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>

            <!-- Image Upload or URL -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">High-Resolution Background Image *</label>
              <div class="space-y-3">
                <input
                  type="file"
                  accept="image/*"
                  @change="handleImageFile"
                  class="w-full px-4 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-slate-300 light:text-slate-700 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-500 file:text-slate-950 hover:file:bg-teal-400"
                />
                <div class="flex items-center gap-2">
                  <span class="text-xs text-slate-400 light:text-slate-600">Or enter Image URL:</span>
                  <input
                    v-model="form.image_url"
                    type="text"
                    placeholder="https://images.unsplash.com/..."
                    class="flex-1 px-3 py-1.5 rounded-lg bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
                  />
                </div>

                <div v-if="imagePreview || slide?.image_path" class="mt-2 w-full h-44 rounded-2xl overflow-hidden border border-slate-700 light:border-slate-300 bg-slate-950 light:bg-slate-100 relative">
                  <img
                    :src="imagePreview || slide?.image_path"
                    alt="Preview"
                    class="w-full h-full object-cover"
                  />
                  <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-950/80 light:bg-white text-teal-300 light:text-teal-800 shadow-sm">
                    Live Background Preview
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Buttons & Countdown Settings -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            Call to Action Buttons & Countdown Timer
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Primary CTA -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Primary Button Label</label>
              <input
                v-model="form.cta_text"
                type="text"
                placeholder="e.g. Confirm Your Attendance"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Primary Button URL</label>
              <input
                v-model="form.cta_url"
                type="text"
                placeholder="e.g. /events/customer-gathering-2026/register"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <!-- Secondary CTA -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Secondary Button Label</label>
              <input
                v-model="form.secondary_cta_text"
                type="text"
                placeholder="e.g. View Full Rundown"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Secondary Button URL</label>
              <input
                v-model="form.secondary_cta_url"
                type="text"
                placeholder="e.g. /events/customer-gathering-2026"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <!-- Countdown Toggle -->
            <div class="sm:col-span-2 pt-2 border-t border-slate-800/80 light:border-slate-200">
              <label class="flex items-center gap-3 cursor-pointer">
                <input
                  v-model="form.show_countdown"
                  type="checkbox"
                  class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
                />
                <span class="text-xs font-semibold text-white light:text-slate-900">Embed Live Countdown Clock on Slide</span>
              </label>
            </div>

            <div v-if="form.show_countdown" class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Countdown Target Date *</label>
              <input
                v-model="form.countdown_date"
                type="date"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div v-if="form.show_countdown" class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Countdown Target Time</label>
              <input
                v-model="form.countdown_time"
                type="text"
                placeholder="10:00"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <!-- Sort order & status -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Slide Order Index</label>
              <input
                v-model="form.sort_order"
                type="number"
                min="0"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Active State</label>
              <select
                v-model="form.is_active"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              >
                <option :value="true">Active (Display in Slider)</option>
                <option :value="false">Inactive (Hidden)</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
          <Link
            :href="route('admin.banners.index')"
            class="px-6 py-3 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black text-xs font-semibold"
          >
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-8 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all"
          >
            {{ slide ? 'Update Slide' : 'Publish Slide to Carousel' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { formatDate } from '../../../Utils/date';
import { Sparkles } from 'lucide-vue-next';

const props = defineProps({
  slide: {
    type: Object,
    default: null,
  },
  events: {
    type: Array,
    default: () => [],
  },
});

const imagePreview = ref(null);

const form = useForm({
  title: props.slide?.title || '',
  subtitle: props.slide?.subtitle || '',
  badge_text: props.slide?.badge_text || '',
  image: null,
  image_url: props.slide?.image_path || '',
  cta_text: props.slide?.cta_text || 'Confirm Your Attendance',
  cta_url: props.slide?.cta_url || '',
  secondary_cta_text: props.slide?.secondary_cta_text || 'View Rundown',
  secondary_cta_url: props.slide?.secondary_cta_url || '',
  event_id: props.slide?.event_id || null,
  show_countdown: props.slide?.show_countdown ?? false,
  countdown_date: props.slide?.countdown_date || '',
  countdown_time: props.slide?.countdown_time || '10:00',
  sort_order: props.slide?.sort_order || 0,
  is_active: props.slide?.is_active ?? true,
});

const handleImageFile = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const autoFillFromEvent = (eventId) => {
  if (!eventId) return;
  const evt = props.events.find((e) => e.id == eventId);
  if (evt) {
    form.event_id = evt.id;
    form.title = evt.title;
    form.subtitle = evt.subtitle || `Official gathering at ${evt.venue_name}`;
    form.badge_text = `Featured Event • ${formatDate(evt.date)}`;
    form.cta_text = 'Register & Confirm Attendance';
    form.cta_url = `/events/${evt.slug}/register`;
    form.secondary_cta_text = 'View Event Rundown';
    form.secondary_cta_url = `/events/${evt.slug}`;
    form.show_countdown = true;
    form.countdown_date = String(evt.date || '').split('T')[0];
    form.countdown_time = evt.start_time || '10:00';
    if (evt.hero_image) {
      form.image_url = evt.hero_image;
    }
  }
};

const submit = () => {
  if (props.slide) {
    form.post(route('admin.banners.update', props.slide.id), {
      _method: 'put',
    });
  } else {
    form.post(route('admin.banners.store'));
  }
};
</script>
