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

    <CaseDetail
      v-else-if="caseItem"
      :case-item="caseItem"
      @updated="caseItem = $event"
    />
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import CaseDetail from '../components/CaseDetail.vue';
import { casesApi } from '../api/cases';

const route = useRoute();
const caseItem = ref(null);
const loading = ref(true);
const error = ref(null);

async function loadCase() {
  loading.value = true;
  error.value = null;

  try {
    const response = await casesApi.get(route.params.id);
    caseItem.value = response.data;
  } catch (err) {
    error.value = err.message ?? 'Kunde inte hämta ärendet.';
    caseItem.value = null;
  } finally {
    loading.value = false;
  }
}

onMounted(loadCase);
</script>
