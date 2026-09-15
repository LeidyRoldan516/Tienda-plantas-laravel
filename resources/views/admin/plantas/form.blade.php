@if ($errors->any())
    <div style="color: #b91c1c">
        <p>Revisa estos campos:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p>
    <label>Nombre<br>
        <input type="text" name="nombre" required
               value="{{ old('nombre', $planta->nombre ?? '') }}">
    </label>
</p>
<p>
    <label>Descripción<br>
        <textarea name="descripcion" required>{{ old('descripcion', $planta->descripcion ?? '') }}</textarea>
    </label>
</p>
<p>
    <label>Precio (pesos)<br>
        <input type="number" name="precio" min="0" step="1" required
               value="{{ old('precio', $planta->precio ?? '') }}">
    </label>
</p>
<p>
    <label>Unidades disponibles<br>
        <input type="number" name="stock" min="0" step="1" required
               value="{{ old('stock', $planta->stock ?? '') }}">
    </label>
</p>
<p>
    <label>Categoría<br>
        <select name="categoria_id" required>
            <option value="">Selecciona una categoría</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('categoria_id', $planta->categoria_id ?? '') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </label>
</p>
<p>
    <label>URL de imagen (opcional)<br>
        <input type="url" name="imagen_url"
               value="{{ old('imagen_url', $planta->imagen_url ?? '') }}">
    </label>
</p>
<button type="submit">Guardar</button>
