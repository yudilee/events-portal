<template>
  <AdminLayout>
    <Head title="User & Role Management" />

    <div class="space-y-6 max-w-7xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Security & RBAC</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            User Accounts & Roles
          </h1>
        </div>

        <button
          @click="openCreateModal"
          class="px-4 py-2 rounded-xl text-xs font-bold bg-teal-500 hover:bg-teal-400 text-slate-950 flex items-center gap-1.5 self-start sm:self-auto transition-all shadow-md shadow-teal-950/50"
        >
          <Plus class="w-4 h-4" />
          <span>Add User Account</span>
        </button>
      </div>

      <!-- Users Table -->
      <div class="glass-card rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
            <thead class="bg-slate-950/80 light:bg-slate-100 text-slate-400 light:text-slate-600 font-semibold uppercase tracking-wider text-[0.7rem] border-b border-slate-800 light:border-slate-200">
              <tr>
                <th class="px-6 py-4">User Details</th>
                <th class="px-6 py-4">Assigned Role</th>
                <th class="px-6 py-4">Company / Organization</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 light:divide-slate-200">
              <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-900/40 light:hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-bold text-white light:text-slate-900 text-sm font-heading">{{ user.name }}</div>
                  <div class="text-slate-400 light:text-slate-500 font-mono text-[0.7rem]">{{ user.email }}</div>
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold uppercase tracking-wider',
                      user.role === 'admin' ? 'bg-rose-950 light:bg-rose-100 text-rose-300 light:text-rose-800 border border-rose-800 light:border-rose-300' :
                      user.role === 'event_organizer' ? 'bg-amber-950 light:bg-amber-100 text-amber-300 light:text-amber-800 border border-amber-800 light:border-amber-300' :
                      user.role === 'news_agency' ? 'bg-cyan-950 light:bg-cyan-100 text-cyan-300 light:text-cyan-800 border border-cyan-800 light:border-cyan-300' : 'bg-teal-950 light:bg-teal-100 text-teal-300 light:text-teal-800 border border-teal-800 light:border-teal-300'
                    ]"
                  >
                    {{ user.role.replace('_', ' ') }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-slate-200 light:text-slate-800 font-medium">{{ user.company || 'Private User' }}</span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-teal-400 light:text-teal-700 font-semibold uppercase text-[0.65rem]">
                    ● {{ user.status }}
                  </span>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="openEditModal(user)"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-teal-400 light:text-teal-700 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700 light:border-slate-300 transition-colors"
                    >
                      <Edit3 class="w-3.5 h-3.5" />
                    </button>
                    <Link
                      v-if="user.id !== $page.props.auth?.user?.id"
                      :href="route('admin.users.destroy', user.id)"
                      method="delete"
                      as="button"
                      class="p-2 rounded-lg bg-slate-800 light:bg-slate-100 text-rose-400 light:text-rose-600 hover:bg-rose-950 light:hover:bg-rose-100 border border-slate-700 light:border-slate-300 transition-colors"
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
          :pagination="users"
          item-name="users"
          :per-page-options="[10, 15, 25, 50, 'all']"
        />
      </div>

      <!-- User Create/Edit Modal -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-teal-500/40 light:border-teal-300 bg-slate-900 light:bg-white shadow-2xl relative">
          <button @click="showModal = false" class="absolute top-4 right-4 text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black p-1">
            <X class="w-5 h-5" />
          </button>

          <h3 class="text-xl font-bold text-white light:text-slate-900 font-heading mb-6">
            {{ editingUser ? 'Edit User Account' : 'Create User Account' }}
          </h3>

          <form @submit.prevent="submitUser" class="space-y-4 text-xs">
            <div>
              <label class="font-semibold text-slate-200 light:text-slate-700">Full Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div>
              <label class="font-semibold text-slate-200 light:text-slate-700">Email Address *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="font-semibold text-slate-200 light:text-slate-700">Assigned Role *</label>
                <select
                  v-model="form.role"
                  class="w-full mt-1 px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-white light:text-slate-900"
                >
                  <option value="admin">Super Admin</option>
                  <option value="event_organizer">Event Organizer</option>
                  <option value="member">VIP Member</option>
                  <option value="news_agency">News Agency / Media</option>
                  <option value="guest">General Guest</option>
                </select>
              </div>

              <div>
                <label class="font-semibold text-slate-200 light:text-slate-700">Account Status</label>
                <select
                  v-model="form.status"
                  class="w-full mt-1 px-3 py-2 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-white light:text-slate-900"
                >
                  <option value="active">Active</option>
                  <option value="pending">Pending</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>

            <div>
              <label class="font-semibold text-slate-200 light:text-slate-700">Company / Organization</label>
              <input
                v-model="form.company"
                type="text"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div>
              <label class="font-semibold text-slate-200 light:text-slate-700">Password {{ editingUser ? '(leave blank to keep)' : '*' }}</label>
              <input
                v-model="form.password"
                type="password"
                :required="!editingUser"
                class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900"
              />
            </div>

            <div class="pt-2">
              <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 rounded-xl font-bold text-xs bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-lg transition-all"
              >
                {{ editingUser ? 'Update Account' : 'Create Account' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Plus, Edit3, Trash2, X } from 'lucide-vue-next';

defineProps({
  users: {
    type: Object,
    required: true,
  },
});

const showModal = ref(false);
const editingUser = ref(null);

const form = useForm({
  name: '',
  email: '',
  phone: '',
  company: '',
  role: 'member',
  status: 'active',
  member_tier: 'regular',
  password: '',
});

const openCreateModal = () => {
  editingUser.value = null;
  form.reset();
  showModal.value = true;
};

const openEditModal = (user) => {
  editingUser.value = user;
  form.name = user.name;
  form.email = user.email;
  form.phone = user.phone || '';
  form.company = user.company || '';
  form.role = user.role;
  form.status = user.status;
  form.member_tier = user.member_tier || 'regular';
  form.password = '';
  showModal.value = true;
};

const submitUser = () => {
  if (editingUser.value) {
    form.put(route('admin.users.update', editingUser.value.id), {
      onSuccess: () => { showModal.value = false; },
    });
  } else {
    form.post(route('admin.users.store'), {
      onSuccess: () => { showModal.value = false; },
    });
  }
};
</script>
