<template>
  <AdminLayout>
    <Head :title="unit ? `Edit Business Unit — ${unit.name}` : 'Add New Business Unit — Organizer'" />

    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 light:text-slate-500">
            <Link :href="route('admin.business-units.index')" class="hover:text-teal-400 light:hover:text-teal-700">Business Units</Link>
            <span>/</span>
            <span class="text-teal-400 light:text-teal-700 font-semibold">{{ unit ? unit.name : 'New Unit' }}</span>
          </div>
          <h1 class="text-2xl font-extrabold text-white light:text-slate-900 font-heading mt-1">
            {{ unit ? 'Edit Business Unit Details' : 'Add New Subsidiary / Business Unit' }}
          </h1>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Main Unit Info -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            General Information & Branding
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Business Unit / Division Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="e.g. Mercedes-Benz Authorized Dealer, HR Auto Studio"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.name" class="text-rose-400 text-xs">{{ form.errors.name }}</div>
            </div>

            <!-- Slug -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">URL Slug (Optional - auto generated if empty)</label>
              <div class="flex items-center gap-2">
                <span class="text-xs font-mono text-slate-500 light:text-slate-600">/events?unit=</span>
                <input
                  v-model="form.slug"
                  type="text"
                  placeholder="e.g. hr-auto-studio, mercedes-benz"
                  class="flex-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-mono"
                />
              </div>
              <div v-if="form.errors.slug" class="text-rose-400 text-xs">{{ form.errors.slug }}</div>
            </div>

            <!-- Subtitle -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Tagline / Subtitle</label>
              <input
                v-model="form.subtitle"
                type="text"
                placeholder="e.g. Body Repair, Paint & Premium Detailing"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- Description -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Overview / Business Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Briefly describe the business unit capabilities, services, or market focus displayed on ecosystem cards..."
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Visual Styling & Icon Selection -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            Icon & Color Palette Styling
          </h2>

          <!-- Icon Selector Grid -->
          <div class="space-y-3">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Select Visual Icon</label>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
              <button
                v-for="opt in iconOptions"
                :key="opt.name"
                type="button"
                @click="form.icon = opt.name"
                :class="[
                  'p-3 rounded-2xl border flex flex-col items-center justify-center gap-2 transition-all',
                  form.icon === opt.name
                    ? 'border-teal-400 bg-teal-950/60 light:bg-teal-50 text-teal-300 light:text-teal-800 font-bold ring-2 ring-teal-500/30'
                    : 'border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-slate-50 text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black hover:bg-slate-800 light:hover:bg-slate-100'
                ]"
              >
                <component :is="opt.icon" class="w-6 h-6" />
                <span class="text-[0.65rem]">{{ opt.label }}</span>
              </button>
            </div>
          </div>

          <!-- Color Badge Selector -->
          <div class="space-y-3 pt-3 border-t border-slate-800/80 light:border-slate-200">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Accent Badge Color</label>
            <div class="grid grid-cols-2 sm:grid-cols-7 gap-3">
              <button
                v-for="color in colorOptions"
                :key="color.value"
                type="button"
                @click="form.badge_color = color.value"
                :class="[
                  'p-2.5 rounded-xl border text-xs font-semibold flex items-center justify-center gap-1.5 transition-all',
                  color.badgeClass,
                  form.badge_color === color.value ? 'ring-2 ring-teal-400 font-bold scale-105' : 'opacity-70 hover:opacity-100'
                ]"
              >
                <span class="w-2.5 h-2.5 rounded-full bg-current"></span>
                <span>{{ color.label }}</span>
              </button>
            </div>
          </div>

          <!-- Sort Order -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-800/80 light:border-slate-200">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Ecosystem Sort Order Index</label>
              <input
                v-model="form.sort_order"
                type="number"
                min="0"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
              <p class="text-[0.65rem] text-slate-400 light:text-slate-500">Determines the card sequence on the front page from left to right.</p>
            </div>
          </div>
        </div>

        <!-- Live Front-Page Card Preview -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-teal-500/30 light:border-teal-300 bg-slate-900/60 light:bg-white space-y-4 shadow-2xl">
          <div class="flex items-center gap-2 text-teal-400 light:text-teal-700 text-xs font-bold uppercase tracking-wider">
            <Sparkles class="w-4 h-4" />
            <span>Live Front-Page Ecosystem Card Preview</span>
          </div>

          <div class="max-w-xs">
            <div class="glass-card rounded-2xl p-5 border border-slate-800/80 light:border-slate-200 bg-slate-900 light:bg-white hover:border-teal-500/40 transition-all flex flex-col justify-between shadow-md">
              <div>
                <div
                  class="w-10 h-10 rounded-xl border flex items-center justify-center mb-4 transition-transform"
                  :class="getPreviewBadgeClasses(form.badge_color)"
                >
                  <component :is="getIconComponent(form.icon)" class="w-5 h-5" />
                </div>
                <h3 class="text-sm font-bold text-white light:text-slate-900 font-heading">
                  {{ form.name || 'Business Unit Name' }}
                </h3>
                <p class="text-xs text-slate-400 light:text-slate-600 mt-1 font-light line-clamp-3">
                  {{ form.subtitle || form.description || 'Business unit subtitle or overview narrative.' }}
                </p>
              </div>

              <div class="mt-4 pt-3 border-t border-slate-800/60 light:border-slate-200 flex items-center justify-between text-[0.7rem] text-slate-400 light:text-slate-500">
                <span>{{ unit?.events_count || 0 }} Events</span>
                <span class="text-teal-400 light:text-teal-700 font-semibold">
                  Explore &rarr;
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
          <Link
            :href="route('admin.business-units.index')"
            class="px-6 py-3 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black text-xs font-semibold"
          >
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-8 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all"
          >
            {{ unit ? 'Update Business Unit' : 'Add to Ecosystem' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import {
  Car,
  Sparkles,
  Key,
  Truck,
  Container,
  Building2,
  ShieldCheck,
  Wrench,
  Layers,
  Globe
} from 'lucide-vue-next';

const props = defineProps({
  unit: {
    type: Object,
    default: null,
  },
  nextOrder: {
    type: Number,
    default: 1,
  },
});

const iconOptions = [
  { name: 'Car', label: 'Car / Dealership', icon: Car },
  { name: 'Sparkles', label: 'Auto Studio / Detail', icon: Sparkles },
  { name: 'Key', label: 'Car Rental / Fleet', icon: Key },
  { name: 'Truck', label: 'Truck Body / Karoseri', icon: Truck },
  { name: 'Container', label: 'Heavy Transport', icon: Container },
  { name: 'Building2', label: 'Corporate HQ', icon: Building2 },
  { name: 'Wrench', label: 'Workshop / Service', icon: Wrench },
  { name: 'ShieldCheck', label: 'Certified / Safety', icon: ShieldCheck },
  { name: 'Layers', label: 'Logistics / Supply', icon: Layers },
  { name: 'Globe', label: 'Global / Network', icon: Globe },
];

const colorOptions = [
  { value: 'teal', label: 'Teal', badgeClass: 'bg-teal-950/80 light:bg-teal-100 border-teal-800/40 light:border-teal-300 text-teal-300 light:text-teal-800' },
  { value: 'cyan', label: 'Cyan', badgeClass: 'bg-cyan-950/80 light:bg-cyan-100 border-cyan-800/40 light:border-cyan-300 text-cyan-300 light:text-cyan-800' },
  { value: 'gold', label: 'Gold', badgeClass: 'bg-amber-950/80 light:bg-amber-100 border-amber-800/40 light:border-amber-300 text-amber-300 light:text-amber-800' },
  { value: 'orange', label: 'Orange', badgeClass: 'bg-orange-950/80 light:bg-orange-100 border-orange-800/40 light:border-orange-300 text-orange-300 light:text-orange-800' },
  { value: 'rose', label: 'Rose', badgeClass: 'bg-rose-950/80 light:bg-rose-100 border-rose-800/40 light:border-rose-300 text-rose-300 light:text-rose-800' },
  { value: 'indigo', label: 'Indigo', badgeClass: 'bg-indigo-950/80 light:bg-indigo-100 border-indigo-800/40 light:border-indigo-300 text-indigo-300 light:text-indigo-800' },
  { value: 'slate', label: 'Slate', badgeClass: 'bg-slate-900 light:bg-slate-100 border-slate-700 light:border-slate-300 text-slate-300 light:text-slate-800' },
];

const form = useForm({
  name: props.unit?.name || '',
  slug: props.unit?.slug || '',
  subtitle: props.unit?.subtitle || '',
  description: props.unit?.description || '',
  icon: props.unit?.icon || 'Building2',
  badge_color: props.unit?.badge_color || 'teal',
  sort_order: props.unit?.sort_order ?? props.nextOrder,
});

const iconsMap = {
  Car,
  Sparkles,
  Key,
  Truck,
  Container,
  Building2,
  ShieldCheck,
  Wrench,
  Layers,
  Globe,
};

const getIconComponent = (iconName) => {
  return iconsMap[iconName] || Building2;
};

const getPreviewBadgeClasses = (color) => {
  switch (color) {
    case 'cyan':
      return 'bg-cyan-950/60 light:bg-cyan-100 border-cyan-800/40 light:border-cyan-300 text-cyan-400 light:text-cyan-800';
    case 'gold':
      return 'bg-amber-950/60 light:bg-amber-100 border-amber-800/40 light:border-amber-300 text-amber-400 light:text-amber-800';
    case 'orange':
      return 'bg-orange-950/60 light:bg-orange-100 border-orange-800/40 light:border-orange-300 text-orange-400 light:text-orange-800';
    case 'rose':
      return 'bg-rose-950/60 light:bg-rose-100 border-rose-800/40 light:border-rose-300 text-rose-400 light:text-rose-800';
    case 'indigo':
      return 'bg-indigo-950/60 light:bg-indigo-100 border-indigo-800/40 light:border-indigo-300 text-indigo-400 light:text-indigo-800';
    case 'slate':
      return 'bg-slate-900 light:bg-slate-100 border-slate-700 light:border-slate-300 text-slate-300 light:text-slate-800';
    default:
      return 'bg-teal-950/60 light:bg-teal-100 border-teal-800/40 light:border-teal-300 text-teal-400 light:text-teal-800';
  }
};

const submit = () => {
  if (props.unit) {
    form.put(route('admin.business-units.update', props.unit.id));
  } else {
    form.post(route('admin.business-units.store'));
  }
};
</script>
