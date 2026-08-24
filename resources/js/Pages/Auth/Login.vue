<template>
  <PublicLayout>
    <Head title="Sign In — Portal Access" />

    <div class="max-w-md mx-auto px-4 sm:px-6 py-16">
      <div class="glass-card rounded-3xl p-8 border border-teal-500/30 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
          <HartonoLogo size="md" class="justify-center" />
          <h1 class="text-2xl font-extrabold text-white font-heading pt-2">Portal Sign In</h1>
          <p class="text-xs text-slate-400">
            Access your Member tickets, Media press room, or Organizer back-office.
          </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="text-xs font-semibold text-slate-200">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              placeholder="name@hartonomotor-group.com"
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400 placeholder:text-slate-500"
            />
            <div v-if="form.errors.email" class="text-rose-400 text-xs mt-1">{{ form.errors.email }}</div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-200">Password</label>
            <input
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              placeholder="••••••••"
              class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-teal-400 placeholder:text-slate-500"
            />
            <div v-if="form.errors.password" class="text-rose-400 text-xs mt-1">{{ form.errors.password }}</div>
          </div>

          <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-slate-300">
              <input v-model="form.remember" type="checkbox" class="rounded bg-slate-900 border-slate-700 text-teal-500 focus:ring-0" />
              <span>Remember me</span>
            </label>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-3 rounded-xl font-bold text-xs bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 text-slate-950 shadow-lg shadow-teal-950/60 transition-all flex items-center justify-center gap-2"
          >
            <LogIn class="w-4 h-4" />
            <span>{{ form.processing ? 'Signing In...' : 'Sign In to Portal' }}</span>
          </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-400">
          Don't have an account?
          <Link :href="route('register')" class="text-teal-400 font-semibold hover:underline ml-1">
            Register Portal Account
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
import { LogIn } from 'lucide-vue-next';

const form = useForm({
  email: '',
  password: '',
  remember: true,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>
