<!-- Modal catalogue-->
<div class="modal fade" id="catalogueModal" tabindex="-1" aria-labelledby="catalogueModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catalogueModalLabel">Catalogue</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
        <div class="row">


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Frein">
    <input type="hidden" name="Catalogue" value="Frein">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/frein.png') }}" alt="Frein" class="catalogue-img">
        <p class="card-category mt-2">Frein</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Moteur">
    <input type="hidden" name="Catalogue" value="Moteur">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/moteur.png') }}" alt="Moteur" class="catalogue-img">
        <p class="card-category mt-2">Moteur</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="EMB">
    <input type="hidden" name="Catalogue" value="Embrayage">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/embrayage.png') }}" alt="Embrayage" class="catalogue-img">
        <p class="card-category mt-2">Embrayage</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="COURROI">
    <input type="hidden" name="Catalogue" value="Courroies & Chaines">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/courroie.png') }}" alt="Courroie" class="catalogue-img">
        <p class="card-category mt-2">Courroies & Chaines</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="AMORT">
    <input type="hidden" name="Catalogue" value="Amortissement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/amortissement.png') }}" alt="Amortissement" class="catalogue-img">
        <p class="card-category mt-2">Amortissement</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="SUSP">
    <input type="hidden" name="Catalogue" value="Suspension">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/suspension.png') }}" alt="Suspension" class="catalogue-img">
        <p class="card-category mt-2">Suspension</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FILTRE">
    <input type="hidden" name="Catalogue" value="Filtre">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/filtre.png') }}" alt="Filtre" class="catalogue-img">
        <p class="card-category mt-2">Filtre</p>
      </div>
    </button>
  </form>
</div>




<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ECHAP">
    <input type="hidden" name="Catalogue" value="Echappement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/Echappement.png') }}" alt="Echappement" class="catalogue-img">
        <p class="card-category mt-2">Echappement</p>
      </div>
    </button>
  </form>
</div>




<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="DIR">
    <input type="hidden" name="Catalogue" value="Direction">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/direction.png') }}" alt="Direction" class="catalogue-img">
        <p class="card-category mt-2">Direction</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ALLUM">
    <input type="hidden" name="Catalogue" value="Allumage">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/allumage.png') }}" alt="Allumage" class="catalogue-img">
        <p class="card-category mt-2">Allumage</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="REFROI">
    <input type="hidden" name="Catalogue" value="Refroidissement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/Refroidissement.png') }}" alt="Refroidissement" class="catalogue-img">
        <p class="card-category mt-2">Refroidissement</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="cardan">
    <input type="hidden" name="Catalogue" value="Cardan">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/cardan.png') }}" alt="cardan" class="catalogue-img">
        <p class="card-category mt-2">cardan</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ARBRE">
    <input type="hidden" name="Catalogue" value="Arbres De Transmission">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/arbre.png') }}" alt="Arbres De Transmission" class="catalogue-img">
        <p class="card-category mt-2">Arbres De Transmission</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ROULEMEN">
    <input type="hidden" name="Catalogue" value="Roulement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/roulement.png') }}" alt="Roulement" class="catalogue-img">
        <p class="card-category mt-2">Roulement</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FIXA">
    <input type="hidden" name="Catalogue" value="Fixation">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/fixation.png') }}" alt="Fixation" class="catalogue-img">
        <p class="card-category mt-2">Fixation</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="VENTI">
    <input type="hidden" name="Catalogue" value="Ventilation">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/ventilation.png') }}" alt="Ventilation" class="catalogue-img">
        <p class="card-category mt-2">Ventilation</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FEU">
    <input type="hidden" name="Catalogue" value="Feux">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/feux.png') }}" alt="Feux" class="catalogue-img">
        <p class="card-category mt-2">Feux</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="retro">
    <input type="hidden" name="Catalogue" value="Rétroviseur">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/retroviseur.png') }}" alt="Rétroviseur" class="catalogue-img">
        <p class="card-category mt-2">Rétroviseur</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ESSUI">
    <input type="hidden" name="Catalogue" value="Système d'essuie-glaces">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/essui.png') }}" alt="Système d'essuie-glaces" class="catalogue-img">
        <p class="card-category mt-2">Essuie-glaces</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Huile">
    <input type="hidden" name="Catalogue" value="Huiles et Fluides">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/huile.png') }}" alt="Huiles" class="catalogue-img">
        <p class="card-category mt-2">Huiles et Fluides</p>
      </div>
    </button>
  </form>
</div>




<!-- Ajoute d'autres catalogues ici de la même manière -->

</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<style>
  .catalogue-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
  }
  .catalogue-card {
    cursor: pointer;
    transition: 0.3s;
  }
  .catalogue-card:hover {
    background-color: #f8f9fa;
  }
</style>
<!-- fin modal catalogue -->


