<div class="newtopbar">
    <div class="wrapper">
        <div class="newtopbar__mob">
            <span class="newtopbar__mobtitle"></span>
            <span class="newtopbar__mobarrow"></span>
        </div>
        <nav class="newtopbar__nav">
            <ul class="newtopbar__list s-between">
                <li class="newtopbar__item">
                    <a href="{{ route('applications.index') }}" class="newtopbar__link">Все</a>
                </li>
                <li class="newtopbar__item">
                    <a href="{{ route('applications.index', ['status_id' => 2]) }}" class="newtopbar__link">Хранение</a>
                </li>
                <li class="newtopbar__item">
                    <a href="{{ route('applications.index', ['status_id' => 1]) }}" class="newtopbar__link">Черновик</a>
                </li>
                <li class="newtopbar__item">
                    <a href="{{ route('application.accepting.request') }}" class="newtopbar__link">Постановка</a>
                </li>
                <li class="newtopbar__item{{ request()->routeIs('view_requests.index') ? ' active' : '' }}">
                    <a href="{{ route('view_requests.index') }}" class="newtopbar__link">Осмотр</a>
                </li>
                <li class="newtopbar__item{{ request()->routeIs('issue_requests.index') ? ' active' : '' }}">
                    <a href="{{ route('issue_requests.index') }}" class="newtopbar__link">Выдача</a>
                </li>
                <li class="newtopbar__item">
                    <a href="{{ route('applications.index', ['status_id' => 3]) }}" class="newtopbar__link">Выдано</a>
                </li>
                <li class="newtopbar__item">
                    <a href="{{ route('applications.index', ['status_id' => 6]) }}" class="newtopbar__link">Отклонено</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Дубли</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div class="newfilter">
    <div class="wrapper s-between">
        <div class="newfilter__search d-flex">
            <select name="" id="" class="newfilter__select">
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
            </select>
            <select name="" id="" class="newfilter__select">
                <option value="Стоянка">Стоянка</option>
                <option value="Стоянка">Стоянка</option>
                <option value="Стоянка">Стоянка</option>
            </select>
            <select name="" id="" class="newfilter__select">
                <option value="Исполнитель">Исполнитель</option>
                <option value="Исполнитель">Исполнитель</option>
                <option value="Исполнитель">Исполнитель</option>
            </select>
            <input type="text" class="newfilter__input" placeholder="Поиск по тексту">
        </div>
        <div class="newfilter__sort">
            <button class="newfilter__save newbtn">Сохраненые</button>
            <button class="newfilter__sortcol newbtn active">Колонки</button>
            <button class="newfilter__sortrow newbtn">Строки</button>
        </div>
    </div>
</div>
