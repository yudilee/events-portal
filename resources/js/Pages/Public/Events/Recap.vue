<template>
  <PublicLayout>
    <Head :title="`Post-Event Recap & Wishes Wall — ${event.title}`" />

    <!-- Hero Header -->
    <section class="relative pt-10 pb-16 lg:pt-16 lg:pb-20 border-b border-slate-800/80 light:border-slate-200 overflow-hidden">
      <!-- Ambient Glow -->
      <div class="absolute inset-0 pointer-events-none -z-10 flex items-center justify-center">
        <div class="w-[700px] h-[350px] bg-teal-600/10 rounded-full blur-[140px]"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500 mb-6">
          <Link :href="route('public.events.index')" class="hover:text-teal-400 light:hover:text-teal-700">Events</Link>
          <span>/</span>
          <Link :href="route('public.events.show', event.slug)" class="hover:text-teal-400 light:hover:text-teal-700 truncate max-w-[200px]">{{ event.title }}</Link>
          <span>/</span>
          <span class="text-slate-200 light:text-slate-800 font-semibold">Post-Event Recap</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <div class="lg:col-span-8 space-y-4">
            <div class="flex flex-wrap items-center gap-3">
              <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-950/80 light:bg-rose-100 text-rose-300 light:text-rose-800 border border-rose-800/40 light:border-rose-300 flex items-center gap-1.5">
                <Sparkles class="w-3.5 h-3.5" />
                Official Event Recap & Highlights
              </span>
              <span class="text-xs text-slate-400 light:text-slate-500">
                {{ formattedDate }} • {{ event.venue_name }}
              </span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight leading-tight">
              {{ event.title }}
            </h1>

            <p v-if="event.subtitle" class="text-base sm:text-lg text-teal-300/90 light:text-teal-700 font-medium">
              {{ event.subtitle }}
            </p>
          </div>

          <!-- Shortcuts on Right -->
          <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-end">
            <button
              type="button"
              @click="wishModalOpen = true"
              class="w-full py-3.5 px-6 rounded-2xl font-bold text-xs bg-gradient-to-r from-teal-500 via-teal-400 to-cyan-400 text-slate-950 shadow-xl shadow-teal-950/50 hover:shadow-teal-500/30 transition-all flex items-center justify-center gap-2"
            >
              <HeartHandshake class="w-4 h-4" />
              <span>Send Congratulations & Wishes</span>
            </button>

            <Link
              v-if="event.is_gallery_enabled"
              :href="route('public.events.gallery', event.slug)"
              class="w-full py-3 px-6 rounded-2xl font-semibold text-xs glass-card bg-slate-900/60 light:bg-white hover:bg-slate-800 light:hover:bg-slate-100 text-white light:text-slate-800 border border-slate-700 light:border-slate-300 transition-all flex items-center justify-center gap-2 shadow-sm"
            >
              <Camera class="w-4 h-4 text-teal-400 light:text-teal-700" />
              <span>View Full Photo Gallery</span>
            </Link>
          </div>
        </div>
      </div>
    </section>

    <!-- Narrative & Executive Afterthoughts Section -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
          <!-- Left: Narrative Story -->
          <div class="lg:col-span-8 space-y-8">
            <div class="glass-card rounded-3xl p-6 sm:p-10 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
              <div class="border-b border-slate-800 light:border-slate-200 pb-4">
                <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Event Narrative</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
                  Executive Afterthought & Highlights
                </h2>
              </div>

              <!-- WYSIWYG Content Body -->
              <div
                v-if="event.post_event_summary"
                class="prose-hartono text-sm sm:text-base font-light leading-relaxed"
                v-html="event.post_event_summary"
              ></div>

              <div v-else class="text-sm text-slate-300 light:text-slate-700 font-light leading-relaxed space-y-4">
                <p>
                  PT Hartono Raya Motor and management would like to extend our deepest gratitude to all distinguished guests, customers, and media partners who attended <strong>{{ event.title }}</strong>.
                </p>
                <p>
                  Through continuous dedication and innovation since 1970, we are proud to inaugurate our state-of-the-art facilities and celebrate shared milestones with the entire automotive family.
                </p>
              </div>
            </div>

            <!-- Post-Event Highlight Photos Carousel / Grid -->
            <div v-if="postMedia.length > 0" class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading">
                  Event Photo Highlights
                </h3>
                <Link :href="route('public.events.gallery', event.slug)" class="text-xs font-semibold text-teal-400 light:text-teal-700 hover:underline">
                  All Photos &rarr;
                </Link>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                  v-for="item in postMedia.slice(0, 6)"
                  :key="item.id"
                  class="rounded-2xl overflow-hidden aspect-4/3 border border-slate-800 light:border-slate-200 bg-slate-950 light:bg-slate-100 group shadow-md"
                >
                  <img
                    :src="item.url"
                    :alt="item.caption || 'Event recap photo'"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Key Event Numbers & Quick Wish CTA -->
          <div class="lg:col-span-4 space-y-6">
            <!-- Event Metrics Card -->
            <div class="glass-card rounded-3xl p-6 border border-teal-500/30 light:border-teal-300 bg-slate-900/60 light:bg-white space-y-5 shadow-xl">
              <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700 block">
                Gathering Snapshot
              </span>

              <div class="space-y-4 divide-y divide-slate-800/80 light:divide-slate-200 text-xs">
                <div class="flex items-center justify-between pt-1 text-slate-300 light:text-slate-700">
                  <span>Guest Registrations</span>
                  <span class="font-bold text-white light:text-slate-900">{{ event.confirmed_registrations_count || event.max_capacity }} Attendees</span>
                </div>

                <div class="flex items-center justify-between pt-3 text-slate-300 light:text-slate-700">
                  <span>Business Division</span>
                  <span class="font-bold text-teal-300 light:text-teal-700">{{ event.business_unit?.name || 'Corporate HQ' }}</span>
                </div>

                <div class="flex items-center justify-between pt-3 text-slate-300 light:text-slate-700">
                  <span>Community Wishes</span>
                  <span class="font-bold text-white light:text-slate-900">{{ comments.length }} Messages</span>
                </div>
              </div>

              <div class="pt-2">
                <button
                  type="button"
                  @click="wishModalOpen = true"
                  class="w-full py-3 rounded-xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-md transition-all flex items-center justify-center gap-1.5"
                >
                  <MessageSquarePlus class="w-3.5 h-3.5" />
                  <span>Leave Your Wish Message</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Community Wishes Wall Section -->
    <section class="py-16 bg-slate-950/40 light:bg-slate-50 border-t border-slate-800/80 light:border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Community Thoughts</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
              Attendee Wishes & Congratulatory Wall
            </h2>
            <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 font-light mt-0.5">
              Read warm wishes, congratulations, and shared memories from event attendees.
            </p>
          </div>

          <button
            type="button"
            @click="wishModalOpen = true"
            class="px-5 py-2.5 rounded-xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-md transition-all flex items-center gap-1.5 self-start sm:self-auto"
          >
            <Plus class="w-4 h-4" />
            <span>Post a Wish</span>
          </button>
        </div>

        <div v-if="comments.length === 0" class="p-12 text-center text-xs text-slate-400 light:text-slate-600 glass-card rounded-3xl border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-md">
          Be the first to share your congratulations and afterthoughts on this gathering!
        </div>

        <!-- Wishes Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="item in comments"
            :key="item.id"
            :class="[
              'glass-card rounded-3xl p-6 border transition-all flex flex-col justify-between space-y-4 shadow-xl',
              item.is_highlighted
                ? 'border-amber-500/50 bg-amber-950/10 light:bg-amber-50'
                : 'border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white'
            ]"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-9 h-9 rounded-full bg-teal-500 text-slate-950 font-bold flex items-center justify-center font-heading text-xs">
                    {{ item.guest_name.charAt(0) }}
                  </div>
                  <div>
                    <h4 class="text-xs font-bold text-white light:text-slate-900">{{ item.guest_name }}</h4>
                    <span v-if="item.guest_company" class="text-[0.65rem] text-teal-400 light:text-teal-700 font-semibold">{{ item.guest_company }}</span>
                  </div>
                </div>

                <span
                  v-if="item.is_highlighted"
                  class="px-2 py-0.5 rounded text-[0.6rem] font-bold bg-amber-500 text-slate-950"
                >
                  ★ Highlight
                </span>
              </div>

              <!-- Selfie Photo if attached -->
              <div v-if="item.photo_url" class="rounded-2xl overflow-hidden aspect-video bg-slate-900 light:bg-slate-100 border border-slate-800 light:border-slate-200">
                <img :src="item.photo_url" alt="Attendee photo" class="w-full h-full object-cover" />
              </div>

              <p class="text-xs text-slate-300 light:text-slate-700 font-light leading-relaxed whitespace-pre-line">
                "{{ item.message }}"
              </p>
            </div>

            <!-- Like Interaction Bar -->
            <div class="pt-3 border-t border-slate-800/80 light:border-slate-200 flex items-center justify-between text-xs">
              <span class="text-[0.65rem] text-slate-500">
                {{ new Date(item.created_at).toLocaleDateString() }}
              </span>

              <button
                type="button"
                @click="likeComment(item.id)"
                class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 light:bg-slate-100 border border-slate-700/60 light:border-slate-300 text-slate-300 light:text-slate-700 hover:text-rose-400 transition-colors shadow-sm"
              >
                <Heart class="w-3.5 h-3.5 text-rose-500 fill-rose-500" />
                <span class="text-xs font-bold font-mono">{{ item.likes_count }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Post Wish Modal -->
    <div
      v-if="wishModalOpen"
      class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4"
      @click.self="wishModalOpen = false"
    >
      <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-teal-500/40 light:border-teal-300 bg-slate-900 light:bg-white shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 light:border-slate-200 pb-3">
          <div class="flex items-center gap-2 text-teal-400 light:text-teal-700 text-xs font-bold uppercase tracking-wider">
            <HeartHandshake class="w-4 h-4" />
            <span>Send Congratulations & Wishes</span>
          </div>
          <button @click="wishModalOpen = false" class="text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black p-1">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="submitWish" class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Your Full Name *</label>
            <input
              v-model="wishForm.guest_name"
              type="text"
              required
              placeholder="e.g. Budi Santoso"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:border-teal-400 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Company / Affiliation</label>
              <input
                v-model="wishForm.guest_company"
                type="text"
                placeholder="e.g. PT Mitra Sejahtera"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:border-teal-400 focus:outline-none"
              />
            </div>
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Designation / Role</label>
              <input
                v-model="wishForm.guest_role"
                type="text"
                placeholder="e.g. VIP Guest / Director"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:border-teal-400 focus:outline-none"
              />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Your Wish / Message *</label>
            <textarea
              v-model="wishForm.message"
              rows="3"
              required
              placeholder="Write your congratulatory message, appreciation, or afterthought..."
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:border-teal-400 focus:outline-none"
            ></textarea>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Attach Event Photo / Selfie (Optional)</label>
            <input
              type="file"
              accept="image/*"
              @change="handlePhotoUpload"
              class="w-full px-3 py-1.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-slate-300 light:text-slate-700 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-500 file:text-slate-950"
            />
          </div>

          <div class="pt-2 flex justify-end gap-3">
            <button
              type="button"
              @click="wishModalOpen = false"
              class="px-4 py-2.5 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 text-xs font-semibold"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="wishForm.processing"
              class="px-6 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60"
            >
              {{ wishForm.processing ? 'Publishing...' : 'Share My Wish' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import {
  Sparkles,
  HeartHandshake,
  Camera,
  MessageSquarePlus,
  Plus,
  Heart,
  X
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
  galleryMedia: {
    type: Array,
    default: () => [],
  },
  comments: {
    type: Array,
    default: () => [],
  },
});

const wishModalOpen = ref(false);

const wishForm = useForm({
  guest_name: '',
  guest_company: '',
  guest_role: '',
  message: '',
  photo: null,
  type: 'wish',
});

const formattedDate = computed(() => {
  if (!props.event?.date) return '';
  const dateObj = new Date(props.event.date);
  return dateObj.toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
});

const handlePhotoUpload = (e) => {
  if (e.target.files[0]) {
    wishForm.photo = e.target.files[0];
  }
};

const submitWish = () => {
  wishForm.post(route('public.events.comments.store', props.event.slug), {
    preserveScroll: true,
    onSuccess: () => {
      wishModalOpen.value = false;
      wishForm.reset();
    },
  });
};

const likeComment = (id) => {
  router.post(route('public.comments.like', id), {}, { preserveScroll: true });
};
</script>
