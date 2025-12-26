<x-app-layout>
    <div x-data="leadStatusModal()" x-cloak class="min-h-screen">

        <div class="ml-64 min-h-screen p-6 bg-gray-100 dark:bg-gray-900">
            <div class="w-full">

                <!-- Header -->
                <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm">
                            <i class="fas fa-tags text-xl text-gray-700 dark:text-gray-200"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                            Lead Statuses
                        </h1>
                    </div>

                    <button @click="openModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Lead Status
                    </button>
                </header>

                <!-- Success -->
                @if (session('success'))
                    <div class="p-3 bg-green-600 text-white rounded shadow-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Table -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm overflow-x-auto">
                    <table id="leadStatusTable" class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th>Order</th>
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
                    <form @submit.prevent="saveLeadStatus()" class="space-y-4">

                        <!-- Name -->
                        <div>
                            <label class="block font-medium mb-1">Status Name</label>
                            <input x-model="leadStatus.name" required
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                        </div>

                        <!-- Color -->
                        <div>
                            <label class="block font-medium mb-1">Color (Tailwind)</label>
                            <input x-model="leadStatus.color"
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700"
                                placeholder="bg-green-600">
                        </div>

                        <!-- Order -->
                        <div>
                            <label class="block font-medium mb-1">Order</label>
                            <input type="number" x-model="leadStatus.order_by"
                                class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <select x-model="leadStatus.is_active"
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
            function leadStatusModal() {
                return {
                    modalOpen: false,
                    modalTitle: 'Add Lead Status',

                    leadStatus: {
                        id: '',
                        name: '',
                        color: '',
                        order_by: 0,
                        is_active: 1
                    },

                    table: null,

                    init() {
                        const self = this;

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        this.table = $('#leadStatusTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('admin.lead-statuses.index') }}",
                            columns: [{
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'color',
                                    orderable: false
                                },
                                {
                                    data: 'order_by',
                                    searchable: false
                                },
                                {
                                    data: 'is_active',
                                    searchable: false
                                },
                                {
                                    data: 'action',
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            order: [
                                [0, 'asc']
                            ]
                        });

                        window.addEventListener('edit-lead', e => this.openModal(e.detail));

                        $('#leadStatusTable').on('click', '.delete-btn', function() {
                            const id = $(this).data('id');

                            Swal.fire({
                                title: 'Are you sure?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: `{{ url('/admin/lead-statuses') }}/${id}`,
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

                    openModal(status = null) {
                        this.modalTitle = status ? 'Edit Lead Status' : 'Add Lead Status';

                        this.leadStatus = status ? {
                            id: status.id,
                            name: status.name,
                            color: status.color,
                            order_by: status.order_by ?? 0,
                            is_active: status.is_active ? 1 : 0
                        } : {
                            id: '',
                            name: '',
                            color: '',
                            order_by: 0,
                            is_active: 1
                        };

                        this.modalOpen = true;
                    },

                    closeModal() {
                        this.modalOpen = false;
                    },

                    saveLeadStatus() {
                        const payload = {
                            ...this.leadStatus,
                            is_active: this.leadStatus.is_active == 1
                        };

                        const url = payload.id ?
                            `{{ url('lead-statuses') }}/${payload.id}` :
                            `{{ route('lead-statuses.store') }}`;

                        $.ajax({
                            url,
                            type: payload.id ? 'PUT' : 'POST',
                            data: payload,
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
