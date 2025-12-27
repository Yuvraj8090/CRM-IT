<x-app-layout>
    <div x-data="packageModal()" x-cloak class="min-h-screen">

        <div class="ml-64 min-h-screen p-6 bg-gray-100 dark:bg-gray-900">
            <div class="w-full">

                <!-- Header -->
                <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm">
                            <i class="fas fa-box-open text-xl text-gray-700 dark:text-gray-200"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                            Packages
                        </h1>
                    </div>

                    <button @click="openModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Package
                    </button>
                </header>

                <!-- Flash -->
                @if (session('success'))
                    <div class="p-3 bg-green-600 text-white rounded shadow-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Table -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm overflow-x-auto">
                    <table id="packageTable" class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>PDF</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div x-show="modalOpen" x-transition.opacity
                class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">

                <div @click.outside="closeModal()" x-transition
                    class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-xl relative p-6">

                    <!-- Close -->
                    <button @click="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    <!-- Title -->
                    <div class="text-center border-b pb-3 mb-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white" x-text="modalTitle"></h2>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="savePackage()" class="space-y-4">

                        <!-- Name -->
                        <div>
                            <label class="block font-medium mb-1">Package Name</label>
                            <input x-model="pkg.name" required
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block font-medium mb-1">Description</label>
                            <textarea x-model="pkg.description" class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700"></textarea>
                        </div>

                        <!-- PDF -->
                        <div>
                            <label class="block font-medium mb-1">PDF File</label>
                            <input type="file" @change="pkg.pdf = $event.target.files[0]"
                                class="w-full p-2 rounded-xl border bg-gray-50 dark:bg-gray-700">
                            <template x-if="pkg.pdf_path">
                                <a :href="pkg.pdf_path" target="_blank"
                                    class="text-blue-600 text-sm underline mt-1 inline-block">
                                    View Existing PDF
                                </a>
                            </template>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <select x-model="pkg.is_active"
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2 pt-4">
                            <button type="button" @click="closeModal()" class="px-4 py-2 bg-gray-300 rounded-xl">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

      <script>
function packageModal() {
    return {
        modalOpen: false,
        modalTitle: 'Add Package',

        pkg: {
            id: '',
            name: '',
            description: '',
            pdf: null,
            pdf_path: '',
            is_active: 1
        },

        table: null,

        init() {
            const self = this;

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            // DATATABLE
            this.table = $('#packageTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.packages.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'pdf', orderable: false, searchable: false },
                    { data: 'is_active', searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });

            // EDIT
            window.addEventListener('edit-package', e => {
                self.openModal(e.detail);
            });

            // DELETE
            $('#packageTable').on('click', '.delete-btn', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Delete this package?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/admin/packages') }}/${id}`,
                            type: 'DELETE',
                            success(res) {
                                self.toast(res.message);
                                self.table.ajax.reload(null, false);
                            },
                            error() {
                                self.toast('Delete failed', 'error');
                            }
                        });
                    }
                });
            });

            // RESTORE
            $('#packageTable').on('click', '.restore-btn', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Restore this package?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post(`{{ url('/admin/packages') }}/${id}/restore`, {
                            _token: '{{ csrf_token() }}'
                        }, res => {
                            self.toast(res.message);
                            self.table.ajax.reload(null, false);
                        });
                    }
                });
            });
        },

        openModal(pkg = null) {
            this.modalTitle = pkg ? 'Edit Package' : 'Add Package';

            this.pkg = pkg ? {
                id: pkg.id,
                name: pkg.name,
                description: pkg.description,
                pdf: null,
                pdf_path: pkg.pdf_path ? `/storage/${pkg.pdf_path}` : '',
                is_active: pkg.is_active ? 1 : 0
            } : {
                id: '',
                name: '',
                description: '',
                pdf: null,
                pdf_path: '',
                is_active: 1
            };

            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
        },

        savePackage() {
            const self = this;
            let formData = new FormData();

            Object.keys(this.pkg).forEach(k => {
                if (this.pkg[k] !== null) {
                    formData.append(k, this.pkg[k]);
                }
            });

            formData.set('is_active', this.pkg.is_active == 1);

            let url = this.pkg.id
                ? `{{ url('/admin/packages') }}/${this.pkg.id}`
                : `{{ route('admin.packages.store') }}`;

            if (this.pkg.id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success(res) {
                    self.toast(res.message);
                    self.table.ajax.reload(null, false);
                    self.closeModal(); // ✅ modal closes
                },

                error(xhr) {
                    self.toast(xhr.responseJSON?.message || 'Error', 'error');
                }
            });
        },

        toast(message, icon = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon,
                title: message,
                showConfirmButton: false,
                timer: 2500
            });
        }
    }
}
</script>


    </div>
</x-app-layout>
