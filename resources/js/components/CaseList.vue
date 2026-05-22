<template>
  <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <p
      v-if="cases.length === 0"
      class="p-6 text-center text-slate-600"
      role="status"
    >
      Inga ärenden matchar dina filter.
    </p>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200 text-sm">
        <caption class="sr-only">
          Lista över inkommande ärenden
        </caption>
        <thead class="bg-slate-50">
          <tr>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Alias
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Kontaktkanal
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Ärendetyp
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Prioritet
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Status
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              Skapad
            </th>
            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">
              <span class="sr-only">Åtgärd</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="item in cases"
            :key="item.id"
            class="hover:bg-care-50/50"
          >
            <td class="px-4 py-3 font-medium text-slate-900">
              {{ item.alias }}
            </td>
            <td class="px-4 py-3 text-slate-700">
              {{ item.contact_channel_label }}
            </td>
            <td class="px-4 py-3 text-slate-700">
              {{ item.case_type_label }}
            </td>
            <td class="px-4 py-3">
              <StatusBadge
                kind="priority"
                :value="item.priority"
                :label="item.priority_label"
              />
            </td>
            <td class="px-4 py-3">
              <StatusBadge
                kind="status"
                :value="item.status"
                :label="item.status_label"
              />
            </td>
            <td class="px-4 py-3 text-slate-600">
              <time :datetime="item.created_at">
                {{ formatDate(item.created_at) }}
              </time>
            </td>
            <td class="px-4 py-3">
              <RouterLink
                :to="`/cases/${item.id}`"
                class="font-medium text-care-700 hover:text-care-800"
                :aria-label="`Visa ärende ${item.alias}`"
              >
                Visa
              </RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p class="border-t border-slate-100 px-4 py-3 text-sm text-slate-500">
      Visar {{ cases.length }} ärenden
    </p>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import StatusBadge from './StatusBadge.vue';

defineProps({
  cases: {
    type: Array,
    required: true,
  },
});

function formatDate(value) {
  if (!value) {
    return '—';
  }

  return new Date(value).toLocaleDateString('sv-SE', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}
</script>
