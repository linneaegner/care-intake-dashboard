<template>
  <section aria-labelledby="create-case-heading">
    <div class="mb-6">
      <RouterLink
        to="/"
        class="text-sm font-medium text-care-700 hover:text-care-800"
      >
        ← Tillbaka till ärenden
      </RouterLink>

      <h2 id="create-case-heading" class="mt-3 text-2xl font-semibold text-slate-900">
        Registrera nytt ärende
      </h2>
      <p class="mt-1 text-slate-600">
        Använd fiktiva alias — inga riktiga personuppgifter.
      </p>
    </div>

    <CaseForm />
  </section>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import CaseForm from '../components/CaseForm.vue';
</script>
