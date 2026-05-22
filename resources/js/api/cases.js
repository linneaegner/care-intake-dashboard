const BASE_URL = '/api/cases';

async function request(url, options = {}) {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...options.headers,
        },
        ...options,
    });

    let payload;

    try {
        payload = await response.json();
    } catch {
        throw {
            status: response.status,
            message: response.ok ? 'Ogiltigt svar från servern.' : 'Något gick fel.',
            errors: {},
        };
    }

    if (!response.ok) {
        throw {
            status: response.status,
            message: payload.message ?? 'Något gick fel.',
            errors: payload.errors ?? {},
        };
    }

    return payload;
}

function buildQuery(params) {
    const query = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
        if (value !== '' && value !== null && value !== undefined) {
            query.set(key, value);
        }
    });

    const queryString = query.toString();

    return queryString ? `?${queryString}` : '';
}

export const casesApi = {
    list(params = {}) {
        return request(`${BASE_URL}${buildQuery(params)}`);
    },

    get(id) {
        return request(`${BASE_URL}/${id}`);
    },

    create(data) {
        return request(BASE_URL, {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },

    updateStatus(id, status) {
        return request(`${BASE_URL}/${id}/status`, {
            method: 'PATCH',
            body: JSON.stringify({ status }),
        });
    },

    updateNote(id, internalNote) {
        return request(`${BASE_URL}/${id}/note`, {
            method: 'PATCH',
            body: JSON.stringify({ internal_note: internalNote }),
        });
    },
};
