<template>
  <span
    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
    :class="badgeClasses"
    :aria-label="ariaLabel"
  >
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  kind: {
    type: String,
    required: true,
    validator: (value) => ['status', 'priority'].includes(value),
  },
  value: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
});

const statusClasses = {
  new: 'bg-sky-100 text-sky-800',
  contacted: 'bg-amber-100 text-amber-900',
  booked: 'bg-teal-100 text-teal-900',
  closed: 'bg-slate-200 text-slate-700',
};

const priorityClasses = {
  low: 'bg-slate-100 text-slate-700',
  normal: 'bg-blue-100 text-blue-800',
  high: 'bg-orange-100 text-orange-900',
};

const badgeClasses = computed(() => {
  if (props.kind === 'status') {
    return statusClasses[props.value] ?? 'bg-slate-100 text-slate-700';
  }

  return priorityClasses[props.value] ?? 'bg-slate-100 text-slate-700';
});

const ariaLabel = computed(() => {
  const prefix = props.kind === 'status' ? 'Status' : 'Prioritet';

  return `${prefix}: ${props.label}`;
});
</script>
