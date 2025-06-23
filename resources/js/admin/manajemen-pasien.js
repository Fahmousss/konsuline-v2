document.addEventListener('DOMContentLoaded', () => {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', async function () {
                    const id = this.dataset.id;

                    if (!id) {
                        showAlert('danger', 'ID pasien tidak ditemukan.');
                        return;
                    }

                    const confirmed = confirm('Yakin ingin menghapus pasien ini?');
                    if (!confirmed) return;

                    try {
                        const response = await fetch(`/admin/pasien/${id}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showAlert('success', data.message || 'Data berhasil dihapus.');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('danger', data.message || 'Gagal menghapus data.');
                        }

                    } catch (error) {
                        console.error(error);
                        showAlert('danger', 'Terjadi kesalahan saat menghapus data.');
                    }
                });
            });

            // Helper untuk tampilkan alert
            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                const header = document.querySelector('.content-header');
                if (header) {
                    header.insertAdjacentElement('afterend', alertDiv);
                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        alertDiv.classList.add('hide');
                        alertDiv.remove();
                    }, 4000);
                }
            }
        });
