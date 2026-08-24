<template>
  <AdminLayout>
    <Head :title="release ? `Edit Press Release — ${release.title}` : 'New Press Release'" />

    <div class="max-w-3xl mx-auto space-y-6">
      <div>
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Media CMS</span>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
          {{ release ? 'Edit Press Release' : 'Draft New Press Release' }}
        </h1>
      </div>

      <form @submit.prevent="submit" class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white space-y-6 shadow-xl">
        <div class="space-y-4">
          <div>
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Business Unit / Source</label>
            <select
              v-model="form.business_unit_id"
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            >
              <option :value="null">Hartono Group Corporate HQ</option>
              <option v-for="u in businessUnits" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Headline / Title *</label>
            <input
              v-model="form.title"
              type="text"
              required
              placeholder="e.g. Hartono Group Unveils HR Auto Studio in Jakarta"
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            />
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Executive Summary</label>
            <textarea
              v-model="form.summary"
              rows="2"
              placeholder="Brief 1-2 sentence lead for journalists..."
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            ></textarea>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Full Release Content *</label>
            <textarea
              v-model="form.content"
              rows="8"
              required
              placeholder="Press release body text, quotes, and event details..."
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Visibility</label>
              <select
                v-model="form.visibility"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              >
                <option value="public">Public (Everyone)</option>
                <option value="media_only">Media Only (Accredited Press)</option>
              </select>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Embargo Status</label>
              <div class="mt-2 flex items-center gap-2 text-xs text-slate-300 light:text-slate-700">
                <input v-model="form.is_embargoed" type="checkbox" class="rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 text-rose-500" />
                <span>Embargoed Release</span>
              </div>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800 light:border-slate-200">
          <Link
            :href="route('admin.news.index')"
            class="px-6 py-2.5 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black text-xs font-semibold"
          >
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-8 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg transition-all"
          >
            {{ release ? 'Save Changes' : 'Publish Press Release' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

const props = defineProps({
  release: {
    type: Object,
    default: null,
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
  events: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  business_unit_id: props.release?.business_unit_id || null,
  event_id: props.release?.event_id || null,
  title: props.release?.title || '',
  summary: props.release?.summary || '',
  content: props.release?.content || '',
  visibility: props.release?.visibility || 'public',
  is_embargoed: props.release?.is_embargoed ?? false,
  embargo_until: props.release?.embargo_until || null,
});

const submit = () => {
  if (props.release) {
    form.put(route('admin.news.update', props.release.id));
  } else {
    form.post(route('admin.news.store'));
  }
};
</script>
