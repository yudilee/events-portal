<template>
  <div>
    <!-- Floating or Sidebar Trigger Button -->
    <button
      @click="isOpen = true"
      type="button"
      class="flex items-center gap-2.5 px-3 py-2 w-full rounded-xl text-xs font-semibold text-teal-300 light:text-teal-800 bg-teal-950/60 light:bg-teal-50 hover:bg-teal-900/60 light:hover:bg-teal-100 border border-teal-800/40 light:border-teal-200 transition-all shadow-sm group"
      title="System Workflow & Organizer Guide"
    >
      <HelpCircle class="w-4 h-4 text-teal-400 light:text-teal-700 group-hover:rotate-12 transition-transform" />
      <span>Workflow & Guide</span>
    </button>

    <!-- Modal Backdrop -->
    <div
      v-if="isOpen"
      class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 overflow-y-auto animate-fade-in"
      @click.self="isOpen = false"
    >
      <div class="relative w-full max-w-4xl bg-[#091017] light:bg-white border border-slate-800 light:border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-800/80 light:border-slate-200 flex items-center justify-between bg-slate-900/50 light:bg-slate-50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-teal-500/20 light:bg-teal-100 border border-teal-500/40 light:border-teal-300 flex items-center justify-center text-teal-400 light:text-teal-700">
              <BookOpen class="w-5 h-5" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-white light:text-slate-900 font-heading">
                Organizer Workflow & Platform Guide
              </h3>
              <p class="text-xs text-slate-400 light:text-slate-600">
                End-to-end lifecycle guide for managing Hartono Group Corporate & Dealership Events
              </p>
            </div>
          </div>

          <button
            @click="isOpen = false"
            class="p-2 rounded-xl text-slate-400 hover:text-white light:hover:text-black hover:bg-slate-800 light:hover:bg-slate-100 transition-colors"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Guide Body (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-8 text-slate-300 light:text-slate-700 text-xs leading-relaxed">
          
          <!-- Lifecycle Overview Roadmap -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3.5 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 text-center space-y-1">
              <div class="text-[0.65rem] font-bold text-teal-400 light:text-teal-700 uppercase tracking-widest">Phase 1</div>
              <div class="font-bold text-white light:text-slate-900 text-xs">Setup & Launch</div>
              <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Units, Event & Hero Banner</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 text-center space-y-1">
              <div class="text-[0.65rem] font-bold text-teal-400 light:text-teal-700 uppercase tracking-widest">Phase 2</div>
              <div class="font-bold text-white light:text-slate-900 text-xs">Invitations & RSVPs</div>
              <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Registration, VIP & Tickets</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 text-center space-y-1">
              <div class="text-[0.65rem] font-bold text-teal-400 light:text-teal-700 uppercase tracking-widest">Phase 3</div>
              <div class="font-bold text-white light:text-slate-900 text-xs">Event Day Check-In</div>
              <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Live QR Scanner & Wall</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 text-center space-y-1">
              <div class="text-[0.65rem] font-bold text-teal-400 light:text-teal-700 uppercase tracking-widest">Phase 4</div>
              <div class="font-bold text-white light:text-slate-900 text-xs">Post-Event & PR</div>
              <div class="text-[0.65rem] text-slate-400 light:text-slate-500">Recap, Gallery & Wishes</div>
            </div>
          </div>

          <!-- Step-by-Step Flow Accordion/Cards -->
          <div class="space-y-4">
            <!-- Step 1 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-teal-500 text-slate-950 font-bold flex items-center justify-center text-xs">1</span>
                  <span>Create & Configure an Event</span>
                </div>
                <Link :href="route('admin.events.create')" @click="isOpen = false" class="text-teal-400 light:text-teal-700 font-bold hover:underline">
                  Create Event &rarr;
                </Link>
              </div>
              <p>
                Navigate to <strong>Events & Rundowns &rarr; Create Event</strong>. Select the corresponding <strong>Business Unit</strong> (e.g. <em>HR Auto Studio</em> or <em>Mercedes-Benz Dealership</em>), specify venue details, time, max capacity, dress code, and use the <strong>WYSIWYG Rich Editor</strong> to format your story and agenda.
              </p>
            </div>

            <!-- Step 2 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-teal-500 text-slate-950 font-bold flex items-center justify-center text-xs">2</span>
                  <span>Highlight on Front-Page Hero Slider</span>
                </div>
                <Link :href="route('admin.banners.index')" @click="isOpen = false" class="text-teal-400 light:text-teal-700 font-bold hover:underline">
                  Manage Slider &rarr;
                </Link>
              </div>
              <p>
                Go to <strong>Hero Slider / Carousel</strong> to promote newly created events on the homepage. You can import any active event directly, set a live countdown clock target, and reorder slide sequence via drag-and-drop or order indexes.
              </p>
            </div>

            <!-- Step 3 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-teal-500 text-slate-950 font-bold flex items-center justify-center text-xs">3</span>
                  <span>Monitor RSVPs & Manage Tickets</span>
                </div>
                <Link :href="route('admin.registrations.index')" @click="isOpen = false" class="text-teal-400 light:text-teal-700 font-bold hover:underline">
                  View Attendees &rarr;
                </Link>
              </div>
              <p>
                Public visitors and VIP customers register online and immediately receive a <strong>digital e-ticket with a unique QR code</strong>. In <strong>Attendees & RSVPs</strong>, organizers can filter registrations, assign VIP tiers, view confirmation status, or export attendee manifests to CSV.
              </p>
            </div>

            <!-- Step 4 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-cyan-500 text-slate-950 font-bold flex items-center justify-center text-xs">4</span>
                  <span>Reception Desk: Live QR Scanner</span>
                </div>
                <Link :href="route('admin.check-in')" @click="isOpen = false" class="text-cyan-400 light:text-cyan-700 font-bold hover:underline">
                  Open Scanner &rarr;
                </Link>
              </div>
              <p>
                On the day of the event, reception staff can open the <strong>Live QR Scanner</strong> on any phone, tablet, or laptop. Scanning the attendee's digital ticket or paper QR code provides instantaneous check-in validation with audio feedback and live statistics.
              </p>
            </div>

            <!-- Step 5: Doorprize Roulette -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-amber-500/30 light:border-amber-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-amber-400 light:text-amber-700 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-amber-400 text-slate-950 font-bold flex items-center justify-center text-xs">5</span>
                  <span>Stage Entertainment: Live Doorprize Roulette</span>
                </div>
                <Link :href="route('admin.doorprize.index')" @click="isOpen = false" class="text-amber-400 light:text-amber-700 font-bold hover:underline">
                  Doorprize Setup &rarr;
                </Link>
              </div>
              <p>
                In <strong>Doorprize Roulette</strong>, pre-define your event prizes (e.g. <em>Grand Prize: Luxury Watch</em>, <em>2nd Prize: Vouchers</em>). When connected to the venue LED wall, launch <strong>Live Roulette</strong> presentation mode to spin through verified digital guestbook signers with slot-machine sound effects, grand confetti celebrations, and automatic one-by-one winner removal.
              </p>
            </div>

            <!-- Step 6 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-teal-500 text-slate-950 font-bold flex items-center justify-center text-xs">6</span>
                  <span>Post-Event Hub: Media Gallery & Wishes</span>
                </div>
                <Link :href="route('admin.events.index')" @click="isOpen = false" class="text-teal-400 light:text-teal-700 font-bold hover:underline">
                  Manage Events &rarr;
                </Link>
              </div>
              <p>
                After the event concludes, click <strong>Manage &rarr; Post-Event Narrative</strong> on any event to publish high-resolution photos/videos to the public gallery, write the executive event recap, and moderate wishes & selfie uploads from attendees in <strong>Wishes & Community</strong>.
              </p>
            </div>

            <!-- Step 7 -->
            <div class="p-5 rounded-2xl bg-slate-950/40 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 space-y-2">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 font-bold text-sm text-white light:text-slate-900 font-heading">
                  <span class="w-6 h-6 rounded-lg bg-teal-500 text-slate-950 font-bold flex items-center justify-center text-xs">7</span>
                  <span>Press Releases & Media Accreditation</span>
                </div>
                <Link :href="route('admin.news.index')" @click="isOpen = false" class="text-teal-400 light:text-teal-700 font-bold hover:underline">
                  Press CMS &rarr;
                </Link>
              </div>
              <p>
                Publish official press releases and distribute downloadable media kit assets (logos, specs, high-res vehicle photography) to automotive journalists and press agencies via the <strong>Press Releases</strong> console.
              </p>
            </div>
          </div>

          <!-- Roles & Permissions Matrix -->
          <div class="border-t border-slate-800/80 light:border-slate-200 pt-6 space-y-3">
            <h4 class="text-sm font-bold text-white light:text-slate-900 font-heading">User Roles & Access Levels</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div class="p-3.5 rounded-xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-1">
                <div class="font-bold text-white light:text-slate-900 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                  <span>Administrator (Full Access)</span>
                </div>
                <p class="text-[0.7rem] text-slate-400 light:text-slate-500">Can manage users, roles, ecosystem business units, hero sliders, events, scanners, and global platform settings.</p>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-1">
                <div class="font-bold text-white light:text-slate-900 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                  <span>Event Organizer</span>
                </div>
                <p class="text-[0.7rem] text-slate-400 light:text-slate-500">Can create and edit events, manage rundowns, moderate attendees & wishes, scan tickets, and publish galleries.</p>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-1">
                <div class="font-bold text-white light:text-slate-900 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                  <span>VIP Customer / Member</span>
                </div>
                <p class="text-[0.7rem] text-slate-400 light:text-slate-500">Access to member portal at <code>/portal/dashboard</code>, view active ticket passes, download QR tickets.</p>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-950/60 light:bg-slate-50 border border-slate-800 light:border-slate-200 space-y-1">
                <div class="font-bold text-white light:text-slate-900 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                  <span>Press & Media Agency</span>
                </div>
                <p class="text-[0.7rem] text-slate-400 light:text-slate-500">Access to media portal at <code>/media/dashboard</code>, official press releases, and media accreditation requests.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800/80 light:border-slate-200 bg-slate-900/50 light:bg-slate-50 flex items-center justify-between">
          <div class="text-[0.7rem] text-slate-400 light:text-slate-600">
            Need urgent event technical support? Contact IT Support at <strong class="text-teal-400 light:text-teal-700">it@hartonomotor-group.com</strong>
          </div>
          <button
            @click="isOpen = false"
            class="px-5 py-2 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-500 text-slate-950 transition-all shadow-md"
          >
            Got It
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { HelpCircle, BookOpen, X } from 'lucide-vue-next';

const isOpen = ref(false);
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.98); }
  to { opacity: 1; transform: scale(1); }
}
</style>
