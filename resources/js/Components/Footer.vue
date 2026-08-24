<template>
  <footer class="border-t border-slate-800/80 light:border-slate-200 bg-[#05080A] light:bg-[#EDF3F5] text-slate-400 light:text-slate-600 mt-20 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
        <!-- Brand Story -->
        <div class="space-y-4 md:col-span-1">
          <HartonoLogo size="md" />
          <p class="text-xs leading-relaxed text-slate-400 light:text-slate-600 font-light">
            {{ settings.brand_story }}
          </p>
          <div v-if="settings.brand_tagline" class="pt-2 text-xs text-teal-400 light:text-teal-700 font-medium italic">
            "{{ settings.brand_tagline }}"
          </div>
        </div>

        <!-- Business Units -->
        <div>
          <h4 class="text-sm font-semibold text-white light:text-slate-900 uppercase tracking-wider mb-4 font-heading">
            Business Units
          </h4>
          <ul v-if="businessUnits && businessUnits.length > 0" class="space-y-2.5 text-xs">
            <li v-for="unit in businessUnits" :key="unit.id">
              <Link :href="route('public.events.index', { unit: unit.slug })" class="text-slate-300 light:text-slate-700 hover:text-teal-400 light:hover:text-teal-700 transition-colors">
                {{ unit.name }}
              </Link>
            </li>
          </ul>
          <ul v-else class="space-y-2.5 text-xs">
            <li><span class="text-slate-300 light:text-slate-700">Mercedes-Benz Dealership</span></li>
            <li><span class="text-slate-300 light:text-slate-700">HR Auto Studio (Body & Detailing)</span></li>
            <li><span class="text-slate-300 light:text-slate-700">Hartono Rent Car</span></li>
            <li><span class="text-slate-300 light:text-slate-700">PT Rudy Darma Engineering (Karoseri)</span></li>
            <li><span class="text-slate-300 light:text-slate-700">Hartono Heavy Transport</span></li>
          </ul>
        </div>

        <!-- Dealership Network -->
        <div>
          <h4 class="text-sm font-semibold text-white light:text-slate-900 uppercase tracking-wider mb-4 font-heading">
            Network & Branches
          </h4>
          <ul class="space-y-2.5 text-xs text-slate-400 light:text-slate-600">
            <li v-for="(branch, idx) in settings.branches" :key="idx">
              <strong class="text-slate-200 light:text-slate-900">{{ branch.city }}:</strong> {{ branch.address }}
            </li>
          </ul>
        </div>

        <!-- Corporate Contact & Links -->
        <div class="space-y-3">
          <h4 class="text-sm font-semibold text-white light:text-slate-900 uppercase tracking-wider mb-4 font-heading">
            Contact & Portal
          </h4>
          <p class="text-xs text-slate-400 light:text-slate-600">
            <strong class="text-slate-200 light:text-slate-900">Head Office:</strong> {{ settings.head_office }}
          </p>
          <p class="text-xs text-slate-400 light:text-slate-600">
            <strong class="text-slate-200 light:text-slate-900">Phone:</strong> {{ settings.phone }}
          </p>
          <p class="text-xs text-slate-400 light:text-slate-600">
            <strong class="text-slate-200 light:text-slate-900">Event RSVP:</strong> {{ settings.rsvp_phone }}
          </p>
          <div class="pt-2">
            <a
              :href="settings.website_url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-400 light:text-teal-700 hover:text-teal-300 light:hover:text-teal-800 transition-colors"
            >
              <span>{{ settings.website_label || 'Visit Official Website' }}</span>
              <ExternalLink class="w-3 h-3" />
            </a>
          </div>
        </div>
      </div>

      <!-- Copyright Bar -->
      <div class="border-t border-slate-800/60 light:border-slate-300 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 light:text-slate-600 gap-4">
        <p>{{ settings.copyright }}</p>
        <div class="flex items-center space-x-6">
          <Link :href="route('public.media-kit')" class="hover:text-teal-400 light:hover:text-teal-700 transition-colors">Press & Media Kit</Link>
          <Link :href="route('public.events.index')" class="hover:text-teal-400 light:hover:text-teal-700 transition-colors">Events Calendar</Link>
          <Link :href="route('login')" class="hover:text-teal-400 light:hover:text-teal-700 transition-colors">Staff Login</Link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import HartonoLogo from './HartonoLogo.vue';
import { ExternalLink } from 'lucide-vue-next';

const page = usePage();

const defaultSettings = {
  brand_story: 'Pioneering the automotive lifestyle since 1970. Integrated ecosystem encompassing authorized Mercedes-Benz dealership, body repair, luxury detailing, and industrial haulage.',
  brand_tagline: 'Everlasting, Continuous Growth.',
  branches: [
    { city: 'Jakarta', address: 'Daan Mogot Km 10 No. 9' },
    { city: 'Surabaya', address: 'Jl. Demak No. 166-168' },
    { city: 'Bali', address: 'Denpasar Dealership & Service' },
    { city: 'Semarang', address: 'Central Java Operations' },
  ],
  head_office: 'Jl. Demak No. 166-168, Surabaya',
  phone: '+62 31 531 1306',
  rsvp_phone: '+62 877-8222-3724',
  website_url: 'https://hartonomotor-group.com',
  website_label: 'Visit hartonomotor-group.com',
  copyright: '© 2026 PT Hartono Raya Motor & Hartono Group. All rights reserved.',
};

const settings = computed(() => {
  return page.props.footerSettings || defaultSettings;
});

const businessUnits = computed(() => {
  return page.props.footerBusinessUnits || [];
});
</script>
