function renderCategories() {
    const app = document.getElementById('app-content');

    app.innerHTML = `
        <div class="space-y-6">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Kategori</h2>
                    <p class="mt-1 text-slate-500">Daftar kategori perpustakaan</p>
                </div>
                <button id="add-category-button" type="button" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-blue-700">
                    + Tambah Kategori
                </button>
            </div>
            <div id="categories-table-container" class="overflow-x-auto rounded-xl bg-white shadow-sm">
                <p class="p-6 text-slate-500">Memuat data kategori...</p>
            </div>
        </div>
        ${categoryFormModal()}
    `;

    document.getElementById('add-category-button').addEventListener('click', () => openCategoryModal());
    document.getElementById('close-category-modal').addEventListener('click', closeCategoryModal);
    document.getElementById('cancel-category-modal').addEventListener('click', closeCategoryModal);
    document.getElementById('category-form').addEventListener('submit', submitCategoryForm);
    document.getElementById('category-modal').addEventListener('click', (event) => {
        if (event.target.id === 'category-modal') {
            closeCategoryModal();
        }
    });

    loadCategories();
}

function categoryFormModal() {
    return `
        <div id="category-modal" class="fixed inset-0 z-10 hidden items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 id="category-modal-title" class="text-lg font-semibold text-slate-800">Tambah Kategori</h3>
                    <button id="close-category-modal" type="button" class="text-2xl leading-none text-slate-400 hover:text-slate-700" aria-label="Tutup">&times;</button>
                </div>
                <form id="category-form" class="mt-5 space-y-4">
                    <input name="id" type="hidden">
                    <label class="block text-sm font-medium text-slate-700">Nama
                        <input name="nama" type="text" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="block text-sm font-medium text-slate-700">Deskripsi
                        <textarea name="deskripsi" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></textarea>
                    </label>
                    <p id="category-form-error" class="hidden rounded-lg bg-red-50 p-3 text-sm text-red-700"></p>
                    <div class="flex justify-end gap-3 pt-2">
                        <button id="cancel-category-modal" type="button" class="rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    `;
}

async function loadCategories() {
    const container = document.getElementById('categories-table-container');

    try {
        const response = await categoryService.list();
        const categories = Array.isArray(response?.data) ? response.data : [];

        if (categories.length === 0) {
            container.innerHTML = '<p class="p-6 text-slate-500">Belum ada data kategori.</p>';
            return;
        }

        container.innerHTML = `
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    ${categories.map((category) => `
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">${escapeHtml(category.id)}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(category.nama)}</td>
                            <td class="px-6 py-4">${escapeHtml(category.deskripsi || '-')}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button type="button" data-action="edit" data-id="${escapeHtml(category.id)}" class="mr-2 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600">Edit</button>
                                <button type="button" data-action="delete" data-id="${escapeHtml(category.id)}" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        container.querySelectorAll('[data-action="edit"]').forEach((button) => {
            button.addEventListener('click', () => editCategory(Number(button.dataset.id)));
        });
        container.querySelectorAll('[data-action="delete"]').forEach((button) => {
            button.addEventListener('click', () => deleteCategory(Number(button.dataset.id)));
        });
    } catch (error) {
        container.innerHTML = `<p class="p-6 text-red-700">Gagal memuat kategori: ${escapeHtml(error.message)}</p>`;
    }
}

function openCategoryModal(category = null) {
    const modal = document.getElementById('category-modal');
    const form = document.getElementById('category-form');
    const errorElement = document.getElementById('category-form-error');

    form.reset();
    errorElement.classList.add('hidden');
    document.getElementById('category-modal-title').textContent = category ? 'Edit Kategori' : 'Tambah Kategori';
    form.elements.id.value = category?.id || '';
    form.elements.nama.value = category?.nama || '';
    form.elements.deskripsi.value = category?.deskripsi || '';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.elements.nama.focus();
}

function closeCategoryModal() {
    const modal = document.getElementById('category-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function editCategory(id) {
    try {
        const response = await categoryService.findById(id);
        openCategoryModal(response?.data || null);
    } catch (error) {
        showToast(`Gagal memuat kategori: ${error.message}`, 'error');
    }
}

async function submitCategoryForm(event) {
    event.preventDefault();
    const form = event.target;
    const errorElement = document.getElementById('category-form-error');
    const formData = new FormData(form);
    const nama = formData.get('nama').trim();
    const data = {
        nama,
        deskripsi: formData.get('deskripsi').trim() || null
    };

    if (!nama) {
        errorElement.textContent = 'Nama kategori wajib diisi.';
        errorElement.classList.remove('hidden');
        return;
    }

    try {
        const id = formData.get('id');
        if (id) {
            await categoryService.update(Number(id), data);
            showToast('Kategori berhasil diperbarui', 'success');
        } else {
            await categoryService.create(data);
            showToast('Kategori berhasil ditambahkan', 'success');
        }

        closeCategoryModal();
        loadCategories();
        window.dispatchEvent(new CustomEvent('categories:changed'));
    } catch (error) {
        errorElement.textContent = error.message;
        errorElement.classList.remove('hidden');
    }
}

async function deleteCategory(id) {
    if (!window.confirm('Apakah kategori ini akan dihapus?')) {
        return;
    }

    try {
        await categoryService.delete(id);
        showToast('Kategori berhasil dihapus', 'success');
        loadCategories();
        window.dispatchEvent(new CustomEvent('categories:changed'));
    } catch (error) {
        showToast(`Gagal menghapus kategori: ${error.message}`, 'error');
    }
}

window.renderCategories = renderCategories;
