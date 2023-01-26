<tbody>

    @foreach($legals as $legal)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
                <a href="{{ route('legals.view', ['user'=>$legal->owner->id, 'legal'=>$legal->id]) }}">{{ $legal->name }}</a>
            </td>
            <td>{{ $legal->reg_number }}</td>
            <td>{{ $legal->inn }}</td>
            <td class="text-right">
                <div class="btn-group">
                    @can('legal_update')
                        <a class="tbale-btn" href="{{ route('legals.edit', ['legal'=>$legal->id]) }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    @endcan

                    @can('legal_delete')
                        <a class="tbale-btn" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                            document.getElementById('deleteLegal{{ $legal->id }}').submit(); return true }">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    @endcan

                </div>

                @can('legal_delete')
                    <form id="deleteLegal{{ $legal->id }}"
                          method="POST"
                          action="{{ route('legals.destroy', ['legal'=>$legal->id]) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                @endcan

            </td>
        </tr>
    @endforeach
</tbody>
