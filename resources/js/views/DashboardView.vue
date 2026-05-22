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

    <div
      v-else
      class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
    >
      <p class="text-slate-700">
        <span class="font-semibold text-slate-900">{{ cases.length }}</span>
        ärenden hämtade från API:t.
      </p>
      <p class="mt-2 text-sm text-slate-500">
        Listvy och filter byggs i nästa steg (CaseList, CaseFilters).
      </p>

      <ul v-if="cases.length" class="mt-4 space-y-2">
        <li
          v-for="item in cases.slice(0, 5)"
          :key="item.id"
          class="flex items-center justify-between rounded-md border border-slate-100 px-3 py-2"
        >
          <span class="font-medium text-slate-900">{{ item.alias }}</span>
          <RouterLink
            :to="`/cases/${item.id}`"
            class="text-sm font-medium text-care-700 hover:text-care-800"
          >
            Visa
          </RouterLink>
        </li>
      </ul>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { useCases } from '../composables/useCases';

const { cases, loading, error, fetchCases } = useCases();

onMounted(() => {
  fetchCases();
});
</script>
