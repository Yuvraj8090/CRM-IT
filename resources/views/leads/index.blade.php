
<x-app-layout>
   <div x-data="leadModal()" x-cloak class="min-h-screen">

        <div class="ml-64 min-h-screen p-6 bg-gray-100 dark:bg-gray-900">
            <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Leads</h1>
            <button @click="openModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Lead
            </button>
        </div>

        <!-- Lead Status Counts -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @foreach (\App\Models\LeadStatus::ordered()->get() as $status)
                <div class="p-4 rounded shadow flex items-center justify-between"
                    style="background-color: {{ $status->color ?? '#f3f4f6' }}">
                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $status->name }}</div>
                    <div class="text-gray-700 dark:text-gray-200 font-bold">
                        {{ \App\Models\Lead::where('lead_status', $status->name)->count() }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Leads Table -->
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4 overflow-x-auto">
            <table id="leads-table" class="min-w-full table-auto text-left">
                <thead>
                    <tr class="text-gray-700 dark:text-gray-200">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Profession</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Lead Modal -->
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
                <form @submit.prevent="saveLead()" class="space-y-4">

                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <input x-model="lead.name" required
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Email</label>
                        <input x-model="lead.email" type="email" required
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Phone</label>
                        <input x-model="lead.phone" type="text" required
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Profession</label>
                        <input x-model="lead.profession" type="text"
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Package</label>
                        <select x-model="lead.package_id" required
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                            <option value="">Select Package</option>
                            @foreach (\App\Models\Package::all() as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Lead Status</label>
                        <select x-model="lead.lead_status"
                            class="w-full p-3 rounded-xl border bg-gray-50 dark:bg-gray-700">
                            <option value="">Select Status</option>
                            @foreach (\App\Models\LeadStatus::ordered()->get() as $status)
                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

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
        <script>
            function leadModal() {
                return {
                    modalOpen: false,
                    modalTitle: 'Add Lead',

                    lead: {
                        id: '',
                        name: '',
                        email: '',
                        phone: '',
                        profession: '',
                        package_id: '',
                        lead_status: ''
                    },

                    table: null,

                    init() {
                        const self = this;

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        // DataTable
                        this.table = $('#leads-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('admin.leads.index') }}",
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'email',
                                    name: 'email'
                                },
                                {
                                    data: 'phone',
                                    name: 'phone'
                                },
                                {
                                    data: 'profession',
                                    name: 'profession'
                                },
                                {
                                    data: 'package',
                                    name: 'package.name',
                                    orderable: false,
                                    searchable: true
                                },
                                {
                                    data: 'lead_status',
                                    name: 'lead_status',
                                    render: function(data, type, row) {
                                        let status = row.lead_status || 'N/A';
                                        let color = '#e5e7eb';
                                        @foreach (\App\Models\LeadStatus::ordered()->get() as $statusItem)
                                            if (status === "{{ $statusItem->name }}") color =
                                                "{{ $statusItem->color }}";
                                        @endforeach
                                        return `<span class="px-2 py-1 rounded text-white" style="background-color:${color}">${status}</span>`;
                                    }
                                },
                                {
                                    data: 'created_at',
                                    name: 'created_at'
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ],
                            order: [
                                [7, 'desc']
                            ],
                        });

                        // Edit
                        window.addEventListener('edit-lead', e => {
                            self.openModal(e.detail);
                        });

                        // Delete
                        $('#leads-table').on('click', '.delete-btn', function() {
                            const id = $(this).data('id');
                            Swal.fire({
                                title: 'Delete this lead?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: `{{ url('/leads') }}/${id}`,
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
                    },

                    openModal(lead = null) {
                        this.modalTitle = lead ? 'Edit Lead' : 'Add Lead';
                        this.lead = lead ? {
                            ...lead
                        } : {
                            id: '',
                            name: '',
                            email: '',
                            phone: '',
                            profession: '',
                            package_id: '',
                            lead_status: ''
                        };
                        this.modalOpen = true;
                    },

                    closeModal() {
                        this.modalOpen = false;
                    },

                    saveLead() {
                        const self = this;
                        let formData = new FormData();
                        Object.keys(this.lead).forEach(k => formData.append(k, this.lead[k]));
                        let url = this.lead.id ? `{{ url('/admin/leads') }}/${this.lead.id}` : `{{ route('admin.leads.store') }}`;
                        if (this.lead.id) formData.append('_method', 'PUT');

                        $.ajax({
                            url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success(res) {
                                self.toast(res.message);
                                self.table.ajax.reload(null, false);
                                self.closeModal();
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
