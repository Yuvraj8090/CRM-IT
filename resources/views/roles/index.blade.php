<x-app-layout>
    <div x-data="roleModal()" x-cloak class="min-h-screen">

        <div class="ml-64 min-h-screen p-6 bg-gray-100 dark:bg-gray-900">
            <div class="w-full">

                <!-- Header -->
                <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm">
                            <i class="fas fa-user-shield text-xl text-gray-700 dark:text-gray-200"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                            Roles Management
                        </h1>
                    </div>

                    <button @click="openModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Role
                    </button>
                </header>

                <!-- Table -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm overflow-x-auto">
                    <table id="rolesTable" class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
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
                    class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl relative p-6">

                    <!-- Close -->
                    <button @click="closeModal()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>

                    <!-- Title -->
                    <div class="text-center border-b pb-3 mb-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white"
                            x-text="modalTitle"></h2>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="saveRole()" class="space-y-4">

                        <div>
                            <label class="block font-medium mb-1">Role Name</label>
                            <input x-model="role.name" required
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                        </div>

                        <div class="flex justify-end gap-2 pt-4">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2 bg-gray-300 rounded-xl">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-xl">
                                Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            function roleModal() {
                return {
                    modalOpen: false,
                    modalTitle: 'Add Role',

                    role: {
                        id: '',
                        name: ''
                    },

                    table: null,

                    init() {
                        const self = this;

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        this.table = $('#rolesTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('admin.roles.index') }}",
                            columns: [
                                { data: 'id', name: 'id' },
                                { data: 'name', name: 'name' },
                                {
                                    data: 'action',
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            order: [[0, 'asc']]
                        });

                        window.addEventListener('edit-role', e => this.openModal(e.detail));

                        $('#rolesTable').on('click', '.delete-btn', function() {
                            const id = $(this).data('id');

                            Swal.fire({
                                title: 'Are you sure?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: `{{ url('/admin/roles') }}/${id}`,
                                        type: 'DELETE',
                                        success: res => {
                                            self.toast(res.message);
                                            self.table.ajax.reload(null, false);
                                        }
                                    });
                                }
                            });
                        });
                    },

                    openModal(role = null) {
                        this.modalTitle = role ? 'Edit Role' : 'Add Role';

                        this.role = role ? {
                            id: role.id,
                            name: role.name
                        } : {
                            id: '',
                            name: ''
                        };

                        this.modalOpen = true;
                    },

                    closeModal() {
                        this.modalOpen = false;
                    },

                    saveRole() {
                        const url = this.role.id
                            ? `{{ url('/admin/roles') }}/${this.role.id}`
                            : `{{ route('admin.roles.store') }}`;

                        $.ajax({
                            url,
                            type: this.role.id ? 'PUT' : 'POST',
                            data: this.role,
                            success: res => {
                                this.toast(res.message);
                                this.table.ajax.reload(null, false);
                                this.closeModal();
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
