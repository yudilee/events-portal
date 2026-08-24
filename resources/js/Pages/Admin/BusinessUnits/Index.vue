<template>
  <AdminLayout>
    <Head title="Business Units Ecosystem — Organizer Back-Office" />

    <div class="space-y-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Corporate Architecture</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Hartono Group Business Units Ecosystem
          </h1>
          <p class="text-xs text-slate-400 light:text-slate-500 mt-1">
            Manage subsidiary business units, brand representations, and corporate divisions displayed on the front page.
          </p>
        </div>

        <Link
          :href="route('admin.business-units.create')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all self-start sm:self-auto"
        >
          <Plus class="w-4 h-4" />
          <span>Add Business Unit</span>
        </Link>
      </div>

      <!-- Business Units List / Cards -->
      <div class="glass-card rounded-3xl border border-slate-800/80 light:border-slate-200 bg-slate-900/60 light:bg-white overflow-hidden shadow-xl">
        <div v-if="businessUnits.length === 0" class="p-12 text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-teal-950/60 light:bg-teal-50 border border-teal-800/40 light:border-teal-300 text-teal-400 light:text-teal-700 mx-auto flex items-center justify-center">
            <Building2 class="w-8 h-8" />
          </div>
          <div>
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">No Business Units Configured</h3>
            <p class="text-xs text-slate-400 light:text-slate-500 max-w-md mx-auto mt-1">
              Add your corporate subsidiaries, authorized dealerships, auto studios, and logistics companies.
            </p>
          </div>
          <Link
            :href="route('admin.business-units.create')"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-teal-500 text-slate-950 font-bold text-xs"
          >
            <Plus class="w-4 h-4" />
            <span>Create First Business Unit</span>
          </Link>
        </div>

        <div v-else class="divide-y divide-slate-800/80 light:divide-slate-200">
          <div
            v-for="(unit, index) in businessUnits"
            :key="unit.id"
            class="p-5 sm:p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors"
          >
            <!-- Left: Unit Icon & Details -->
            <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
              <!-- Icon Box -->
              <div
                class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border"
                :class="getBadgeClasses(unit.badge_color)"
              >
                <component :is="getIconComponent(unit.icon)" class="w-6 h-6" />
              </div>

              <!-- Details -->
              <div class="space-y-1 min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-xs font-mono font-bold text-teal-400 light:text-teal-700">#{{ unit.sort_order }}</span>
                  <h3 class="text-base font-bold text-white light:text-slate-900 font-heading truncate">
                    {{ unit.name }}
                  </h3>
                  <span class="text-[0.65rem] font-mono text-slate-400 light:text-slate-600 bg-slate-900 light:bg-slate-100 px-2 py-0.5 rounded border border-slate-800 light:border-slate-300">
                    /{{ unit.slug }}
                  </span>
                </div>

                <p v-if="unit.subtitle" class="text-xs text-teal-300 light:text-teal-700 font-medium line-clamp-1">
                  {{ unit.subtitle }}
                </p>

                <p v-if="unit.description" class="text-xs text-slate-400 light:text-slate-600 font-light line-clamp-2">
                  {{ unit.description }}
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-1 text-[0.7rem] text-slate-400 light:text-slate-500">
                  <span class="text-slate-300 light:text-slate-700 flex items-center gap-1 font-semibold">
                    <Calendar class="w-3 h-3 text-teal-400 light:text-teal-700" />
                    {{ unit.events_count || 0 }} Associated Events
                  </span>
                  <span class="text-slate-300 light:text-slate-700 flex items-center gap-1 font-semibold">
                    <Newspaper class="w-3 h-3 text-cyan-400 light:text-cyan-700" />
                    {{ unit.press_releases_count || 0 }} Press Releases
                  </span>
                </div>
              </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-2.5 shrink-0 self-end lg:self-center">
              <Link
                :href="route('public.events.index', { unit: unit.slug })"
                target="_blank"
                class="p-2.5 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 hover:text-teal-300 light:hover:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors text-xs font-semibold flex items-center gap-1.5 border border-slate-700 light:border-slate-300"
                title="View on Public Portal"
              >
                <ExternalLink class="w-3.5 h-3.5" />
                <span class="hidden sm:inline">Preview</span>
              </Link>

              <Link
                :href="route('admin.business-units.edit', unit.id)"
                class="p-2.5 rounded-xl bg-slate-800 light:bg-slate-100 text-teal-400 light:text-teal-700 hover:text-teal-300 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors border border-slate-700 light:border-slate-300"
                title="Edit Business Unit"
              >
                <Edit3 class="w-4 h-4" />
              </Link>

              <button
                type="button"
                @click="deleteUnit(unit)"
                class="p-2.5 rounded-xl bg-slate-800 light:bg-slate-100 text-slate-400 light:text-slate-600 hover:text-rose-400 hover:bg-slate-700 light:hover:bg-slate-200 transition-colors border border-slate-700 light:border-slate-300"
                title="Delete Business Unit"
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
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import {
  Building2,
  Plus,
  Calendar,
  Newspaper,
  ExternalLink,
  Edit3,
  Trash2,
  Car,
  Sparkles,
  Key,
  Truck,
  Container,
  ShieldCheck,
  Wrench,
  Layers,
  Globe
} from 'lucide-vue-next';

defineProps({
  businessUnits: {
    type: Array,
    default: () => [],
  },
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

const getBadgeClasses = (color) => {
  switch (color) {
    case 'cyan':
      return 'bg-cyan-950/80 light:bg-cyan-100 border-cyan-800/40 light:border-cyan-300 text-cyan-300 light:text-cyan-800';
    case 'gold':
      return 'bg-amber-950/80 light:bg-amber-100 border-amber-800/40 light:border-amber-300 text-amber-300 light:text-amber-800';
    case 'orange':
      return 'bg-orange-950/80 light:bg-orange-100 border-orange-800/40 light:border-orange-300 text-orange-300 light:text-orange-800';
    case 'rose':
      return 'bg-rose-950/80 light:bg-rose-100 border-rose-800/40 light:border-rose-300 text-rose-300 light:text-rose-800';
    case 'indigo':
      return 'bg-indigo-950/80 light:bg-indigo-100 border-indigo-800/40 light:border-indigo-300 text-indigo-300 light:text-indigo-800';
    case 'slate':
      return 'bg-slate-900 light:bg-slate-100 border-slate-700 light:border-slate-300 text-slate-300 light:text-slate-800';
    default:
      return 'bg-teal-950/80 light:bg-teal-100 border-teal-800/40 light:border-teal-300 text-teal-300 light:text-teal-800';
  }
};

const deleteUnit = (unit) => {
  if (confirm(`Are you sure you want to remove '${unit.name}' from the ecosystem? Associated events will not be deleted.`)) {
    router.delete(route('admin.business-units.destroy', unit.id));
  }
};
</script>
