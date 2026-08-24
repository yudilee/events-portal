<template>
  <AdminLayout>
    <Head :title="`Media & Gallery — ${event.title}`" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500">
            <Link :href="route('admin.events.index')" class="hover:text-teal-400 light:hover:text-teal-700">Events</Link>
            <span>/</span>
            <span class="text-teal-400 light:text-teal-700 font-semibold">{{ event.title }}</span>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
            Event Media & Gallery Manager
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Upload high-resolution event photos, batch galleries, and YouTube video embeds.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('public.events.gallery', event.slug)"
            target="_blank"
            class="px-4 py-2.5 rounded-xl bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-slate-200 light:text-slate-800 hover:text-white light:hover:text-black text-xs font-semibold flex items-center gap-1.5 transition-all border border-slate-700 light:border-slate-300 shadow-sm"
          >
            <span>View Public Gallery</span>
            <ExternalLink class="w-3.5 h-3.5" />
          </Link>

          <Link
            :href="route('admin.events.post-event', event.id)"
            class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold flex items-center gap-1.5 transition-all shadow-lg shadow-teal-950/50"
          >
            <Sparkles class="w-3.5 h-3.5" />
            <span>Post-Event Hub</span>
          </Link>
        </div>
      </div>

      <!-- Upload Suite Box -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
        <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3 flex items-center gap-2">
          <UploadCloud class="w-4 h-4 text-teal-400 light:text-teal-700" />
          <span>Upload Media Assets</span>
        </h2>

        <!-- Upload Tabs: Multi Image Upload vs Video URL -->
        <div class="flex items-center gap-2 border-b border-slate-800 light:border-slate-200 pb-3 text-xs font-semibold">
          <button
            type="button"
            @click="uploadMode = 'images'"
            :class="[
              'px-3.5 py-1.5 rounded-lg transition-colors',
              uploadMode === 'images' ? 'bg-teal-500 text-slate-950 font-bold' : 'text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black'
            ]"
          >
            Photos / Multi-Image Drag & Drop
          </button>
          <button
            type="button"
            @click="uploadMode = 'video'"
            :class="[
              'px-3.5 py-1.5 rounded-lg transition-colors',
              uploadMode === 'video' ? 'bg-teal-500 text-slate-950 font-bold' : 'text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black'
            ]"
          >
            Video Embed Link (YouTube / MP4)
          </button>
        </div>

        <!-- Image Upload Form -->
        <form v-if="uploadMode === 'images'" @submit.prevent="uploadImages" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700">Target Collection</label>
              <select
                v-model="imageForm.collection"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
              >
                <option value="gallery">Public Event Gallery</option>
                <option value="post_event">Post-Event Highlights</option>
                <option value="hero_slide">Hero Slide Banner</option>
              </select>
            </div>

            <div class="sm:col-span-2 space-y-1.5">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700">Default Caption (Optional)</label>
              <input
                v-model="imageForm.caption"
                type="text"
                placeholder="e.g. Soft Opening Ribbon Cutting Ceremony"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
              />
            </div>
          </div>

          <!-- Drag and Drop Dropzone -->
          <div
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            :class="[
              'border-2 border-dashed rounded-2xl p-8 text-center transition-all cursor-pointer',
              isDragging ? 'border-teal-400 bg-teal-950/20 light:bg-teal-50' : 'border-slate-700/80 light:border-slate-300 hover:border-slate-600 bg-slate-950/40 light:bg-slate-50'
            ]"
            @click="$refs.fileInput.click()"
          >
            <input
              ref="fileInput"
              type="file"
              multiple
              accept="image/*"
              @change="handleFiles"
              class="hidden"
            />
            <div class="w-12 h-12 rounded-2xl bg-slate-900 light:bg-white border border-slate-800 light:border-slate-300 text-teal-400 light:text-teal-700 mx-auto flex items-center justify-center mb-3">
              <ImageIcon class="w-6 h-6" />
            </div>
            <p class="text-xs font-bold text-white light:text-slate-900">
              Drag and drop high-resolution photos here, or click to browse
            </p>
            <p class="text-[0.7rem] text-slate-400 light:text-slate-500 mt-1">
              Supports JPEG, PNG, WebP up to 10MB per file. Multi-selection enabled.
            </p>

            <div v-if="selectedFiles.length > 0" class="mt-4 pt-3 border-t border-slate-800 light:border-slate-200 text-xs text-teal-300 light:text-teal-700 font-semibold">
              {{ selectedFiles.length }} file(s) queued for upload
            </div>
          </div>

          <div v-if="selectedFiles.length > 0" class="flex justify-end gap-3">
            <button
              type="button"
              @click="selectedFiles = []"
              class="px-4 py-2 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black text-xs font-semibold"
            >
              Clear Queue
            </button>
            <button
              type="submit"
              :disabled="imageForm.processing"
              class="px-6 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/50"
            >
              {{ imageForm.processing ? 'Uploading Assets...' : `Upload ${selectedFiles.length} Photos` }}
            </button>
          </div>
        </form>

        <!-- Video URL Form -->
        <form v-else @submit.prevent="uploadVideo" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700">Target Collection</label>
              <select
                v-model="videoForm.collection"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
              >
                <option value="gallery">Public Event Gallery</option>
                <option value="post_event">Post-Event Highlights</option>
              </select>
            </div>

            <div class="sm:col-span-2 space-y-1.5">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700">Video Embed URL (YouTube, Vimeo, MP4) *</label>
              <input
                v-model="videoForm.media_url"
                type="text"
                required
                placeholder="https://www.youtube.com/watch?v=... or https://..."
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
              />
            </div>

            <div class="sm:col-span-3 space-y-1.5">
              <label class="text-xs font-semibold text-slate-300 light:text-slate-700">Video Title / Caption</label>
              <input
                v-model="videoForm.caption"
                type="text"
                placeholder="e.g. Official Soft Opening Ceremony Highlights Reel"
                class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
              />
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="videoForm.processing"
              class="px-6 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-950/50"
            >
              Add Video to Event
            </button>
          </div>
        </form>
      </div>

      <!-- Current Media Library Grid -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 light:border-slate-200 pb-3">
          <div>
            <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">
              Current Media Assets ({{ filteredMedia.length }})
            </h2>
            <p class="text-xs text-slate-400 light:text-slate-500 mt-0.5">
              Click to toggle featured cover status, edit captions, or delete media items.
            </p>
          </div>

          <!-- Collection Filter -->
          <div class="flex items-center gap-2 text-xs font-semibold">
            <button
              type="button"
              @click="filterCollection = 'all'"
              :class="[
                'px-3 py-1 rounded-lg transition-colors',
                filterCollection === 'all' ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-300'
              ]"
            >
              All ({{ media.length }})
            </button>
            <button
              type="button"
              @click="filterCollection = 'gallery'"
              :class="[
                'px-3 py-1 rounded-lg transition-colors',
                filterCollection === 'gallery' ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-300'
              ]"
            >
              Gallery ({{ media.filter(m => m.collection === 'gallery').length }})
            </button>
            <button
              type="button"
              @click="filterCollection = 'post_event'"
              :class="[
                'px-3 py-1 rounded-lg transition-colors',
                filterCollection === 'post_event' ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-700 hover:text-white light:hover:text-black border border-slate-700 light:border-slate-300'
              ]"
            >
              Post-Event ({{ media.filter(m => m.collection === 'post_event').length }})
            </button>
          </div>
        </div>

        <div v-if="filteredMedia.length === 0" class="p-12 text-center text-slate-400 light:text-slate-500 text-xs">
          No media items in this collection yet. Use the upload box above to add photos or video links.
        </div>

        <!-- Media Grid -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
          <div
            v-for="item in filteredMedia"
            :key="item.id"
            class="group relative rounded-2xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-950 light:bg-white flex flex-col justify-between shadow-lg"
          >
            <!-- Media Visual -->
            <div class="relative aspect-video sm:aspect-square bg-slate-900 light:bg-slate-100 overflow-hidden">
              <!-- Video Badge or Image -->
              <template v-if="item.type === 'video_url'">
                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-900 light:bg-slate-100 text-cyan-400 light:text-cyan-700 p-4 text-center">
                  <PlayCircle class="w-10 h-10 mb-1 animate-pulse" />
                  <span class="text-[0.65rem] font-mono text-slate-400 light:text-slate-600 line-clamp-1">{{ item.file_path }}</span>
                </div>
              </template>
              <template v-else>
                <img
                  :src="item.url"
                  :alt="item.caption || 'Event image'"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
              </template>

              <!-- Badges -->
              <div class="absolute top-2 left-2 flex flex-col gap-1">
                <span
                  class="px-2 py-0.5 rounded text-[0.6rem] font-bold uppercase tracking-wider bg-slate-950/80 light:bg-white text-teal-300 light:text-teal-800 border border-teal-500/30 shadow-sm"
                >
                  {{ item.collection }}
                </span>
                <span
                  v-if="item.is_featured"
                  class="px-2 py-0.5 rounded text-[0.6rem] font-bold uppercase tracking-wider bg-amber-500 text-slate-950"
                >
                  ★ Featured Cover
                </span>
              </div>

              <!-- Top Right Delete & Star Actions -->
              <div class="absolute top-2 right-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button
                  type="button"
                  @click="toggleFeatured(item.id)"
                  :class="[
                    'p-1.5 rounded-lg text-xs transition-colors',
                    item.is_featured ? 'bg-amber-500 text-slate-950' : 'bg-slate-950/80 light:bg-white text-slate-300 light:text-slate-700 hover:text-amber-400'
                  ]"
                  title="Toggle Featured Cover"
                >
                  <Star class="w-3.5 h-3.5" />
                </button>

                <button
                  type="button"
                  @click="deleteMedia(item.id)"
                  class="p-1.5 rounded-lg bg-slate-950/80 light:bg-white text-slate-400 light:text-slate-600 hover:text-rose-400 transition-colors"
                  title="Delete Item"
                >
                  <Trash2 class="w-3.5 h-3.5" />
                </button>
              </div>
            </div>

            <!-- Bottom Caption & Info -->
            <div class="p-3 bg-slate-900/90 light:bg-white border-t border-slate-800 light:border-slate-200 text-xs space-y-1">
              <p class="text-slate-300 light:text-slate-700 font-medium line-clamp-1">
                {{ item.caption || 'No caption set' }}
              </p>
              <div class="flex items-center justify-between text-[0.65rem] text-slate-500">
                <span>Type: {{ item.type }}</span>
                <span>#{{ item.sort_order }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import {
  UploadCloud,
  ExternalLink,
  Sparkles,
  Image as ImageIcon,
  PlayCircle,
  Star,
  Trash2
} from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
  media: {
    type: Array,
    default: () => [],
  },
});

const uploadMode = ref('images');
const filterCollection = ref('all');
const isDragging = ref(false);
const selectedFiles = ref([]);

const imageForm = useForm({
  collection: 'gallery',
  caption: '',
  files: [],
});

const videoForm = useForm({
  collection: 'gallery',
  type: 'video_url',
  media_url: '',
  caption: '',
});

const filteredMedia = computed(() => {
  if (filterCollection.value === 'all') return props.media;
  return props.media.filter((m) => m.collection === filterCollection.value);
});

const handleFiles = (e) => {
  selectedFiles.value = Array.from(e.target.files);
};

const handleDrop = (e) => {
  isDragging.value = false;
  if (e.dataTransfer.files.length) {
    selectedFiles.value = Array.from(e.dataTransfer.files);
  }
};

const uploadImages = () => {
  if (!selectedFiles.value.length) return;
  imageForm.files = selectedFiles.value;
  imageForm.post(route('admin.events.media.store', props.event.id), {
    preserveScroll: true,
    onSuccess: () => {
      selectedFiles.value = [];
      imageForm.reset('caption');
    },
  });
};

const uploadVideo = () => {
  videoForm.post(route('admin.events.media.store', props.event.id), {
    preserveScroll: true,
    onSuccess: () => {
      videoForm.reset('media_url', 'caption');
    },
  });
};

const toggleFeatured = (id) => {
  router.post(route('admin.media.featured', id), {}, { preserveScroll: true });
};

const deleteMedia = (id) => {
  if (confirm('Delete this media asset?')) {
    router.delete(route('admin.media.destroy', id), { preserveScroll: true });
  }
};
</script>
