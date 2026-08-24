<template>
  <AdminLayout>
    <Head title="Press Releases & CMS" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Media Center CMS</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            Press Releases & News
          </h1>
        </div>

        <Link
          :href="route('admin.news.create')"
          class="px-4 py-2 rounded-xl text-xs font-bold bg-teal-500 hover:bg-teal-400 text-slate-950 flex items-center gap-1.5 self-start sm:self-auto transition-all shadow-md shadow-teal-950/50"
        >
          <Plus class="w-4 h-4" />
          <span>New Press Release</span>
        </Link>
      </div>

      <!-- Releases Table -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">Title & Slug</th>
                <th class="px-6 py-4">Unit / Scope</th>
                <th class="px-6 py-4">Visibility / Embargo</th>
                <th class="px-6 py-4">Published Date</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr v-for="rel in releases.data" :key="rel.id" class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900 text-sm font-heading">{{ rel.title }}</div>
                  <div class="text-[0.7rem] text-slate-400 light:text-slate-500 font-mono">{{ rel.slug }}</div>
                </td>

                <td class="px-6 py-4">
                  <span class="px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300">
                    {{ rel.business_unit?.name || 'Hartono Group HQ' }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2 py-0.5 rounded-full text-[0.65rem] font-bold uppercase',
                      rel.is_embargoed
                        ? 'bg-rose-950 light:bg-rose-100 text-rose-300 light:text-rose-800 border border-rose-800/40 light:border-rose-300'
                        : 'bg-slate-800 light:bg-slate-100 text-slate-300 light:text-slate-700 border border-slate-700 light:border-slate-300'
                    ]"
                  >
                    {{ rel.is_embargoed ? '🔒 Embargoed' : rel.visibility }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <div class="text-slate-200 light:text-slate-800 font-medium">{{ new Date(rel.published_at).toLocaleDateString() }}</div>
                  <div class="text-slate-400 light:text-slate-500 text-[0.7rem]">{{ rel.views_count }} views</div>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link
                      :href="route('admin.news.edit', rel.id)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-teal-400 light:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700 light:border-slate-300 transition-colors"
                      title="Edit Press Release"
                    >
                      <Edit3 class="w-3.5 h-3.5" />
                    </Link>
                    <Link
                      :href="route('admin.news.destroy', rel.id)"
                      method="delete"
                      as="button"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 light:text-rose-600 hover:bg-rose-950 light:hover:bg-rose-100 border border-slate-700 light:border-slate-300 transition-colors"
                      title="Delete Press Release"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Integrated Pagination Component -->
        <Pagination
          :pagination="releases"
          item-name="articles"
          :per-page-options="[10, 15, 25, 50, 'all']"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Plus, Edit3, Trash2 } from 'lucide-vue-next';

defineProps({
  releases: {
    type: Object,
    required: true,
  },
});
</script>
