<label for="claveProductoServicio">ClaveProductoServicio </label>
<input list="claveProductoServicioList" id="claveProductoServicio" name="claveProductoServicio" class="form-control">
<datalist id="claveProductoServicioList">
    @foreach ($claveProdServ as $item)
        <option value="{{ $item->clave }}" label="{{ $item->clave }} - {{ $item->descripcion }}">
    @endforeach
</datalist>
