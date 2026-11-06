function escapeHtml(value) {
    const element = document.createElement('div');
    element.textContent = value == null ? '' : String(value);
    return element.innerHTML;
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.setAttribute('role', 'status');

    document.body.appendChild(toast);
    window.setTimeout(() => toast.remove(), 3000);
}

window.escapeHtml = escapeHtml;
window.showToast = showToast;
