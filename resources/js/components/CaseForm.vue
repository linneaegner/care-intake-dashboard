<template>
  <form
    class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"
    novalidate
    @submit.prevent="submit"
  >
    <div
      v-if="successMessage"
      class="mb-4 rounded-md border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-900"
      role="status"
      aria-live="polite"
    >
      {{ successMessage }}
    </div>

    <div
      v-if="formError"
      class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
      role="alert"
    >
      {{ formError }}
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div class="sm:col-span-2">
        <label for="alias" class="mb-1 block text-sm font-medium text-slate-700">
          Alias eller referensnamn <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <input
          id="alias"
          v-model="form.alias"
          type="text"
          required
          autocomplete="off"
          placeholder="t.ex. Klient A, Referens 1042"
          class="w-full rounded-md border px-3 py-2 text-sm text-slate-900"
          :class="fieldClass('alias')"
          :aria-invalid="Boolean(errors.alias)"
          :aria-describedby="errors.alias ? 'alias-error' : undefined"
        >
        <p
          v-if="errors.alias"
          id="alias-error"
          class="mt-1 text-sm text-red-700"
          role="alert"
        >
          {{ errors.alias[0] }}
        </p>
      </div>

      <div>
        <label for="contact_channel" class="mb-1 block text-sm font-medium text-slate-700">
          Kontaktkanal <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <select
          id="contact_channel"
          v-model="form.contact_channel"
          required
          class="w-full rounded-md border px-3 py-2 text-sm text-slate-900"
          :class="fieldClass('contact_channel')"
          :aria-invalid="Boolean(errors.contact_channel)"
          :aria-describedby="errors.contact_channel ? 'contact-channel-error' : undefined"
        >
          <option value="" disabled>Välj kanal</option>
          <option
            v-for="option in contactChannelOptions"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
        <p
          v-if="errors.contact_channel"
          id="contact-channel-error"
          class="mt-1 text-sm text-red-700"
          role="alert"
        >
          {{ errors.contact_channel[0] }}
        </p>
      </div>

      <div>
        <label for="case_type" class="mb-1 block text-sm font-medium text-slate-700">
          Ärendetyp <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <select
          id="case_type"
          v-model="form.case_type"
          required
          class="w-full rounded-md border px-3 py-2 text-sm text-slate-900"
          :class="fieldClass('case_type')"
          :aria-invalid="Boolean(errors.case_type)"
          :aria-describedby="errors.case_type ? 'case-type-error' : undefined"
        >
          <option value="" disabled>Välj typ</option>
          <option
            v-for="option in caseTypeOptions"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
        <p
          v-if="errors.case_type"
          id="case-type-error"
          class="mt-1 text-sm text-red-700"
          role="alert"
        >
          {{ errors.case_type[0] }}
        </p>
      </div>

      <div class="sm:col-span-2">
        <label for="priority" class="mb-1 block text-sm font-medium text-slate-700">
          Prioritet
        </label>
        <select
          id="priority"
          v-model="form.priority"
          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900"
          :aria-invalid="Boolean(errors.priority)"
          :aria-describedby="errors.priority ? 'priority-error' : undefined"
        >
          <option
            v-for="option in priorityOptions"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
        <p
          v-if="errors.priority"
          id="priority-error"
          class="mt-1 text-sm text-red-700"
          role="alert"
        >
          {{ errors.priority[0] }}
        </p>
      </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
      <button
        type="submit"
        class="rounded-md bg-care-700 px-4 py-2 text-sm font-medium text-white hover:bg-care-800 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="submitting"
      >
        {{ submitting ? 'Registrerar…' : 'Registrera ärende' }}
      </button>

      <RouterLink
        to="/"
        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
      >
        Avbryt
      </RouterLink>
    </div>
  </form>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { casesApi } from '../api/cases';

const router = useRouter();

const form = reactive({
  alias: '',
  contact_channel: '',
  case_type: '',
  priority: 'normal',
});

const errors = reactive({});
const formError = ref(null);
const successMessage = ref(null);
const submitting = ref(false);

const contactChannelOptions = [
  { value: 'web_form', label: 'Webbformulär' },
  { value: 'email', label: 'E-post' },
  { value: 'phone', label: 'Telefon' },
  { value: 'referral', label: 'Remiss' },
];

const caseTypeOptions = [
  { value: 'therapy', label: 'Terapi' },
  { value: 'npf_assessment', label: 'NPF-utredning' },
  { value: 'occupational_health', label: 'Företagshälsa' },
];

const priorityOptions = [
  { value: 'low', label: 'Låg' },
  { value: 'normal', label: 'Normal' },
  { value: 'high', label: 'Hög' },
];

function fieldClass(field) {
  return errors[field]
    ? 'border-red-400 focus:border-red-500'
    : 'border-slate-300';
}

function clearErrors() {
  Object.keys(errors).forEach((key) => delete errors[key]);
  formError.value = null;
}

async function submit() {
  clearErrors();
  submitting.value = true;

  try {
    const response = await casesApi.create({ ...form });
    successMessage.value = 'Ärendet registrerades.';

    setTimeout(() => {
      router.push(`/cases/${response.data.id}`);
    }, 400);
  } catch (err) {
    if (err.errors && Object.keys(err.errors).length) {
      Object.assign(errors, err.errors);
    } else {
      formError.value = err.message ?? 'Kunde inte registrera ärendet.';
    }
  } finally {
    submitting.value = false;
  }
}
</script>
