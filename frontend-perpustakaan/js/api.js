class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl.replace(/\/$/, '');
    }

    async request(path, options = {}) {
        const config = { ...options };
        const headers = new Headers(options.headers || {});

        if (config.body !== undefined && !(config.body instanceof FormData)) {
            headers.set('Content-Type', 'application/json');
            config.body = JSON.stringify(config.body);
        }

        config.headers = headers;

        let response;
        try {
            response = await fetch(`${this.baseUrl}${path}`, config);
        } catch (error) {
            throw new Error(`Gagal terhubung ke API: ${error.message}`);
        }

        let result = null;
        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            result = await response.json();
        }

        if (!response.ok) {
            throw new Error(result?.message || `Request gagal (${response.status})`);
        }

        return result;
    }

    get(path) {
        return this.request(path);
    }

    post(path, data) {
        return this.request(path, { method: 'POST', body: data });
    }

    put(path, data) {
        return this.request(path, { method: 'PUT', body: data });
    }

    delete(path) {
        return this.request(path, { method: 'DELETE' });
    }
}

const apiClient = new ApiClient(window.API_CONFIG.BASE_URL);

const bookService = {
    list: () => apiClient.get('/books'),
    findById: (id) => apiClient.get(`/books/${id}`),
    create: (data) => apiClient.post('/books', data),
    update: (id, data) => apiClient.put(`/books/${id}`, data),
    delete: (id) => apiClient.delete(`/books/${id}`)
};

const categoryService = {
    list: () => apiClient.get('/categories'),
    findById: (id) => apiClient.get(`/categories/${id}`),
    create: (data) => apiClient.post('/categories', data),
    update: (id, data) => apiClient.put(`/categories/${id}`, data),
    delete: (id) => apiClient.delete(`/categories/${id}`)
};

window.ApiClient = ApiClient;
window.apiClient = apiClient;
window.bookService = bookService;
window.categoryService = categoryService;
