<template>
  <AdminLayout>
    <Head :title="event ? `Edit Event — ${event.title}` : 'Create New Event'" />

    <div class="max-w-4xl mx-auto space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-teal-400 light:text-teal-700">Event Builder</span>
          <h1 class="text-2xl font-extrabold text-white light:text-slate-900 font-heading mt-0.5">
            {{ event ? 'Edit Event Details' : 'Create New Corporate Event' }}
          </h1>
        </div>

        <div v-if="event" class="flex items-center gap-2">
          <Link
            :href="route('admin.events.media.index', event.id)"
            class="px-3.5 py-2 rounded-xl bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-slate-200 light:text-slate-800 hover:text-white text-xs font-semibold flex items-center gap-1.5 transition-all border border-slate-700 light:border-slate-300"
          >
            <Camera class="w-3.5 h-3.5 text-teal-400" />
            <span>Manage Gallery</span>
          </Link>
          <Link
            :href="route('admin.events.post-event', event.id)"
            class="px-3.5 py-2 rounded-xl bg-teal-500/20 light:bg-teal-50 hover:bg-teal-500/30 text-teal-300 light:text-teal-800 border border-teal-500/40 light:border-teal-300 text-xs font-semibold flex items-center gap-1.5 transition-all"
          >
            <Sparkles class="w-3.5 h-3.5" />
            <span>Post-Event Hub</span>
          </Link>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Main Event Information -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            General Information
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Business Unit -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Business Unit</label>
              <select
                v-model="form.business_unit_id"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              >
                <option :value="null">Corporate / Hartono Group HQ</option>
                <option v-for="u in businessUnits" :key="u.id" :value="u.id">{{ u.name }}</option>
              </select>
            </div>

            <!-- Event Type -->
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Event Type *</label>
              <select
                v-model="form.event_type"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              >
                <option value="customer_gathering">Customer Gathering</option>
                <option value="soft_opening">Soft Opening & Ceremony</option>
                <option value="exhibition">Exhibition / Expo</option>
                <option value="press_conference">Press Conference</option>
                <option value="test_drive">VIP Test Drive Experience</option>
              </select>
            </div>

            <!-- Title -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Event Title *</label>
              <input
                v-model="form.title"
                type="text"
                required
                placeholder="e.g. Customer Gathering CV HRM Jakarta & Soft Opening HR Auto Studio"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
              <div v-if="form.errors.title" class="text-rose-400 text-xs">{{ form.errors.title }}</div>
            </div>

            <!-- Subtitle -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Subtitle / Tagline</label>
              <input
                v-model="form.subtitle"
                type="text"
                placeholder="e.g. Body Repair & Detailing Soft Launch & Customer Appreciation"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <!-- Short Description (for Cards & SEO) -->
            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Short Summary (for Event Cards & Meta)</label>
              <textarea
                v-model="form.description"
                rows="2"
                required
                placeholder="Brief 1-2 sentence overview shown in event listings and invitations..."
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              ></textarea>
            </div>

            <!-- Rich Content Body (Tiptap WYSIWYG) -->
            <div class="space-y-2 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700 flex items-center justify-between">
                <span>Full Event Body Content (WYSIWYG Editor)</span>
                <span class="text-teal-400 light:text-teal-700 font-mono text-[0.7rem]">Supports Headings, Lists, Quotes & Images</span>
              </label>
              <RichTextEditor
                v-model="form.content_html"
                placeholder="Write rich event highlights, detailed facility overview, speech summaries, or embedded photos..."
              />
            </div>
          </div>
        </div>

        <!-- Venue & Date -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
          <h2 class="text-base font-bold text-white light:text-slate-900 font-heading border-b border-slate-800 light:border-slate-200 pb-3">
            Schedule & Venue Details
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Event Date *</label>
              <input
                v-model="form.date"
                type="date"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Start Time *</label>
              <input
                v-model="form.start_time"
                type="text"
                placeholder="10:00"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">End Time</label>
              <input
                v-model="form.end_time"
                type="text"
                placeholder="13:00"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Dress Code *</label>
              <input
                v-model="form.dress_code"
                type="text"
                placeholder="Smart Casual"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Venue Name *</label>
              <input
                v-model="form.venue_name"
                type="text"
                placeholder="PT Hartono Raya Motor Jakarta"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Venue Address</label>
              <input
                v-model="form.venue_address"
                type="text"
                placeholder="Jl. Raya Daan Mogot Km 10 No. 9, Jakarta"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Max Guest Capacity *</label>
              <input
                v-model="form.max_capacity"
                type="number"
                min="1"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5 sm:col-span-2">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">RSVP Help Phone</label>
              <input
                v-model="form.rsvp_phone"
                type="text"
                placeholder="087782223724"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-semibold text-slate-200 light:text-slate-700">Publish Status *</label>
              <select
                v-model="form.status"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-sm text-white light:text-slate-900 focus:outline-none focus:border-teal-400 font-medium"
              >
                <option value="published">Published (Active)</option>
                <option value="rescheduled">⚠️ Rescheduled / Postponed</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
              </select>
            </div>
          </div>

          <!-- Event Rescheduling & Postponement Notice (Shown when status is rescheduled or notice exists) -->
          <div
            v-if="form.status === 'rescheduled' || form.reschedule_notice"
            class="p-5 rounded-2xl bg-amber-950/30 light:bg-amber-50 border-2 border-amber-500/50 light:border-amber-300 space-y-4 animate-in fade-in duration-200"
          >
            <div class="flex items-start gap-3">
              <div class="p-2 rounded-xl bg-amber-500/20 text-amber-400 light:text-amber-700 shrink-0">
                <AlertTriangle class="w-5 h-5" />
              </div>
              <div class="space-y-1">
                <h4 class="text-sm font-bold text-amber-300 light:text-amber-900 font-heading">
                  Event Rescheduled / Postponed Announcement
                </h4>
                <p class="text-xs text-amber-200/80 light:text-amber-800 leading-relaxed">
                  This announcement will be displayed prominently at the top of the event website, ticket wallet, and digital guestbook. All registered attendees' QR passes remain valid for the new date.
                </p>
              </div>
            </div>

            <!-- Reschedule Option Selector (Set Date vs TBA) -->
            <div class="space-y-2">
              <label class="block text-xs font-bold uppercase tracking-wider text-amber-300 light:text-amber-900">
                Pilih Tipe Penjadwalan Ulang (Reschedule Mode):
              </label>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                  type="button"
                  @click="form.is_date_tba = false"
                  class="p-3.5 rounded-2xl border text-left transition-all flex items-start gap-3 cursor-pointer"
                  :class="!form.is_date_tba ? 'bg-amber-500/20 border-amber-400 text-white shadow-md ring-1 ring-amber-400/50' : 'bg-slate-900/60 light:bg-white border-slate-700 light:border-slate-300 text-slate-400 hover:border-slate-500'"
                >
                  <div class="w-4 h-4 rounded-full border-2 mt-0.5 flex items-center justify-center shrink-0" :class="!form.is_date_tba ? 'border-amber-400 bg-amber-400' : 'border-slate-600'">
                    <div v-if="!form.is_date_tba" class="w-1.5 h-1.5 rounded-full bg-slate-950"></div>
                  </div>
                  <div>
                    <div class="text-xs font-bold text-slate-100 light:text-slate-900 flex items-center gap-1.5">
                      <span>📅 Tetapkan Tanggal Baru (Set Date)</span>
                    </div>
                    <div class="text-[0.7rem] text-slate-400 light:text-slate-600 mt-0.5">
                      Acara sudah memiliki tanggal pengganti dan jam pasti.
                    </div>
                  </div>
                </button>

                <button
                  type="button"
                  @click="form.is_date_tba = true"
                  class="p-3.5 rounded-2xl border text-left transition-all flex items-start gap-3 cursor-pointer"
                  :class="form.is_date_tba ? 'bg-cyan-500/20 border-cyan-400 text-white shadow-md ring-1 ring-cyan-400/50' : 'bg-slate-900/60 light:bg-white border-slate-700 light:border-slate-300 text-slate-400 hover:border-slate-500'"
                >
                  <div class="w-4 h-4 rounded-full border-2 mt-0.5 flex items-center justify-center shrink-0" :class="form.is_date_tba ? 'border-cyan-400 bg-cyan-400' : 'border-slate-600'">
                    <div v-if="form.is_date_tba" class="w-1.5 h-1.5 rounded-full bg-slate-950"></div>
                  </div>
                  <div>
                    <div class="text-xs font-bold text-slate-100 light:text-slate-900 flex items-center gap-1.5">
                      <span>⏳ Tanggal Belum Ditentukan (TBA)</span>
                    </div>
                    <div class="text-[0.7rem] text-slate-400 light:text-slate-600 mt-0.5">
                      Tanggal baru akan diumumkan segera (To Be Announced Shortly).
                    </div>
                  </div>
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-amber-300 light:text-amber-900 mb-1">
                  Original Event Date (Jadwal Semula)
                </label>
                <input
                  v-model="form.original_date"
                  type="date"
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-amber-700/60 light:border-amber-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400"
                />
              </div>

              <div v-if="!form.is_date_tba">
                <label class="block text-xs font-bold uppercase tracking-wider text-amber-300 light:text-amber-900 mb-1">
                  New Event Date (Jadwal Baru)
                </label>
                <input
                  v-model="form.date"
                  type="date"
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-900 light:bg-white border border-amber-700/60 light:border-amber-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400"
                />
              </div>
              <div v-else class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-center">
                <span class="text-xs font-mono font-black text-amber-300 light:text-amber-800 uppercase tracking-wider">
                  ⏳ NEW DATE: TO BE ANNOUNCED SHORTLY
                </span>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-amber-300 light:text-amber-900 mb-1">
                Official Reschedule Reason / Announcement Message *
              </label>
              <textarea
                v-model="form.reschedule_notice"
                rows="3"
                placeholder="Contoh: Sehubungan dengan penyempurnaan fasilitas showroom dan penyesuaian agenda manajemen, acara dijadwalkan ulang menjadi tanggal..."
                class="w-full p-3 rounded-xl bg-slate-900 light:bg-white border border-amber-700/60 light:border-amber-300 text-xs text-white light:text-slate-900 focus:outline-none focus:border-amber-400 leading-relaxed"
              ></textarea>
            </div>
          </div>

          <!-- Feature Toggles -->
          <div class="pt-4 border-t border-slate-800 light:border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="form.is_registration_enabled"
                type="checkbox"
                class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
              />
              <span class="text-xs text-slate-300 light:text-slate-700">Allow Online Registration & RSVP E-Tickets</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="form.is_guestbook_enabled"
                type="checkbox"
                class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
              />
              <span class="text-xs text-slate-300 light:text-slate-700">Enable Digital Guestbook Wall</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="form.is_gallery_enabled"
                type="checkbox"
                class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
              />
              <span class="text-xs text-slate-300 light:text-slate-700">Enable Public Multi-Image Gallery</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="form.is_post_event_enabled"
                type="checkbox"
                class="w-4 h-4 text-teal-500 rounded bg-slate-900 light:bg-white border-slate-700 light:border-slate-300 focus:ring-teal-500"
              />
              <span class="text-xs text-slate-300 light:text-slate-700">Enable Post-Event Recap & Wishes Wall</span>
            </label>
          </div>
        </div>

        <!-- Dynamic Agenda Items Builder -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white shadow-xl space-y-6">
          <div class="flex items-center justify-between border-b border-slate-800 light:border-slate-200 pb-3">
            <h2 class="text-base font-bold text-white light:text-slate-900 font-heading">
              Event Rundown & Agenda Timeline
            </h2>
            <button
              type="button"
              @click="addAgendaItem"
              class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-500 text-slate-950 font-bold text-xs flex items-center gap-1 shadow-md"
            >
              <Plus class="w-3.5 h-3.5" />
              <span>Add Session</span>
            </button>
          </div>

          <div class="space-y-4">
            <div
              v-for="(ag, idx) in form.agendas"
              :key="idx"
              class="p-4 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-3 relative shadow-sm"
            >
              <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-bold text-teal-400 light:text-teal-700">Session #{{ idx + 1 }}</span>
                <button
                  type="button"
                  @click="removeAgendaItem(idx)"
                  class="text-slate-500 hover:text-rose-400 text-xs p-1"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                  <input
                    v-model="ag.time_start"
                    type="text"
                    placeholder="Start (e.g. 10:00)"
                    class="w-full px-3 py-2 rounded-lg bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
                  />
                </div>
                <div>
                  <input
                    v-model="ag.time_end"
                    type="text"
                    placeholder="End (e.g. 10:15)"
                    class="w-full px-3 py-2 rounded-lg bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
                  />
                </div>
                <div class="sm:col-span-2">
                  <input
                    v-model="ag.title"
                    type="text"
                    placeholder="Session Title (e.g. Speech by HRM BOD)"
                    class="w-full px-3 py-2 rounded-lg bg-slate-900 light:bg-white border border-slate-700 light:border-slate-300 text-xs text-white light:text-slate-900"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-800/60 light:border-slate-200">
          <div>
            <button
              v-if="event && $page.props.auth?.user?.role === 'admin'"
              type="button"
              @click="showDeleteModal = true"
              class="px-5 py-3 rounded-xl bg-rose-950/60 light:bg-rose-100 text-rose-400 light:text-rose-700 hover:bg-rose-900 border border-rose-800/40 text-xs font-bold transition-colors flex items-center gap-1.5"
            >
              <Trash2 class="w-4 h-4" />
              <span>Delete Event</span>
            </button>
          </div>

          <div class="flex items-center gap-3">
            <Link
              :href="route('admin.events.index')"
              class="px-6 py-3 rounded-xl bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black text-xs font-semibold"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-8 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-950/60 transition-all"
            >
              {{ event ? 'Save Changes' : 'Create & Publish Event' }}
            </button>
          </div>
        </div>
      </form>

      <!-- High-Contrast Dual-Theme Delete Confirmation Modal -->
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      >
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-md w-full border border-rose-500/40 light:border-rose-300 shadow-2xl space-y-5 bg-slate-950/95 light:bg-white text-center relative">
          <!-- Icon -->
          <div class="w-14 h-14 rounded-2xl bg-rose-950/80 light:bg-rose-100 border border-rose-500/40 light:border-rose-200 flex items-center justify-center text-rose-400 light:text-rose-600 mx-auto">
            <AlertTriangle class="w-7 h-7" />
          </div>

          <div class="space-y-2.5">
            <h3 class="text-xl font-black text-white light:text-slate-900 font-heading">
              Delete Event?
            </h3>
            <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed">
              Are you sure you want to permanently delete:
            </p>
            <div class="p-3 rounded-2xl bg-slate-900/90 light:bg-slate-100 border border-slate-800 light:border-slate-200 text-sm font-bold text-white light:text-slate-900 font-heading">
              "{{ event?.title }}"
            </div>

            <!-- Warning Box -->
            <div class="text-[0.75rem] text-rose-300 light:text-rose-800 bg-rose-950/40 light:bg-rose-50 p-3.5 rounded-2xl border border-rose-800/40 light:border-rose-200 text-left space-y-1 mt-3">
              <div class="font-bold text-rose-400 light:text-rose-700 flex items-center gap-1.5">
                <span>⚠️ Irreversible Action:</span>
              </div>
              <p class="leading-relaxed text-rose-300 light:text-rose-900">
                This will permanently remove all linked registrations, attendee QR passes, event agendas, guestbook entries, gallery media, and doorprizes.
              </p>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex items-center gap-3 pt-2">
            <button
              type="button"
              @click="showDeleteModal = false"
              :disabled="isDeleting"
              class="flex-1 py-3 rounded-xl text-xs font-bold bg-slate-800 light:bg-slate-200 hover:bg-slate-700 light:hover:bg-slate-300 text-slate-300 light:text-slate-700 transition-colors"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="confirmDelete"
              :disabled="isDeleting"
              class="flex-1 py-3 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-950/60 transition-all flex items-center justify-center gap-1.5"
            >
              <Trash2 class="w-3.5 h-3.5" />
              <span>{{ isDeleting ? 'Deleting...' : 'Yes, Delete Event' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import RichTextEditor from '../../../Components/RichTextEditor.vue';
import { Plus, Trash2, Camera, Sparkles, AlertTriangle } from 'lucide-vue-next';

const props = defineProps({
  event: {
    type: Object,
    default: null,
  },
  businessUnits: {
    type: Array,
    default: () => [],
  },
});

const showDeleteModal = ref(false);
const isDeleting = ref(false);

const form = useForm({
  business_unit_id: props.event?.business_unit_id || null,
  title: props.event?.title || '',
  subtitle: props.event?.subtitle || '',
  description: props.event?.description || '',
  content_html: props.event?.content_html || '',
  event_type: props.event?.event_type || 'customer_gathering',
  visibility: props.event?.visibility || 'public',
  date: props.event?.date || '2026-08-27',
  start_time: props.event?.start_time || '10:00',
  end_time: props.event?.end_time || '13:00',
  timezone: props.event?.timezone || 'WIB',
  venue_name: props.event?.venue_name || 'PT Hartono Raya Motor Jakarta',
  venue_address: props.event?.venue_address || 'Jl. Raya Daan Mogot Km 10 No. 9, Jakarta',
  venue_map_url: props.event?.venue_map_url || '',
  dress_code: props.event?.dress_code || 'Smart Casual',
  rsvp_contact: props.event?.rsvp_contact || 'HRM Event Desk',
  rsvp_phone: props.event?.rsvp_phone || '087782223724',
  max_capacity: props.event?.max_capacity || 150,
  registration_deadline: props.event?.registration_deadline || null,
  status: props.event?.status || 'published',
  reschedule_notice: props.event?.reschedule_notice || '',
  original_date: props.event?.original_date || '',
  is_date_tba: props.event?.is_date_tba ?? false,
  is_registration_enabled: props.event?.is_registration_enabled ?? true,
  is_guestbook_enabled: props.event?.is_guestbook_enabled ?? true,
  is_gallery_enabled: props.event?.is_gallery_enabled ?? true,
  is_post_event_enabled: props.event?.is_post_event_enabled ?? true,
  agendas: props.event?.agendas || [
    { time_start: '09:45', time_end: '10:15', title: 'Registration & Welcome Drinks' },
    { time_start: '10:15', time_end: '10:25', title: 'Opening Remarks by Board of Directors' },
  ],
});

const addAgendaItem = () => {
  form.agendas.push({ time_start: '', time_end: '', title: '', description: '' });
};

const removeAgendaItem = (index) => {
  form.agendas.splice(index, 1);
};

const submit = () => {
  if (props.event) {
    form.put(route('admin.events.update', props.event.id));
  } else {
    form.post(route('admin.events.store'));
  }
};

const confirmDelete = () => {
  if (!props.event || isDeleting.value) return;
  isDeleting.value = true;

  router.delete(route('admin.events.destroy', props.event.id), {
    onFinish: () => {
      isDeleting.value = false;
      showDeleteModal.value = false;
    },
  });
};
</script>
