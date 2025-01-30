<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Note</th>
            <th>Type</th>
            <th>Viewing Username</th>
            <th>Viewer Username</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leads as $lead)
            <tr>
                <td>{{ $lead->id }}</td>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->phone }}</td>
                <td>{{ $lead->note }}</td>
                <td>{{ $lead->type == 1 ? 'Scanned' : ($lead->type == 2 ? 'Lead form' : ($lead->type == 3 ? 'Manually Add' : 'User To User')) }}
                </td>
                <td>{{ $lead->viewing_username }}</td>
                <td>{{ $lead->viewer_username }}</td>
                <td>{{ $lead->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
