function renderBooks() {
    const app = document.getElementById('app-content');

    app.innerHTML = `
        <div class="space-y-6">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Buku</h2>
                    <p class="mt-1 text-slate-500">Daftar buku perpustakaan</p>
                </div>
                <button id="add-book-button" type="button" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-blue-700">
                    + Tambah Buku
                </button>
            </div>
            <div id="books-table-container" class="overflow-x-auto rounded-xl bg-white shadow-sm">
                <p class="p-6 text-slate-500">Memuat data buku...</p>
            </div>
        </div>
        ${bookFormModal()}
    `;

    document.getElementById('add-book-button').addEventListener('click', openBookModal);
    document.getElementById('close-book-modal').addEventListener('click', closeBookModal);
    document.getElementById('cancel-book-modal').addEventListener('click', closeBookModal);
    document.getElementById('book-form').addEventListener('submit', submitBookForm);
    document.getElementById('book-modal').addEventListener('click', (event) => {
        if (event.target.id === 'book-modal') {
            closeBookModal();
        }
    });

    loadBooks();
}

function bookFormModal() {
    return `
        <div id="book-modal" class="fixed inset-0 z-10 hidden items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="book-modal-title">
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 id="book-modal-title" class="text-lg font-semibold text-slate-800">Tambah Buku</h3>
                    <button id="close-book-modal" type="button" class="text-2xl leading-none text-slate-400 hover:text-slate-700" aria-label="Tutup">&times;</button>
                </div>
                <form id="book-form" class="mt-5 space-y-4">
                    <label class="block text-sm font-medium text-slate-700">Judul
                        <input name="judul" type="text" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="block text-sm font-medium text-slate-700">Penulis
                        <input name="penulis" type="text" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="block text-sm font-medium text-slate-700">Tahun
                        <input name="tahun" type="number" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="block text-sm font-medium text-slate-700">Stok
                        <input name="stok" type="number" min="0" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <p id="book-form-error" class="hidden rounded-lg bg-red-50 p-3 text-sm text-red-700"></p>
                    <div class="flex justify-end gap-3 pt-2">
                        <button id="cancel-book-modal" type="button" class="rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    `;
}

async function loadBooks() {
    const container = document.getElementById('books-table-container');

    try {
        const response = await bookService.list();
        const books = Array.isArray(response?.data) ? response.data : [];

        if (books.length === 0) {
            container.innerHTML = '<p class="p-6 text-slate-500">Belum ada data buku.</p>';
            return;
        }

        container.innerHTML = `
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Judul</th>
                        <th class="px-6 py-4 font-semibold">Penulis</th>
                        <th class="px-6 py-4 font-semibold">Tahun</th>
                        <th class="px-6 py-4 font-semibold">Stok</th>
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    ${books.map((book) => `
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">${escapeHtml(book.id)}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(book.judul)}</td>
                            <td class="px-6 py-4">${escapeHtml(book.penulis)}</td>
                            <td class="whitespace-nowrap px-6 py-4">${escapeHtml(book.tahun)}</td>
                            <td class="whitespace-nowrap px-6 py-4">${escapeHtml(book.stok)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-400">-</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        container.innerHTML = `<p class="p-6 text-red-700">Gagal memuat buku: ${escapeHtml(error.message)}</p>`;
    }
}

function openBookModal() {
    const modal = document.getElementById('book-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.querySelector('#book-form input[name="judul"]').focus();
}

function closeBookModal() {
    const modal = document.getElementById('book-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('book-form').reset();
    document.getElementById('book-form-error').classList.add('hidden');
}

async function submitBookForm(event) {
    event.preventDefault();
    const form = event.target;
    const errorElement = document.getElementById('book-form-error');
    const formData = new FormData(form);
    const data = {
        judul: formData.get('judul').trim(),
        penulis: formData.get('penulis').trim(),
        tahun: Number(formData.get('tahun')),
        stok: Number(formData.get('stok'))
    };

    if (!data.judul || !data.penulis || !Number.isInteger(data.tahun) || !Number.isInteger(data.stok) || data.stok < 0) {
        errorElement.textContent = 'Judul dan penulis wajib diisi. Tahun harus berupa angka, dan stok tidak boleh negatif.';
        errorElement.classList.remove('hidden');
        return;
    }

    try {
        await bookService.create(data);
        closeBookModal();
        showToast('Buku berhasil ditambahkan', 'success');
        loadBooks();
    } catch (error) {
        errorElement.textContent = error.message;
        errorElement.classList.remove('hidden');
    }
}

window.renderBooks = renderBooks;
