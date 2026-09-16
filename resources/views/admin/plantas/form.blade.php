{{-- Autor: Simon Martinez Gomez --}}
<div class="admin-form-grid">
    <label class="admin-campo">
        <span>Nombre</span>
        <input type="text" name="nombre" required value="{{ old('nombre', $planta->nombre ?? '') }}">
    </label>

    <label class="admin-campo">
        <span>Categoría</span>
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

    <label class="admin-campo">
        <span>Precio (pesos)</span>
        <input type="number" name="precio" min="0" step="1" required
            value="{{ old('precio', $planta->precio ?? '') }}">
    </label>

    <label class="admin-campo">
        <span>Unidades disponibles</span>
        <input type="number" name="stock" min="0" step="1" required
            value="{{ old('stock', $planta->stock ?? 0) }}">
    </label>

    <label class="admin-campo admin-campo-completo">
        <span>Descripción</span>
        <textarea name="descripcion" rows="3" required>{{ old('descripcion', $planta->descripcion ?? '') }}</textarea>
    </label>

    <label class="admin-campo admin-campo-completo">
        <span>URL de imagen (opcional)</span>
        <input type="url" name="imagen_url" placeholder="https://..."
            value="{{ old('imagen_url', $planta->imagen_url ?? '') }}">
    </label>
</div>

<div class="admin-acciones-bar">
    <button type="submit" class="admin-btn">Guardar planta</button>
</div>
