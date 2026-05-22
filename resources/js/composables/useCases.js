import { ref } from 'vue';
import { casesApi } from '../api/cases';

export function useCases() {
    const cases = ref([]);
    const loading = ref(false);
    const error = ref(null);

    async function fetchCases(filters = {}) {
        loading.value = true;
        error.value = null;

        try {
            const response = await casesApi.list(filters);
            cases.value = response.data ?? [];
        } catch (err) {
            error.value = err.message ?? 'Kunde inte hämta ärenden.';
            cases.value = [];
        } finally {
            loading.value = false;
        }
    }

    return {
        cases,
        loading,
        error,
        fetchCases,
    };
}
