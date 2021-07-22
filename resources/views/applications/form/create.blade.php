<form method="POST" action="">
    @csrf
    <div class="row">
        <div class="col-3">
            <div class="select" id="types">
                <header class="select-header">
                    <input type="text" placeholder="{{ __('The type of car...') }}" class="select-search">
                </header>
                <section class="select-body">
                    <div class="select-content">
                        <ul class="select-list">
                            @foreach($carList as $car)
                                <li class="select-item">
                                    @if ($loop->first)
                                        <a href="" class="active" data-name-id="type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                    @else
                                        <a href="" data-name-id="type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-9">
            <div id="selectGroup">
                <div class="row">
                    <div class="col-3">
                        <div class="select" id="marks">
                            <header class="select-header">
                                <input type="text" placeholder="{{ __('The brand of the car...') }}" class="select-search">
                            </header>
                            <section class="select-body">
                                <div class="select-content">
                                    <ul class="select-list" data-placeholder="Выберите тип авто">

                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="select" id="models">
                            <header class="select-header">
                                <input type="text" placeholder="{{ __('The car model...') }}" class="select-search">
                            </header>
                            <section class="select-body">
                                <div class="select-content">
                                    <ul class="select-list" data-placeholder="Выберите марку авто">
                                        <li class="placeholder">Выберите марку авто</li>
                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="select" id="years">
                            <header class="select-header">
                                <input type="text" placeholder="{{ __('The year of the car...') }}" class="select-search">
                            </header>
                            <section class="select-body">
                                <div class="select-content">
                                    <ul class="select-list" data-placeholder="Выберите модель авто">
                                        <li class="placeholder">Выберите модель авто</li>
                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div id="textArea" class="d-none">
                <div class="row">
                    <div class="col-12">
                        <label for="reg_number" class="col-md-4 col-form-label">
                            {{ __('Description of auto') }}</label>
                        <textarea class="form-control" id="autoDesc" rows="4" name="autoDesc"></textarea>

                    </div>
                </div>
            </div>
        </div>
        <div id="hiddenInputs"></div>
    </div>
</form>
