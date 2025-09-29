@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <h4><i class="fas fa-life-ring"></i> Assistance & Support</h4>
        </div>
        <div class="card-body">

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Formulaire --}}
            <form action="{{ route('contact.send') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', auth()->user()->name ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', auth()->user()->email ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Objet</label>
                    <input type="text" name="subject" class="form-control" required value="{{ old('subject') }}">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </form>

            {{-- Historique des tickets --}}
            @if(!empty($tickets) && count($tickets) > 0)
                <h5 class="mt-4"><i class="fas fa-clipboard-list"></i> Vos tickets récents</h5>
                <table class="table table-striped mt-2 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Objet</th>
                            <th>Message</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ Str::limit($ticket->message, 50) }}</td>
                                <td>
@if($ticket->status == 'Ouvert')
    <span class="badge bg-warning">Ouvert</span>
@elseif($ticket->status == 'En cours')
    <span class="badge bg-info">En cours</span>
@elseif($ticket->status == 'Clôturé')
    <span class="badge bg-success">Clôturé</span>
@endif


                                </td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Aucun ticket pour l’instant.</p>
            @endif

        </div>
    </div>
</div>
@endsection
