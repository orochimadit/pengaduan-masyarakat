@if ($errors->any())
    <div class="p-3 bg-pink-500 border border-pink-700 font-semibold">
        <ul class="ml-5">
            @foreach ($errors->all() as $i)
                <li class="list-disc">{{ $i }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::get('success'))
    <div class="p-3 bg-green-500 border border-green-700 font-semibold">
        <p>{{ Session::get('success') }}</p>
    </div>
@endif