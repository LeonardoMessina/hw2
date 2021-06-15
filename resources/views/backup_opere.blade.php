@extends('layouts.common')

@section('title', 'Backup opere')

@section('css')
@parent
<link href="css/backup_opere.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
    const BACKUP_OPERE_ROUTE = "{{route('backup_opere.backup')}}";
</script>
<script src="js/backup_opere.js" defer></script>
@endsection

@section('header_h1', 'Backup delle opere')

@section('content')
<div>
    <table>
        <thead>
            <tr>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($backups as $backup)
            <tr data-id='$backup->id'>
                <td>{{ $backup->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div id='sidePanel'>
        <div id='backupButton' class='button'>Esegui un backup delle opere!</div>
        <div id='message' class='hidden'></div>
    </div>
</div>
<a href="{{ route('personal_area') }}" class="button">Indietro</a>
@endsection