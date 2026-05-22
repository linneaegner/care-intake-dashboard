<template>
  <form
    class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm"
    aria-label="Filtrera ärenden"
    @submit.prevent="emitFilters"
  >
    <fieldset class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <legend class="sr-only">Filter</legend>

      <div>
        <label for="filter-search" class="mb-1 block text-sm font-medium text-slate-700">
          Sök
        </label>
        <input
          id="filter-search"
          v-model="localFilters.search"
          type="search"
          placeholder="Alias eller ärendetyp…"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400"
          @input="debouncedEmit"
        >
      </div>

      <div>
        <label for="filter-status" class="mb-1 block text-sm font-medium text-slate-700">
          Status
        </label>
        <select
          id="filter-status"
          v-model="localFilters.status"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900"
          @change="emitFilters"
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

      <div>
        <label for="filter-priority" class="mb-1 block text-sm font-medium text-slate-700">
          Prioritet
        </label>
        <select
          id="filter-priority"
          v-model="localFilters.priority"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900"
          @change="emitFilters"
        >
          <option
            v-for="option in priorityOptions"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
      </div>

      <div class="flex items-end">
        <button
          type="button"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
          @click="resetFilters"
        >
          Rensa filter
        </button>
      </div>
    </fieldset>
  </form>
</template>

<script setup>
import { reactive } from 'vue';

const emit = defineEmits(['filter']);

const statusOptions = [
  { value: '', label: 'Alla statusar' },
  { value: 'new', label: 'Ny' },
  { value: 'contacted', label: 'Kontaktad' },
  { value: 'booked', label: 'Bokad' },
  { value: 'closed', label: 'Stängd' },
];

const priorityOptions = [
  { value: '', label: 'All prioritet' },
  { value: 'low', label: 'Låg' },
  { value: 'normal', label: 'Normal' },
  { value: 'high', label: 'Hög' },
];

const localFilters = reactive({
  search: '',
  status: '',
  priority: '',
});

let debounceTimer = null;

function emitFilters() {
  emit('filter', { ...localFilters });
}

function debouncedEmit() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(emitFilters, 300);
}

function resetFilters() {
  localFilters.search = '';
  localFilters.status = '';
  localFilters.priority = '';
  emitFilters();
}
</script>
