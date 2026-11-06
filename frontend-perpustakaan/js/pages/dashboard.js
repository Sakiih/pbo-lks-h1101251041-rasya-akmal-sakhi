function getApiData(response) {
    return Array.isArray(response?.data) ? response.data : [];
}

function renderDashboard() {
    const app = document.getElementById('app-content');

    app.innerHTML = `
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Dashboard</h2>
                <p class="mt-1 text-slate-500">Ringkasan data perpustakaan</p>
            </div>
            <div id="dashboard-stats" class="grid gap-5 md:grid-cols-2">
                <div class="rounded-xl bg-blue-600 p-6 text-white shadow-sm">
                    <p class="text-sm font-medium text-blue-100">Total Buku</p>
                    <p class="mt-2 text-4xl font-bold">Memuat...</p>
                </div>
                <div class="rounded-xl bg-orange-500 p-6 text-white shadow-sm">
                    <p class="text-sm font-medium text-orange-100">Kategori</p>
                    <p class="mt-2 text-4xl font-bold">Memuat...</p>
                </div>
            </div>
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-slate-800">5 Buku Terbaru</h3>
                    <a href="#books" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat semua</a>
                </div>
                <div id="recent-books" class="mt-4">
                    <p class="text-slate-500">Memuat data buku...</p>
                </div>
            </section>
        </div>
    `;

    loadDashboardData();
}

async function loadDashboardData() {
    const stats = document.getElementById('dashboard-stats');
    const recentBooks = document.getElementById('recent-books');

    try {
        const [booksResponse, categoriesResponse] = await Promise.all([
            bookService.list(),
            categoryService.list()
        ]);
        const books = getApiData(booksResponse);
        const categories = getApiData(categoriesResponse);

        stats.innerHTML = `
            <div class="rounded-xl bg-blue-600 p-6 text-white shadow-sm">
                <p class="text-sm font-medium text-blue-100">Total Buku</p>
                <p class="mt-2 text-4xl font-bold">${books.length}</p>
            </div>
            <div class="rounded-xl bg-orange-500 p-6 text-white shadow-sm">
                <p class="text-sm font-medium text-orange-100">Kategori</p>
                <p class="mt-2 text-4xl font-bold">${categories.length}</p>
            </div>
        `;

        const recent = books.slice(0, 5);
        if (recent.length === 0) {
            recentBooks.innerHTML = '<p class="text-slate-500">Belum ada data buku.</p>';
            return;
        }

        recentBooks.innerHTML = `
            <div class="space-y-3">
                ${recent.map((book) => `
                    <article class="rounded-lg border border-slate-200 p-4">
                        <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                            <div>
                                <h4 class="font-semibold text-slate-800">${escapeHtml(book.judul)}</h4>
                                <p class="text-sm text-slate-500">${escapeHtml(book.penulis)}</p>
                            </div>
                            <div class="text-sm text-slate-600 sm:text-right">
                                <p>Tahun: ${escapeHtml(book.tahun)}</p>
                                <p>Stok: ${escapeHtml(book.stok)}</p>
                            </div>
                        </div>
                    </article>
                `).join('')}
            </div>
        `;
    } catch (error) {
        stats.innerHTML = '<p class="md:col-span-2 rounded-lg bg-red-50 p-4 text-red-700">Gagal memuat statistik.</p>';
        recentBooks.innerHTML = `<p class="rounded-lg bg-red-50 p-4 text-red-700">${escapeHtml(error.message)}</p>`;
    }
}

window.renderDashboard = renderDashboard;
