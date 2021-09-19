@if(\Illuminate\Support\Facades\Auth::user()->hasRole('SECRETARY'))

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">REUNIONES Y BONOS</li>

            <x-li route="secretary.meeting.manage" icon='far fa-handshake' name="Gestionar reuniones" secondaries="secretary.meeting.manage.request"/>
            <x-li route="secretary.bonus.list" secondaries="secretary.bonus.create,secretary.bonus.edit" icon='fas fa-puzzle-piece' name="Gestionar bonos"/>
            <x-li route="secretary.defaultlist.list" secondaries="secretary.defaultlist.create,secretary.defaultlist.edit" icon='far fa-list-alt' name="Gestionar listas"/>

        </ul>
    </nav>
@endif
