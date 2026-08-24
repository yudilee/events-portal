<template>
  <AdminLayout>
    <Head title="Footer & Portal Settings — Hartono Event Hub" />

    <div class="space-y-8 max-w-6xl mx-auto pb-16">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading">
              Footer & Portal Settings
            </h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-500/20 text-teal-400 light:text-teal-800 light:bg-teal-100 border border-teal-500/30">
              Live Customizer
            </span>
          </div>
          <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 mt-1">
            Customize the public portal footer branding, dealership branches, contact numbers, and copyright text.
          </p>
        </div>

        <button
          type="button"
          @click="submit"
          :disabled="form.processing"
          class="px-6 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-lg shadow-teal-950/50 flex items-center gap-2 self-start sm:self-auto disabled:opacity-50 cursor-pointer"
        >
          <Save class="w-4 h-4" />
          <span>{{ form.processing ? 'Saving Changes...' : 'Save Footer Settings' }}</span>
        </button>
      </div>

      <!-- Live Preview Card -->
      <div class="glass-card rounded-3xl p-6 sm:p-8 border border-teal-500/40 light:border-teal-300 bg-slate-950/80 light:bg-slate-50 space-y-4 shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-800 light:border-slate-200 pb-3">
          <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-teal-400 light:text-teal-700">
            <Eye class="w-4 h-4" />
            <span>Live Footer Preview (How It Looks to Public)</span>
          </div>
          <span class="text-[0.65rem] text-slate-500">Updated in real-time as you type</span>
        </div>

        <div class="rounded-2xl p-6 bg-[#05080A] text-slate-400 border border-slate-800/80 grid grid-cols-1 md:grid-cols-4 gap-6 text-xs">
          <!-- Story -->
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-teal-500 text-slate-950 flex items-center justify-center font-bold text-xs">★</span>
              <span class="font-bold text-white tracking-wider uppercase font-heading">HARTONO</span>
            </div>
            <p class="leading-relaxed font-light line-clamp-4">{{ form.brand_story }}</p>
            <div v-if="form.brand_tagline" class="text-teal-400 font-medium italic">"{{ form.brand_tagline }}"</div>
          </div>

          <!-- Business Units -->
          <div>
            <h5 class="text-white font-semibold uppercase tracking-wider mb-2 font-heading">Business Units</h5>
            <ul class="space-y-1.5 text-slate-300">
              <li v-for="unit in businessUnits.slice(0, 5)" :key="unit.id">• {{ unit.name }}</li>
              <li v-if="businessUnits.length === 0">• Mercedes-Benz Dealership</li>
            </ul>
          </div>

          <!-- Branches -->
          <div>
            <h5 class="text-white font-semibold uppercase tracking-wider mb-2 font-heading">Network & Branches</h5>
            <ul class="space-y-1.5">
              <li v-for="(b, idx) in form.branches" :key="idx">
                <strong class="text-slate-200">{{ b.city }}:</strong> {{ b.address }}
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div class="space-y-1.5">
            <h5 class="text-white font-semibold uppercase tracking-wider mb-2 font-heading">Contact & Portal</h5>
            <p><strong class="text-slate-200">Head Office:</strong> {{ form.head_office }}</p>
            <p><strong class="text-slate-200">Phone:</strong> {{ form.phone }}</p>
            <p><strong class="text-slate-200">Event RSVP:</strong> {{ form.rsvp_phone }}</p>
            <p class="text-teal-400 font-semibold pt-1">{{ form.website_label }} ↗</p>
          </div>
        </div>

        <div class="text-center text-[0.7rem] text-slate-500 pt-2 border-t border-slate-800/80">
          {{ form.copyright }}
        </div>
      </div>

      <!-- Settings Form -->
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Section 1: Brand Story & Tagline -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-md">
          <div class="border-b border-slate-800 light:border-slate-200 pb-4">
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
              1. Brand Overview & Tagline
            </h3>
            <p class="text-xs text-slate-400 light:text-slate-600 mt-0.5">
              Shown in the leftmost column beneath the Hartono Raya Motor logo.
            </p>
          </div>

          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Brand Story & Mission *</label>
              <textarea
                v-model="form.brand_story"
                rows="3"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 leading-relaxed"
                placeholder="Pioneering the automotive lifestyle since 1970..."
              ></textarea>
              <p v-if="form.errors.brand_story" class="text-[0.7rem] text-rose-400">{{ form.errors.brand_story }}</p>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Corporate Motto / Tagline</label>
              <input
                v-model="form.brand_tagline"
                type="text"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="e.g. Everlasting, Continuous Growth."
              />
              <p v-if="form.errors.brand_tagline" class="text-[0.7rem] text-rose-400">{{ form.errors.brand_tagline }}</p>
            </div>
          </div>
        </div>

        <!-- Section 2: Dealership Branches & Locations -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-md">
          <div class="flex items-center justify-between border-b border-slate-800 light:border-slate-200 pb-4">
            <div>
              <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
                2. Network & Branches Directory
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600 mt-0.5">
                Add, remove, or modify regional branch dealership locations.
              </p>
            </div>

            <button
              type="button"
              @click="addBranch"
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-teal-500/20 hover:bg-teal-500/30 text-teal-300 light:text-teal-800 light:bg-teal-100 border border-teal-500/30 flex items-center gap-1.5"
            >
              <Plus class="w-3.5 h-3.5" />
              <span>Add Branch</span>
            </button>
          </div>

          <div class="space-y-3">
            <div
              v-for="(branch, index) in form.branches"
              :key="index"
              class="p-4 rounded-2xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex flex-col sm:flex-row items-start sm:items-center gap-3"
            >
              <div class="w-full sm:w-1/3 space-y-1">
                <label class="text-[0.65rem] uppercase font-bold text-slate-400 light:text-slate-600">City / Region</label>
                <input
                  v-model="branch.city"
                  type="text"
                  required
                  placeholder="e.g. Jakarta"
                  class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-semibold"
                />
              </div>

              <div class="w-full sm:flex-1 space-y-1">
                <label class="text-[0.65rem] uppercase font-bold text-slate-400 light:text-slate-600">Address / Description</label>
                <input
                  v-model="branch.address"
                  type="text"
                  required
                  placeholder="e.g. Daan Mogot Km 10 No. 9"
                  class="w-full px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                />
              </div>

              <button
                type="button"
                @click="removeBranch(index)"
                :disabled="form.branches.length <= 1"
                class="sm:mt-5 p-2 rounded-xl text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors disabled:opacity-30 self-end sm:self-auto"
                title="Remove Branch"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Section 3: Contact & Portal Links -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-md">
          <div class="border-b border-slate-800 light:border-slate-200 pb-4">
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
              3. Contact, RSVP & External Portal
            </h3>
            <p class="text-xs text-slate-400 light:text-slate-600 mt-0.5">
              Headquarters location, customer care hotline, and official external web link.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Head Office Address *</label>
              <input
                v-model="form.head_office"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="Jl. Demak No. 166-168, Surabaya"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Phone / Hotline *</label>
              <input
                v-model="form.phone"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="+62 31 531 1306"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Event RSVP WhatsApp / Phone *</label>
              <input
                v-model="form.rsvp_phone"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="+62 877-8222-3724"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">External Website URL *</label>
              <input
                v-model="form.website_url"
                type="url"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="https://hartonomotor-group.com"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Website Button Label *</label>
              <input
                v-model="form.website_label"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
                placeholder="Visit hartonomotor-group.com"
              />
            </div>
          </div>
        </div>

        <!-- Section 4: Copyright Notice -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-md">
          <div class="border-b border-slate-800 light:border-slate-200 pb-4">
            <h3 class="text-base font-bold text-white light:text-slate-900 font-heading">
              4. Bottom Copyright Statement
            </h3>
            <p class="text-xs text-slate-400 light:text-slate-600 mt-0.5">
              Displayed at the very bottom bar of all public pages.
            </p>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Copyright Text *</label>
            <input
              v-model="form.copyright"
              type="text"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              placeholder="© 2026 PT Hartono Raya Motor & Hartono Group. All rights reserved."
            />
          </div>
        </div>

        <!-- Save Floating / Sticky Action -->
        <div class="flex items-center justify-end gap-4 pt-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="px-8 py-3.5 rounded-2xl text-xs font-bold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-xl shadow-teal-950/60 flex items-center gap-2 disabled:opacity-50 cursor-pointer"
          >
            <Save class="w-4 h-4" />
            <span>{{ form.processing ? 'Saving Updates...' : 'Publish Footer Settings' }}</span>
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { Save, Eye, Plus, Trash2 } from 'lucide-vue-next';

const props = defineProps({
  settings: {
    type: Object,
    required: true,
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  brand_story: props.settings.brand_story || '',
  brand_tagline: props.settings.brand_tagline || '',
  branches: Array.isArray(props.settings.branches) && props.settings.branches.length > 0
    ? JSON.parse(JSON.stringify(props.settings.branches))
    : [
        { city: 'Jakarta', address: 'Daan Mogot Km 10 No. 9' },
        { city: 'Surabaya', address: 'Jl. Demak No. 166-168' },
      ],
  head_office: props.settings.head_office || '',
  phone: props.settings.phone || '',
  rsvp_phone: props.settings.rsvp_phone || '',
  website_url: props.settings.website_url || '',
  website_label: props.settings.website_label || '',
  copyright: props.settings.copyright || '',
});

const addBranch = () => {
  form.branches.push({ city: '', address: '' });
};

const removeBranch = (index) => {
  if (form.branches.length > 1) {
    form.branches.splice(index, 1);
  }
};

const submit = () => {
  form.post(route('admin.settings.footer.update'), {
    preserveScroll: true,
  });
};
</script>
