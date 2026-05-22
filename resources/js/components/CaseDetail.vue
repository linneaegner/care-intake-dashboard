<template>
  <div class="space-y-6">
    <div
      v-if="feedback.message"
      class="rounded-md border px-4 py-3 text-sm"
      :class="feedback.type === 'success'
        ? 'border-teal-200 bg-teal-50 text-teal-900'
        : 'border-red-200 bg-red-50 text-red-800'"
      role="status"
      aria-live="polite"
    >
      {{ feedback.message }}
    </div>

    <section
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
      aria-labelledby="case-summary-heading"
    >
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <p class="text-sm text-slate-500">Ärende #{{ caseItem.id }}</p>
          <h3 id="case-summary-heading" class="mt-1 text-2xl font-semibold text-slate-900">
            {{ caseItem.alias }}
          </h3>
          <p class="mt-1 text-sm text-slate-600">
            Skapad
            <time :datetime="caseItem.created_at">{{ formatDateTime(caseItem.created_at) }}</time>
          </p>
        </div>

        <div class="flex flex-wrap gap-2">
          <StatusBadge
            kind="status"
            :value="caseItem.status"
            :label="caseItem.status_label"
          />
          <StatusBadge
            kind="priority"
            :value="caseItem.priority"
            :label="caseItem.priority_label"
          />
        </div>
      </div>

      <dl class="mt-6 grid gap-4 sm:grid-cols-2">
        <div>
          <dt class="text-sm text-slate-500">Kontaktkanal</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.contact_channel_label }}</dd>
        </div>
        <div>
          <dt class="text-sm text-slate-500">Ärendetyp</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.case_type_label }}</dd>
        </div>
      </dl>
    </section>

    <section
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
      aria-labelledby="status-update-heading"
    >
      <h3 id="status-update-heading" class="text-lg font-semibold text-slate-900">
        Uppdatera status
      </h3>
      <p class="mt-1 text-sm text-slate-600">
        Ändringen sparas direkt och loggas i tidslinjen.
      </p>

      <div class="mt-4 flex flex-wrap items-end gap-3">
        <div class="min-w-[12rem] flex-1">
          <label for="case-status" class="mb-1 block text-sm font-medium text-slate-700">
            Status
          </label>
          <select
            id="case-status"
            v-model="selectedStatus"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900"
            :disabled="updatingStatus"
          >
            <option
              v-for="option in statusOptions"
              :key="option.value"
              :value="option.value"
            >
              {{ option.label }}
            </option>
          </select>
        </div>

        <button
          type="button"
          class="rounded-md bg-care-700 px-4 py-2 text-sm font-medium text-white hover:bg-care-800 disabled:cursor-not-allowed disabled:opacity-60"
          :disabled="updatingStatus || selectedStatus === caseItem.status"
          @click="saveStatus"
        >
          {{ updatingStatus ? 'Sparar…' : 'Spara status' }}
        </button>
      </div>
    </section>

    <section
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
      aria-labelledby="internal-note-heading"
    >
      <h3 id="internal-note-heading" class="text-lg font-semibold text-slate-900">
        Intern anteckning
      </h3>
      <p class="mt-1 text-sm text-slate-600">
        Endast för internt bruk. Ingen riktig patientdata i denna demo.
      </p>

      <div class="mt-4">
        <label for="internal-note" class="mb-1 block text-sm font-medium text-slate-700">
          Anteckning
        </label>
        <textarea
          id="internal-note"
          v-model="internalNote"
          rows="4"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900"
          placeholder="t.ex. Kontakt via säker länk…"
          :disabled="updatingNote"
        />
      </div>

      <button
        type="button"
        class="mt-3 rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="updatingNote || internalNote === (caseItem.internal_note ?? '')"
        @click="saveNote"
      >
        {{ updatingNote ? 'Sparar…' : 'Spara anteckning' }}
      </button>
    </section>

    <section
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
      aria-labelledby="timeline-heading"
    >
      <h3 id="timeline-heading" class="text-lg font-semibold text-slate-900">
        Tidslinje
      </h3>

      <ol v-if="caseItem.events?.length" class="mt-4 space-y-4">
        <li
          v-for="event in caseItem.events"
          :key="event.id"
          class="relative border-l-2 border-care-700/30 pl-4"
        >
          <time
            class="text-xs font-medium uppercase tracking-wide text-slate-500"
            :datetime="event.created_at"
          >
            {{ formatDateTime(event.created_at) }}
          </time>
          <p class="mt-1 text-sm font-medium text-slate-900">
            {{ event.event_type_label }}
          </p>
          <p class="text-sm text-slate-600">
            {{ event.description }}
          </p>
        </li>
      </ol>

      <p v-else class="mt-4 text-sm text-slate-500">
        Inga händelser registrerade ännu.
      </p>
    </section>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { casesApi } from '../api/cases';
import StatusBadge from './StatusBadge.vue';

const props = defineProps({
  caseItem: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['updated']);

const selectedStatus = ref(props.caseItem.status);
const internalNote = ref(props.caseItem.internal_note ?? '');
const updatingStatus = ref(false);
const updatingNote = ref(false);
const feedback = ref({ message: '', type: 'success' });

const statusOptions = [
  { value: 'new', label: 'Ny' },
  { value: 'contacted', label: 'Kontaktad' },
  { value: 'booked', label: 'Bokad' },
  { value: 'closed', label: 'Stängd' },
];

watch(
  () => props.caseItem,
  (value) => {
    selectedStatus.value = value.status;
    internalNote.value = value.internal_note ?? '';
  },
  { deep: true },
);

function formatDateTime(value) {
  if (!value) {
    return '—';
  }

  return new Date(value).toLocaleString('sv-SE', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function showFeedback(message, type = 'success') {
  feedback.value = { message, type };

  setTimeout(() => {
    feedback.value = { message: '', type: 'success' };
  }, 3000);
}

async function saveStatus() {
  updatingStatus.value = true;

  try {
    const response = await casesApi.updateStatus(props.caseItem.id, selectedStatus.value);
    emit('updated', response.data);
    showFeedback(`Status uppdaterad till ${response.data.status_label}.`);
  } catch (err) {
    selectedStatus.value = props.caseItem.status;
    showFeedback(err.message ?? 'Kunde inte uppdatera status.', 'error');
  } finally {
    updatingStatus.value = false;
  }
}

async function saveNote() {
  updatingNote.value = true;

  try {
    const response = await casesApi.updateNote(props.caseItem.id, internalNote.value);
    emit('updated', response.data);
    showFeedback('Intern anteckning sparad.');
  } catch (err) {
    showFeedback(err.message ?? 'Kunde inte spara anteckningen.', 'error');
  } finally {
    updatingNote.value = false;
  }
}
</script>
