$('#studentTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    ajax: {
        "url": students_list,
    },
    columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email'},
        { data: 'phone_number', name: 'phone_number'},
        { data: 'image', name: 'image', orderable: false },
        { data: 'teacher', name: 'teachers.name', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false},
    ],
});
