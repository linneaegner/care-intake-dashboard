<template>
  <section aria-labelledby="case-detail-heading">
    <div class="mb-6">
      <RouterLink
        to="/"
        class="text-sm font-medium text-care-700 hover:text-care-800"
      >
        ← Tillbaka till ärenden
      </RouterLink>

      <h2 id="case-detail-heading" class="mt-3 text-2xl font-semibold text-slate-900">
        Ärendedetalj
      </h2>
    </div>

    <div
      v-if="loading"
      class="rounded-lg border border-slate-200 bg-white p-6 text-slate-600"
      role="status"
      aria-live="polite"
    >
      Hämtar ärende…
    </div>

    <div
      v-else-if="error"
      class="rounded-lg border border-red-200 bg-red-50 p-6 text-red-800"
      role="alert"
    >
      {{ error }}
    </div>

    <div
      v-else-if="caseItem"
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
    >
      <p class="text-sm text-slate-500">ID {{ caseItem.id }}</p>
      <h3 class="mt-1 text-xl font-semibold text-slate-900">{{ caseItem.alias }}</h3>
      <dl class="mt-4 grid gap-3 sm:grid-cols-2">
        <div>
          <dt class="text-sm text-slate-500">Status</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.status_label }}</dd>
        </div>
        <div>
          <dt class="text-sm text-slate-500">Prioritet</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.priority_label }}</dd>
        </div>
        <div>
          <dt class="text-sm text-slate-500">Ärendetyp</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.case_type_label }}</dd>
        </div>
        <div>
          <dt class="text-sm text-slate-500">Kontaktkanal</dt>
          <dd class="font-medium text-slate-900">{{ caseItem.contact_channel_label }}</dd>
        </div>
      </dl>
      <p class="mt-4 text-sm text-slate-500">
        Full detaljvy med statusbyte och tidslinje byggs i steg 7 (CaseDetail).
      </p>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { casesApi } from '../api/cases';

const route = useRoute();
const caseItem = ref(null);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
  try {
    const response = await casesApi.get(route.params.id);
    caseItem.value = response.data;
  } catch (err) {
    error.value = err.message ?? 'Kunde inte hämta ärendet.';
  } finally {
    loading.value = false;
  }
});
</script>
