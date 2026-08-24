<template>
  <PublicLayout>
    <Head title="Official Media Kit & Brand Guidelines" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="max-w-3xl mb-12 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-cyan-400 light:text-cyan-700">Media Center</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 font-heading tracking-tight">
          Brand Assets & Media Kit
        </h1>
        <p class="text-sm sm:text-base text-slate-300 light:text-slate-600 font-light leading-relaxed">
          Official logos, brand identity guidelines, and media guidelines for press agencies and event partners.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Logo Pack Card -->
        <div class="glass-card rounded-3xl p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-lg light:shadow-sm">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading">Hartono Group Corporate Logo</h3>
            <span class="text-xs px-2.5 py-1 rounded-full bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800/40 light:border-teal-300 font-mono">SVG / PNG</span>
          </div>

          <!-- Logo Preview Box -->
          <div class="p-8 bg-[#070B0E] light:bg-slate-900 rounded-2xl border border-slate-800/80 light:border-slate-300 flex items-center justify-center shadow-inner">
            <HartonoLogo size="lg" />
          </div>

          <p class="text-xs text-slate-400 light:text-slate-600 font-light leading-relaxed">
            The flowing wave motif represents continuous momentum and forward growth since 1970 across all subsidiary companies.
          </p>

          <a
            href="/Viziotec - Hartono Group Final Logo Concept.pdf"
            download
            class="w-full py-3 rounded-xl bg-teal-600 hover:bg-teal-500 text-slate-950 font-bold text-xs flex items-center justify-center gap-2 transition-colors shadow-md"
          >
            <Download class="w-4 h-4" />
            <span>Download Official Brand Concept (PDF)</span>
          </a>
        </div>

        <!-- Color Palette Card -->
        <div class="glass-card rounded-3xl p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-lg light:shadow-sm">
          <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading">Corporate Color Palette</h3>

          <div class="space-y-3">
            <div class="p-3.5 rounded-2xl bg-slate-900 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#0E8B8B] border border-white/20 shadow-sm"></div>
                <div>
                  <div class="text-xs font-bold text-white light:text-slate-900">Hartono Primary Teal</div>
                  <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Flowing wave emblem & corporate primary</div>
                </div>
              </div>
              <span class="font-mono text-xs text-teal-400 light:text-teal-700 font-bold">#0E8B8B</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-900 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#00B4B4] border border-white/20 shadow-sm"></div>
                <div>
                  <div class="text-xs font-bold text-white light:text-slate-900">Hartono Vibrant Cyan</div>
                  <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Digital glow and modern accents</div>
                </div>
              </div>
              <span class="font-mono text-xs text-cyan-400 light:text-cyan-700 font-bold">#00B4B4</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-900 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#070B0E] border border-white/20 shadow-sm"></div>
                <div>
                  <div class="text-xs font-bold text-white light:text-slate-900">Obsidian Slate</div>
                  <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Luxury automotive backdrop</div>
                </div>
              </div>
              <span class="font-mono text-xs text-slate-400 light:text-slate-600 font-bold">#070B0E</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-900 light:bg-slate-50 border border-slate-800 light:border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#E53935] border border-white/20 shadow-sm"></div>
                <div>
                  <div class="text-xs font-bold text-white light:text-slate-900">Crimson Accent</div>
                  <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Highlight dividers and performance cues</div>
                </div>
              </div>
              <span class="font-mono text-xs text-rose-400 light:text-rose-600 font-bold">#E53935</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import HartonoLogo from '../../../Components/HartonoLogo.vue';
import { Download } from 'lucide-vue-next';
</script>
