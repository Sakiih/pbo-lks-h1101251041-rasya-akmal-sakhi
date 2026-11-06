const routes = {
    dashboard: window.renderDashboard,
    books: window.renderBooks,
    categories: window.renderCategories
};

function renderRoute() {
    const routeName = window.location.hash.replace('#', '') || 'dashboard';
    const route = routes[routeName];
    const app = document.getElementById('app-content');

    if (route) {
        route();
        return;
    }

    app.innerHTML = `
        <section class="rounded-xl bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800">Halaman tidak ditemukan</h2>
            <p class="mt-2 text-slate-500">Silakan pilih Dashboard, Buku, atau Kategori.</p>
        </section>
    `;
}

document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('hashchange', renderRoute);
    renderRoute();
});
