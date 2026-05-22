<template>
  <section aria-labelledby="dashboard-heading">
    <div class="mb-6">
      <h2 id="dashboard-heading" class="text-2xl font-semibold text-slate-900">
        Inkommande ärenden
      </h2>
      <p class="mt-1 text-slate-600">
        Översikt över registrerade vårdförfrågningar.
      </p>
    </div>

    <CaseFilters class="mb-4" @filter="handleFilter" />

    <div
      v-if="loading"
      class="rounded-lg border border-slate-200 bg-white p-6 text-slate-600"
      role="status"
      aria-live="polite"
    >
      Hämtar ärenden…
    </div>

    <div
      v-else-if="error"
      class="rounded-lg border border-red-200 bg-red-50 p-6 text-red-800"
      role="alert"
    >
      {{ error }}
    </div>

    <CaseList v-else :cases="cases" />
  </section>
</template>

<script setup>
import { onMounted } from 'vue';
import CaseFilters from '../components/CaseFilters.vue';
import CaseList from '../components/CaseList.vue';
import { useCases } from '../composables/useCases';

const { cases, loading, error, fetchCases } = useCases();

function handleFilter(filters) {
  fetchCases(filters);
}

onMounted(() => {
  fetchCases();
});
</script>
