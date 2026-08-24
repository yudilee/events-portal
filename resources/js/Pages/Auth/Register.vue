<template>
  <PublicLayout>
    <Head title="Create Portal Account" />

    <div class="max-w-md mx-auto px-4 sm:px-6 py-16">
      <div class="glass-card rounded-3xl p-8 border border-teal-500/30 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
          <HartonoLogo size="md" class="justify-center" />
          <h1 class="text-2xl font-extrabold text-white font-heading pt-2">Register Account</h1>
          <p class="text-xs text-slate-400">
            Create an account to manage your event passes or access press materials.
          </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Account Type Selector -->
          <div>
            <label class="text-xs font-semibold text-slate-200">Account Type</label>
            <div class="grid grid-cols-2 gap-3 mt-1">
              <button
                type="button"
                @click="form.account_type = 'member'"
                :class="[
                  'py-2 px-3 rounded-xl text-xs font-semibold border transition-all text-center',
                  form.account_type === 'member'
                    ? 'bg-teal-950/80 border-teal-400 text-teal-300'
                    : 'bg-slate-900 border-slate-700 text-slate-400'
                ]"
              >
                VIP Member
              </button>
              <button
                type="button"
                @click="form.account_type = 'news_agency'"
                :class="[
                  'py-2 px-3 rounded-xl text-xs font-semibold border transition-all text-center',
                  form.account_type === 'news_agency'
                    ? 'bg-cyan-950/80 border-cyan-400 text-cyan-300'
                    : 'bg-slate-900 border-slate-700 text-slate-400'
                ]"
              >
                News Agency / Press
              </button>
            </div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Full Name *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
            <div v-if="form.errors.name" class="text-rose-400 text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Email Address *</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
            <div v-if="form.errors.email" class="text-rose-400 text-xs mt-1">{{ form.errors.email }}</div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">WhatsApp / Phone *</label>
            <input
              v-model="form.phone"
              type="tel"
              required
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Company / Publication</label>
            <input
              v-model="form.company"
              type="text"
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Password *</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
            <div v-if="form.errors.password" class="text-rose-400 text-xs mt-1">{{ form.errors.password }}</div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Confirm Password *</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400"
            />
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-3 rounded-xl font-bold text-xs bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-lg shadow-teal-950/60 transition-all flex items-center justify-center gap-2"
          >
            <UserPlus class="w-4 h-4" />
            <span>{{ form.processing ? 'Registering...' : 'Create Portal Account' }}</span>
          </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-400">
          Already have an account?
          <Link :href="route('login')" class="text-teal-400 font-semibold hover:underline ml-1">
            Sign In
          </Link>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import HartonoLogo from '../../Components/HartonoLogo.vue';
import { UserPlus } from 'lucide-vue-next';

const form = useForm({
  name: '',
  email: '',
  phone: '',
  company: '',
  account_type: 'member',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
